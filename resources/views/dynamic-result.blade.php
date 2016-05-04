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

        <div class="panel panel-default">

            <div class="panel-body">
                <div class="wraper-table table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th scope="row" class="text-center vertical-middle">X</th>
                                @foreach ($data->X as $x_value)
                                    <td>{{ $x_value }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <th scope="row" class="text-center vertical-middle">P(X)</th>
                                @foreach ($data->X as $x_value)
                                    @if (isset($data->materials_price_data[$x_value]))
                                        <td>{{ $data->materials_price_data[$x_value] }}</td>
                                    @else
                                        <td> - </td>
                                    @endif
                                @endforeach
                            </tr>
                            <tr>
                                <th scope="row" class="text-center vertical-middle">Q(X)</th>
                                @foreach ($data->X as $x_value)
                                    @if (isset($data->rent_data[$x_value]))
                                        <td>{{ $data->rent_data[$x_value] }}</td>
                                    @else
                                        <td> - </td>
                                    @endif
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>


        <ul class="nav nav-tabs" role="tablist">
            @foreach ($data->getResult() as $day => $day_value)
                <li role="presentation" @if ($day == 1) class="active" @endif><a href="#day_{{ $day }}" aria-controls="day_{{ $day }}" role="tab" data-toggle="tab">Дунь {{ $day }}</a></li>
            @endforeach
        </ul>

        <div class="tab-content">

            @foreach ($data->getResult() as $day => $day_value)
                <div role="tabpanel" class="tab-pane @if ($day == 1) active @endif" id="day_{{ $day }}">
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <div class="wraper-table table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center vertical-middle">X</th>
                                            <th class="text-center vertical-middle">F(x)</th>
                                            <th class="text-center vertical-middle">U</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($day_value['data'] as $remainder => $for_remainder)
                                            <tr @if ($day_value['optimal'] == $remainder) class="success" @endif role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_{{ $day }}_{{ $remainder }}" aria-expanded="true" aria-controls="collapseOne">
                                                <td class="text-center vertical-middle" scope="row">{{ $remainder }}</td>
                                                <td class="text-center vertical-middle">{{ $for_remainder['min'] }}</td>
                                                <td class="text-center vertical-middle">{{ $for_remainder['optimal_purchase'] }}</td>
                                            </tr>
                                            <tr id="collapse_{{ $day }}_{{ $remainder }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                                <td scope="row" colspan="3">
                                                    <div class="wraper-table table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center vertical-middle">X</th>
                                                                    <th class="text-center vertical-middle">F(x)</th>
                                                                    <th class="text-center vertical-middle">U</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($for_remainder['data'] as $purchase => $for_purchase)
                                                                    <tr @if ($for_remainder['min'] == $for_purchase) class="success" @endif>
                                                                        <td class="text-center vertical-middle" scope="row">{{ $remainder }}</td>
                                                                        <td class="text-center vertical-middle">{{ $for_purchase }}</td>
                                                                        <td class="text-center vertical-middle">{{ $purchase }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            @endforeach

        </div>

    </div>

@endif
