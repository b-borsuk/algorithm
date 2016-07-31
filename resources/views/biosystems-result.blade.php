<header>
    <h3>Результати обрахунків</h3>
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

        <ul class="nav nav-tabs" role="tablist">
            @foreach ($data->getResult() as $number => $number_value)
                <li role="presentation" @if ($number == 0) class="active" @endif><a href="#number_{{ $number }}" aria-controls="number_{{ $number }}" role="tab" data-toggle="tab">Тварина {{ $number + 1 }}</a></li>
            @endforeach
        </ul>

        <div class="tab-content">

            @foreach ($data->getResult() as $number => $number_value)
                <div role="tabpanel" class="tab-pane @if ($number == 0) active @endif" id="number_{{ $number }}">

                     <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="wraper-table table-responsive">
                                <table class="table table-bordered">
                                    <caption>Вхідні дані</caption>
                                    <tbody>
                                        <tr>
                                            <th class="text-center vertical-middle" scope="row">X</th>

                                            @foreach ($number_value['initial_data'] as $key => $x)
                                                <td class="text-center vertical-middle">{{ $x }}</td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="wraper-table table-responsive">
                                @foreach ($number_value['data'] as $key => $group)

                                    <table class="table table-bordered">
                                        <caption>Група {{ $key+1 }}</caption>
                                        <tbody>
                                            <tr>
                                                <th class="text-center vertical-middle" scope="row">X</th>

                                                @foreach ($group['data'] as $x)
                                                    <td class="text-center vertical-middle">{{ $x }}</td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <th class="text-center vertical-middle" scope="row">P {{ $key+1 }}</th>

                                                <td class="text-center vertical-middle">{{ round($group['p'],4) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endforeach
                            </div>

                            <dl class="dl-horizontal">
                              <dt>N</dt>
                              <dd>{{ $number_value['N'] }}</dd>
                              <dt>n</dt>
                              <dd>{{ $number_value['n'] }}</dd>
                              <dt>Hm</dt>
                              <dd>{{ round($number_value['Hm'],4) }}</dd>
                              <dt>H</dt>
                              <dd>{{ round($number_value['H'],4) }}</dd>
                              <dt>R</dt>
                              <dd>{{ round($number_value['R'],4) }}</dd>
                            </dl>

                            <p class="h4">{{ $data->getText($number) }}</p>

                            <div class="chart-conteiner" id="chart-{{ $number }}" data-chart-id="chart-{{ $number }}" data-name="{{ $number }}" data-json="{{ $data->getJsonData($number) }}"></div>
                        </div>
                    </div>

                </div>
            @endforeach

        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <p class="h4"> {{ $data->getSystemType() }} </p>

                <div class="chart-all" id="chart-all" data-json="{{ $data->getJsonData() }}"></div>
            </div>
        </div>

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

        <script type="text/javascript" src="/js/app-2.js?v3"></script>


        <script type="text/javascript">
            $(document).ready(function() {
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawCharts);
            })
        </script>

    </div>

@endif
