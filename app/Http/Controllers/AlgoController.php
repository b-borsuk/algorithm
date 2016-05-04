<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

use App\Http\Requests;
use App\Datas\SugarData;
use App\Datas\DynamicData;

class AlgoController extends Controller
{
    /**
     *
     */
    public function getSugar(Request $request)
    {
        return view('sugar');
    }

    /**
     *
     */
    public function postSugar(Requests\SugarRequest $request)
    {
        $sugar_file = $request->file('sugar_data');

        $data = new SugarData($sugar_file);

        dd($data);
        return 'ssfsdsd';
    }

    /**
     *
     */
    public function getDynamic(Request $request)
    {
        return view('dynamic');
    }

    /**
     *
     */
    public function postDynamic(Requests\DynamicRequest $request)
    {
        $file = null;

        if ($request->hasFile('dynamic_data'))
            $file = $request->file('dynamic_data');

        $data = new DynamicData($request->all(), $file);

        $data->calculate();

        $data->processResult();

        return view('dynamic', [
            'data' => $data,
            'old_values' => $request->all()
        ]);
    }


}
