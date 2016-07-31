<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public function __construct(Request $request)
    {
        self::setProjects($request);
    }

    public function setProjects(Request $request)
    {
        $current_route_name = $request->route()->getName();


        $projects = [
            'biosystems' => [
                'name' => 'Biosystems',
                'url' => action('AlgoController@getBiosystems'),
                'active' => $current_route_name == 'biosystems',
            ],
            'sugar' => [
                'name' => 'Sugar',
                'url' => action('AlgoController@getSugar'),
                'active' => $current_route_name == 'sugar',
            ],
            'dynamic' => [
                'name' => 'Dynamic',
                'url' => action('AlgoController@getDynamic'),
                'active' => $current_route_name == 'dynamic',
            ],
        ];

        $active_project = array_first($projects, function ($key, $value) {
            return $value['active'];
        });

        view()->share('projects', $projects);

        view()->share('active_project', $active_project);
    }
}
