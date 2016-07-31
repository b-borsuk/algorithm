<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

use App\Http\Requests;
use App\Datas\SugarData;
use App\Datas\DynamicData;
use App\Datas\BiosystemsData;

class AlgoController extends Controller
{

    /**
     *
     */
    public function getHome(Request $request)
    {
        return view('default');
    }

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

        return view('sugar', [
            'data' => $data,
        ]);
    }

    /**
     *
     */
    public function getBiosystems(Request $request)
    {
        return view('biosystems');
    }

    /**
     *
     */
    public function postBiosystems(Requests\BiosystemsRequest $request)
    {
        $biosystems_file = $request->file('biosystems_data');

        $data = new BiosystemsData($request->all(), $biosystems_file);

        // dd($data);
        return view('biosystems', [
            'data' => $data,
        ]);
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

        $data->calcAllResult();

        return view('dynamic', [
            'data' => $data,
            'old_values' => $request->all()
        ]);
    }

}
