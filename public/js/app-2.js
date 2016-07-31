var drawCharts = function() {
    $('.chart-conteiner').each(function () {
        var $chart = $(this);
        var name = $chart.data('name');
        var json = $chart.data('json');
        var chart_id = $chart.data('chart-id');

        var chart_data = new google.visualization.DataTable();

        chart_data.addColumn('number', 'R');
        chart_data.addColumn('number', 'Hm');
        chart_data.addRows(json);

        var options_chart = {
            legend: 'none',
            colors: [
              'red',
            ],
            height: 400,
            title: 'Діаграма Біра',
            seriesType: 'scatter',
            series: {
                1: {type: 'line'}
            },
            hAxis: {title: 'R', minValue: 0,  maxValue: 1, ticks:[0,0.1,0.3,1]},
            vAxis: {title: 'Hm', minValue: 0, ticks:[0,3,6]},
        };

        var chart_show = new google.visualization.ComboChart(document.getElementById(chart_id));
        chart_show.draw(chart_data, options_chart);
    })
    drawChartAll();
}

var drawChartAll = function() {
    var $chart = $('#chart-all');
    var json = $chart.data('json');
    console.log(json);
    var chart_data = new google.visualization.DataTable();

    chart_data.addColumn('number', 'R');
    chart_data.addColumn('number', 'Hm');
    chart_data.addRows(json);

    var options_chart = {
        legend: 'none',
        colors: [
          'red',
        ],
        height: 400,
        title: 'Діаграма Біра',
        seriesType: 'scatter',
        series: {
            1: {type: 'line'}
        },
        hAxis: {title: 'R', minValue: 0,  maxValue: 1, ticks:[0,0.1,0.3,1]},
        vAxis: {title: 'Hm', minValue: 0, ticks:[0,3,6]},
    };

    var chart_show = new google.visualization.ComboChart(document.getElementById('chart-all'));
    chart_show.draw(chart_data, options_chart);
}
