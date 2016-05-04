@extends('default')

@section('content')
    <header class="page-header">
      <h1>Dynamic <small>algorithm</small></h1>
    </header>

    <div class="row">

        <div class="data col-xs-8">

            <header>
                <h3>Дані до задачі</h3>
            </header>

            <div class="content">

                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#first" aria-controls="first" role="tab" data-toggle="tab">Перший спосіб</a></li>
                    <!-- <li role="presentation"><a href="#second" aria-controls="second" role="tab" data-toggle="tab">Другий спосіб</a></li> -->
                </ul>

                <div class="tab-content">

                    <div role="tabpanel" class="tab-pane active" id="first">

                        <form action="{{ action('AlgoController@postDynamic') }}" method="POST" role="form" enctype="multipart/form-data">
                            <div class="panel panel-default m-t-md">

                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="days_count">Кількість днів, що прораховуються (N)</label>
                                        <input type="input" class="form-control" id="days_count" name="days_count" placeholder="N" @if (!empty($old_values['days_count'])) value="{{ $old_values['days_count'] }}" @endif>
                                    </div>

                                    <div class="form-group">
                                        <label for="need_materials">Кількість використовуваного матеріалу за день (&alpha;)</label>
                                        <input type="input" class="form-control" id="need_materials" name="need_materials" placeholder="&alpha;"  @if (!empty($old_values['need_materials'])) value="{{ $old_values['need_materials'] }}" @endif>
                                    </div>

                                    <div class="form-group">
                                        <label for="step">Кратність партії (&Delta;)</label>
                                        <input type="input" class="form-control" id="step" name="step" placeholder="&Delta;"  @if (!empty($old_values['step'])) value="{{ $old_values['step'] }}" @endif>
                                    </div>

                                    <div class="form-group">
                                        <label for="max_residue">Максимальна кількість злишкового матеріалу (E)</label>
                                        <input type="input" class="form-control" id="max_residue" name="max_residue" placeholder="E"  @if (!empty($old_values['max_residue'])) value="{{ $old_values['max_residue'] }}" @endif>
                                    </div>

                                    <hr>

                                    <div class="form-group">
                                        <label for="dynamic_data">Дані про ціни за поставку та зберігання матеріалів</label>
                                        <input type="file" id="dynamic_data" name="dynamic_data">
                                    </div>

                                </div>

                                <div class="panel-footer">
                                    {!! csrf_field() !!}
                                    <button type="submit" class="btn btn-primary">Підрахувати</button>
                                </div>

                            </div>
                        </form>

                    </div>

                    <!-- <div role="tabpanel" class="tab-pane" id="second">

                        <form action="{{ action('AlgoController@postDynamic') }}" class="form-horizontal" method="POST" role="form" enctype="multipart/form-data">
                            <div class="panel panel-default m-t-md">

                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="days_count" class="col-sm-5 control-label">Кількість днів, що прораховуються (N)</label>
                                        <div class="col-sm-7">
                                            <input type="input" class="form-control" id="days_count" name="days_count" placeholder="N">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="need_materials" class="col-sm-5 control-label">Кількість використовуваного матеріалу за день (&alpha;)</label>
                                        <div class="col-sm-7">
                                            <input type="input" class="form-control" id="need_materials" name="need_materials" placeholder="&alpha;">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="step" class="col-sm-5 control-label">Кратність партії (&Delta;)</label>
                                        <div class="col-sm-7">
                                            <input type="input" class="form-control" id="step" name="step" placeholder="&Delta;">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="max_residue" class="col-sm-5 control-label">Максимальна кількість злишкового матеріалу (E)</label>
                                        <div class="col-sm-7">
                                            <input type="input" class="form-control" id="max_residue" name="max_residue" placeholder="E">
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-group">
                                        <label for="dynamic_data" class="col-sm-5 control-label">Дані про ціни за поставку та зберігання матеріалів</label>
                                        <div class="col-sm-7">
                                            <input type="file" id="dynamic_data" name="dynamic_data">
                                        </div>
                                    </div>

                                </div>

                                <div class="panel-footer">
                                    {!! csrf_field() !!}
                                    <button type="submit" class="btn btn-primary">Підрахувати</button>
                                </div>

                            </div>
                        </form>

                    </div> -->

                </div>

            </div>

        </div>

        <div class="result col-xs-8">
            @if (!empty($data))
                @include ('dynamic-result')
            @endif
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
