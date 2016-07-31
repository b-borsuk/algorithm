@extends('default')

@section('content')

    <header class="page-header">
      <h1>Biosystems <small>algorithm</small></h1>
    </header>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p>
                        Програма (веб-застосунок) визначає загальні властивості біосистеми, а саме визначає складність біосистеми (Hm) та рівень відносної організації (R). Також будує "Класифікаційну діаграму Біра". <br> Програма потребує дані: точність вимірювання Δx та показники тварин.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Data-in</h3>
                </div>

                <form action="{{ action('AlgoController@postBiosystems') }}" method="POST" role="form" enctype="multipart/form-data">
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="delta">Delta</label>
                            <input type="input" class="form-control" id="delta" name="delta" placeholder="N" @if (!empty($old_values['delta'])) value="{{ $old_values['delta'] }}" @endif>
                        </div>

                        <div class="row">
                            <label for="biosystems-data" class="col-xs-2 text-right">Biosystems Data</label>
                            <input type="file" class="form-file col-xs-10" id="biosystems-data" name="biosystems_data">
                        </div>
                    </div>

                    <div class="panel-footer">
                        {!! csrf_field() !!}
                        <button type="submit" class="btn btn-primary">Calculate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="result col-xs-12">
            @if (!empty($data))
                @include ('biosystems-result')
            @endif
        </div>
    </div>

@endsection
