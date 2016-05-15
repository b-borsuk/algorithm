var drawChart = function() {

    var chart_data_1 = new google.visualization.DataTable();
    chart_data_1.addColumn('number', 'Dose');
    chart_data_1.addColumn('number', 'Value');
    chart_data_1.addColumn('number', 'Max');
    chart_data_1.addRows(window.json_data_1);


    var options_chart_1 = {
        legend: 'none',
        colors: [
          'red',
        ],
        height: 400,
        title: 'Dose',
        seriesType: 'scatter',
        series: {
            1: {type: 'line'}
        },
        hAxis: {title: 'Dose', minValue: 0,  maxValue: maxH},
        vAxis: {title: 'Value', minValue: 0},
    };

    // Instantiate and draw the chart.
    var chart_1 = new google.visualization.ComboChart(document.getElementById('chart_1'));
    chart_1.draw(chart_data_1, options_chart_1);

    drawChart2();
}

var drawChart2 = function() {
    var chart_data_2 = new  google.visualization.arrayToDataTable(window.json_data_2);
    var maxH = window.maxH;
    var optimalLine = window.optimalLine;

    var options_chart_2 = {
        height: 400,
        width: 1000,
        title: 'Result Charts',
        curveType: 'function',
        hAxis: {title: 'Dose', minValue: 0, maxValue: maxH, gridlines: { count: 7 }},
        vAxis: {title: 'Value', minValue: 0, gridlines: { count: 7 }},
        crosshair: {
            color: '#000',
            trigger: 'selection'
        }
        // series: {
        //   optimalLine: {
        //     lineWidth: 5,
        //     lineDashStyle: [14, 2]
        //   },
        // }
    };

    console.log(options_chart_2);
    // Instantiate and draw the chart.
    var chart_2 = new google.visualization.LineChart(document.getElementById('chart_2'));
    chart_2.draw(chart_data_2, options_chart_2);
}
