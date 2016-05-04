<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DynamicRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'days_count'           => 'required|numeric',
            'need_materials'       => 'required|numeric',
            'step'                 => 'required|numeric',
            'max_residue'          => 'required|numeric',
            'X'                    => 'required_without:dynamic_data|array',
            'materials_price_data' => 'required_without:dynamic_data|array',
            'rent_data'            => 'required_without:dynamic_data|array',
            'dynamic_data'         => 'max:2048',
        ];
    }
}
