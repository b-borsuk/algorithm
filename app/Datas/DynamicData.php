<?php

namespace App\Datas;

use App\Datas\Data;
use Illuminate\Http\UploadedFile;
use Exception;

/**
*
*/
class DynamicData extends Data
{
    protected $main_values = [
        'days_count',
        'need_materials',
        'step',
        'max_residue'
    ];

    protected $additional_values = [
        'X',
        'materials_price_data',
        'rent_data',
    ];

    protected $data = [];

    public $days_count;

    public $need_materials;

    public $step;

    public $max_residue;

    protected $additional_data = [];

    public $X = [];

    public $materials_price_data = [];

    public $rent_data = [];


    function __construct(array $data, $file = null)
    {
        $file_data = [];

        if (!is_null($file)) {
            $file_data = $this->processFile($file);
        }

        self::checkData($data, $file_data);

        self::setData($data, $file_data);
    }

    public function processFile($file)
    {
        $this->addFile($file);

        $data = $this->getFileData();

        return $data;
    }

    public function checkData(array $data, array $file_data = [])
    {
        $initial_data = array_merge($data, $file_data);

        $main_values = $this->main_values;
        $additional_values = $this->additional_values;

        foreach ($main_values as $value) {
            if (!array_key_exists($value, $initial_data)) {
                $this->addError(new Exception("Main Data incomplete"), 'main_data');
                break;
            }
        }

        foreach ($additional_values as $value) {
            if (!array_key_exists($value, $initial_data)) {
                $this->addError(new Exception("Additional Data incomplete"), 'additional_data');
                break;
            }
        }

    }

    public function setData(array $data, array $file_data = [])
    {
        if ($this->incorrectly) {
            return null;
        }

        $this->setMainData(array_only($data, $this->main_values));

        if (!empty($file_data)) {
            $this->setAdditionalData($file_data);
        } else {
            $this->setAdditionalData(array_only($file_data, $this->additional_values));
        }

    }

    public function setMainData(array $data)
    {
        $this->data = $data;

        $this->days_count = $data['days_count'];

        $this->need_materials = $data['need_materials'];

        $this->step = $data['step'];

        $this->max_residue = $data['max_residue'];
    }


    public function setAdditionalData(array $data)
    {
        $this->additional_data = $data;

        $this->X = $data['X'];

        $this->materials_price_data = $data['materials_price_data'];

        $this->rent_data = $data['rent_data'];
    }

    public function normaliseFileData(array $data)
    {
        if (count($data) != 3) {
            $this->addError(new Exception("File Data is not validated"), 'file_data');
            return $data;
        }

        $result = [];

        $result['X'] = $data[0];
        $result['materials_price_data'] = self::setArrayKey($data[1],  $data[0]);
        $result['rent_data'] = self::setArrayKey($data[2],  $data[0]);

        return $result;
    }

    public function problemFunction()
    {
        $days_count = $this->days_count;
        $need_materials = $this->need_materials;
        $max_residue = $this->max_residue;
        $step = $this->step;

        $materials_price_data = $this->materials_price_data;
        $rent_data = $this->rent_data;
        $result = [];

        for ($i = $days_count; $i > 0; $i--) {
            $rez[$i] = [];
            $x_min = 0;
            $x_max = 0;

            if ($i != 1) {
                $x_max = min(
                    ($days_count - $i + 1) * $need_materials,
                    $max_residue
                );
            }

            for ($j = $x_min; $j <= $x_max; $j += $step) {
                $y_min = max($need_materials - $j, 0);
                $y_max = min(
                    ($days_count - $i + 1) * $need_materials - $j,
                    $need_materials + $max_residue - $j
                );

                for ($k = $y_min; $k <= $y_max; $k += $step) {
                    $price_for_material = isset($materials_price_data[$k]) ? $materials_price_data[$k] : 0;
                    $price_for_rent = isset($rent_data[$j]) ? $rent_data[$j] : 0;

                    $f_temp = $price_for_material + $price_for_rent;

                    if($i != $days_count) {
                        $f_temp += $result[$i+1]['data'][$k+$j-$need_materials]['min'];
                    }

                    $result[$i]['data'][$j]['data'][$k] = $f_temp;
                    if(empty($result[$i]['data'][$j]['min']) || $result[$i]['data'][$j]['min'] > $f_temp) {
                        $result[$i]['data'][$j]['min'] = $f_temp;
                        $result[$i]['data'][$j]['optimal_purchase'] = $k;
                    }
                }
            }
        }

        ksort($result);
        return $result;
    }

    public function processResult()
    {
        $result = $this->getResult();

        foreach ($result as $day => &$for_day) {
            if ($day == 1) {
                $min_value = null;
                $min_remainder = array_keys($for_day['data'])[0];

                foreach ($for_day['data'] as $remainder => $for_remainder) {
                    if (is_null($min_value) || $min_value > $for_remainder['min']) {
                        $min_value = $for_remainder['min'];
                        $min_remainder = $remainder;
                    }
                }

                $for_day['optimal'] = $min_remainder;
            } else {
                $yesterday = $result[$day-1];
                $yesterday_optimal = $yesterday['optimal'];

                $optimal_purchase = $yesterday_optimal +
                    $yesterday['data'][$yesterday_optimal]['optimal_purchase'] -
                    $this->need_materials;

                foreach ($for_day['data'] as $remainder => $for_remainder) {
                    if ($remainder == $optimal_purchase) {
                        $for_day['optimal'] = $remainder;
                        break;
                    }

                }

            }

        }

        $this->setResult($result);
    }

}
