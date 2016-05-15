<header>
    <h3>Результати</h3>
</header>

@if ($data->hasErrors())

    @foreach ($data->getErrorsMessages() as $error)
        <div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            {{ $error }}
        </div>
    @endforeach

@else

    <div class="content">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="wraper-table table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th scope="row" class="text-center vertical-middle">Dose</th>
                                <th scope="row" class="text-center vertical-middle">Mat Spod</th>
                                <th scope="row" class="text-center vertical-middle">L</th>
                                <th scope="row" class="text-center vertical-middle">Pow</th>
                            </tr>

                            @foreach ($data->data as $doses => $params)
                                <tr>
                                    <td>{{ $doses }}</td>
                                    <td>{{ $params['mat'] }}</td>
                                    <td>{{ $params['L'] }}</td>
                                    <td>{{ $params['pow'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs" role="tablist">
            <li role="prese
            ntation" class="active"><a href="#block_chart_1" aria-controls="block_chart_1" role="tab" data-toggle="tab">Графік початкових даних</a></li>
            <li role="presentation"><a href="#block_chart_2" aria-controls="block_chart_2" role="tab" data-toggle="tab">Графік результатів</a></li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="block_chart_1">
                <div id="chart_1"></div>
            </div>

            <div role="tabpanel" class="tab-pane" id="block_chart_2">
                <div id="chart_2"></div>
            </div>
        </div>

    </div>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">
        window.json_data_1 = {!! $data->getJsonPoints() !!};
        window.json_data_2 = {!! $data->getJsonResult() !!};
        window.maxH = {!! $data->getMaxH() !!};
        window.optimalLine = {!! $data->getOptimalLineNumber() !!};
    </script>

    <script type="text/javascript" src="/js/app.js?v2"></script>


    <script type="text/javascript">
        $(document).ready(function() {
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            // google.charts.load('current', {'packages':['corechart']});
            // google.charts.setOnLoadCallback(drawChart2);
        })
    </script>


@endif
