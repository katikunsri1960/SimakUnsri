// area chart
 Morris.Area({
        element: 'area-chart3',
        data: [{
                    period: '2013',
                    data1: 15,
                    data2: 25
                }, {
                    period: '2014',
                    data1: 55,
                    data2: 20
                }, {
                    period: '2015',
                    data1: 25,
                    data2: 55
                }, {
                    period: '2016',
                    data1: 65,
                    data2: 15
                }, {
                    period: '2017',
                    data1: 35,
                    data2: 25
                }, {
                    period: '2018',
                    data1: 30,
                    data2: 85
                }, {
                    period: '2019',
                    data1: 25,
                    data2: 15
                }


                ],
                lineColors: ['#5840ba', '#fb84e2'],
                xkey: 'period',
                ykeys: ['data1', 'data2'],
                labels: ['Material', 'Exams'],
                pointSize: 0,
                lineWidth: 3,
                resize:true,
                fillOpacity: 0.5,
                behaveLikeLine: true,
                gridLineColor: '#e0e0e0',
                hideHover: 'auto'
        
    });
	
	