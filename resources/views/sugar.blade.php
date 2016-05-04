@extends('default')

@section('content')

    <div class="row">
        <header class="page-header">
          <h1>Sugar <small>algorithm</small></h1>
        </header>

        <div class="col-xs-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Data-in</h3>
                </div>

                <form action="{{ action('AlgoController@postSugar') }}" method="POST" role="form" enctype="multipart/form-data">
                    <div class="panel-body">
                        <div class="row">
                            <label for="sugar-data" class="col-xs-2 text-right">Sugar Data</label>
                            <input type="file" class="form-file col-xs-10" id="sugar-data" name="sugar_data">
                        </div>
                    </div>

                    <div class="panel-footer">
                        {!! csrf_field() !!}
                        <button type="submit" class="btn btn-primary">Calculate</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- <aside class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <form action="{{ action('AlgoController@postSugar') }}" method="POST" role="form">
                <legend>Get info by</legend>

                <div class="form-group">
                    <label for="">label</label>
                    <input type="text" class="form-control" id="" placeholder="Input field">
                </div>



                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </aside> -->
    </div>

@endsection
