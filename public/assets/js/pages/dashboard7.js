//[Dashboard Javascript]

//Project:	EduAdmin - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)


$(function () {

  'use strict';
	
	
	  var options = {
          chart: {
            height: 105,
            type: "radialBar"
          },

          series: [70],
            colors: ['#fff'],
          plotOptions: {
            radialBar: {
              hollow: {
                margin: 0,
                size: "50%"
              },
              track: {
                background: '#b5b5c3',
              },

              dataLabels: {
                showOn: "always",
                name: {
                  offsetY: -10,
                  show: false,
                  color: "#888",
                  fontSize: "13px"
                },
                value: {
                  color: "#fff",
                  offsetY: 5,
                  fontSize: "14px",
                  show: true
                }
              }
            }
          },

          stroke: {
            lineCap: "round",
          },
          labels: ["Progress"]
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);

        chart.render();



	
	
	
}); // End of use strict

    $(function() {
        'use strict'
        $('.easypie').easyPieChart({
            easing: 'easeOutBounce',
            onStep: function(from, to, percent) {
                $(this.el).find('.percent').text(Math.round(percent));
            }
        });
        var chart = window.chart = $('.easypie').data('easyPieChart');
        $('.js_update').on('click', function() {
            chart.update(Math.random()*200-100);
        });
    });