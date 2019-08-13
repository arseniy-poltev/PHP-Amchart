<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        #chartdiv {
            width: 100%;
            height: 500px;
        }

    </style>
    <!-- Resources -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://www.amcharts.com/lib/4/core.js"></script>
    <script src="https://www.amcharts.com/lib/4/charts.js"></script>
    <script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
</head>
<body>
<!-- HTML -->

<div  class="p-5">
    <div class="row">
        <div id="chartdiv">

           <h1> Please Search by name or URL</h1>
        </div>
    </div>

    <div class="row pt-5 search_box">
        <div class="col-md-6 row">
            <div class="col-8 form-group">
                <input class="form-control name_box" placeholder="Name">
            </div>
            <div class="col-4">
                <button class="btn btn-primary name_button">Search</button>
            </div>
        </div>

        <div class="col-md-6 row">
            <div class="col-8 form-group">
                <input class="form-control url_box" placeholder="Url" type="number">
            </div>
            <div class="col-4">
                <button class="btn btn-primary url_button">Search</button>
            </div>
        </div>
    </div>
</div>
<!-- loader-->
<div class="modal fade bd-example-modal-lg" data-backdrop="static" data-keyboard="false" tabindex="-1"
     id="loader_modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="width: 48px">
            <span class="fa fa-spinner fa-spin fa-3x"></span>
        </div>
    </div>
</div>
<script>
    dispChart(null);

    function start_loading() {
        $('#loader_modal').modal('show');
    }

    function exit_loading() {
        $('#loader_modal').modal('hide');
    }
    $('.name_button').on('click', function () {
        $('.url_box').val('');

       var name = $('.name_box').val();
       if (name == "") {
           return
       }
        dispChart('dd');
        start_loading()
        $('#loader_modal').modal('show');
        $.ajax({
           url: 'server.php',
           type: 'post',
           dataType: 'json',
           data: {
               name: name
           },
           success: function (res) {
               exit_loading()
               if (res.state == 'success') {
                   var data = res.data;
                   dispChart(data);
               } else {

               }
           },
            error() {
                exit_loading()
            }
       })
    });

    $('.url_button').on('click', function () {
        $('.name_box').val('')

        var url = $('.url_box').val();
        if (url == "") {
            return
        }
        dispChart('dd');
        start_loading()
        $.ajax({
            url: 'server.php',
            type: 'post',
            dataType: 'json',
            data: {
                url: url
            },
            success: function (res) {
                exit_loading()
                if (res.state == 'success') {
                    var data = res.data;
                    dispChart(data);
                } else {

                }
            },
            error() {
                exit_loading();
            }
        })
    });

    function dispChart(data) {
        am4core.useTheme(am4themes_animated);
        // Themes end

        // Create chart instance
        var chart = am4core.create("chartdiv", am4charts.XYChart);

        // Add data
        chart.data = data;

        // Create axes
        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.minGridDistance = 50;

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

        // Create series
        var series = chart.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = "y";
        series.dataFields.dateX = "x";
        series.dataFields.label = "names";
        series.strokeWidth = 2;
        series.minBulletDistance = 10;
        series.tooltipText = "{label}";
        series.tooltip.pointerOrientation = "vertical";
        series.tooltip.background.cornerRadius = 20;
        series.tooltip.background.fillOpacity = 0.5;
        series.tooltip.label.padding(12,12,12,12)

        // Add scrollbar
        chart.scrollbarX = new am4charts.XYChartScrollbar();
        chart.scrollbarX.series.push(series);

        // Add cursor
        chart.cursor = new am4charts.XYCursor();
        chart.cursor.xAxis = dateAxis;
        chart.cursor.snapToSeries = series;
    }

    // Themes begin


    function generateChartData() {
        var chartData = [];
        var firstDate = new Date();
        firstDate.setDate(firstDate.getDate() - 1000);
        var visits = 1200;
        for (var i = 0; i < 500; i++) {
            // we create date objects here. In your data, you can have date strings
            // and then set format of your dates using chart.dataDateFormat property,
            // however when possible, use date objects, as this will speed up chart rendering.
            var newDate = new Date(firstDate);
            newDate.setDate(newDate.getDate() + i);

            visits += Math.round((Math.random()<0.5?1:-1)*Math.random()*10);

            chartData.push({
                date: newDate,
                visits: visits
            });
        }
        console.log(chartData)
        return chartData;
    }
</script>

<style>
    .bd-example-modal-lg .modal-dialog {
        display: table;
        position: relative;
        margin: 0 auto;
        top: calc(50% - 24px);
    }

    .bd-example-modal-lg .modal-dialog .modal-content {
        background-color: transparent;
        border: none;
    }
</style>
</body>

</html>