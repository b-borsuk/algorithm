<?php

namespace App\Datas;

use App\Datas\Data;
use Illuminate\Http\UploadedFile;
use Exception;

/**
*
*/
class BiosystemsData extends Data
{
    protected $data = [];

    protected $main_values = ['delta'];

    public $delta;

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

        foreach ($main_values as $value) {
            if (!array_key_exists($value, $initial_data)) {
                $this->addError(new Exception("Main Data incomplete"), 'main_data');
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

        $this->setAdditionalData($file_data);
    }

    public function setMainData(array $data)
    {
        $this->delta = $data['delta'];
    }

    public function setAdditionalData(array $data)
    {
        $result = [];

        foreach ($data as $key => $value) {
            $result[$key]['initial_data'] = $value;
            $result[$key]['data'] = self::groupValueByDelta($value);
            $result[$key]['N'] = count($value);
            $result[$key]['n'] = count($result[$key]['data']);

            $result[$key]['Hm'] = log10($result[$key]['n']);
            $result[$key]['H'] = 0;

            foreach ($result[$key]['data'] as &$v) {
                $v['p'] = count($v['data']) / $result[$key]['N'];
                $result[$key]['H'] += $v['p'] * log10($v['p']);
            }

            $result[$key]['H'] = - $result[$key]['H'];

            $result[$key]['R'] = 1 - $result[$key]['H'] / $result[$key]['Hm'];
        }

        $this->data = $result;
    }

    public function groupValueByDelta(array $value)
    {
        $delta = $this->delta;
        $min_value = $value[0];

        $result = [];

        foreach ($value as $v) {
            $key = ($v - $min_value) / $delta;
            $result[$key]['data'][] = $v;
        }

        return $result;
    }

    public function normaliseFileData(array $data)
    {
        foreach ($data as &$value) {
            sort($value);
        }

        return $data;
    }

    public function getResult()
    {
        return $this->data;
    }

    public function getJsonData($number=null)
    {
        $result = [];

        if (is_null($number)) {
            foreach ($this->data as $key => $value) {
                $result[] = [$value['R'], $value['Hm']];
            }

        } else {
            $data = $this->data[$number];

            $result[] = [$data['R'], $data['Hm']];
        }

        return json_encode($result);
    }

    public function getSystemType()
    {
        $group = [
            0 => 0,
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0,
            7 => 0,
            8 => 0,
        ];

        foreach ($this->data as $key => $value) {
            switch (true) {
                case $value['Hm'] > 0 && $value['Hm'] <= 3 && $value['R'] > 0 && $value['R'] <= 0.1:
                    $group[0]++;
                    break;
                case $value['Hm'] > 0 && $value['Hm'] <= 3 && $value['R'] > 0.1 && $value['R'] <= 0.3:
                    $group[1]++;
                    break;
                case $value['Hm'] > 0 && $value['Hm'] <= 3 && $value['R'] > 0.3 && $value['R'] <= 1:
                    $group[2]++;
                    break;

                case $value['Hm'] > 3 && $value['Hm'] <= 6 && $value['R'] > 0 && $value['R'] <= 0.1:
                    $group[3]++;
                    break;
                case $value['Hm'] > 3 && $value['Hm'] <= 6 && $value['R'] > 0.1 && $value['R'] <= 0.3:
                    $group[4]++;
                    break;
                case $value['Hm'] > 3 && $value['Hm'] <= 6 && $value['R'] > 0.3 && $value['R'] <= 1:
                    $group[5]++;
                    break;

                case $value['Hm'] > 6 && $value['R'] > 0 && $value['R'] <= 0.1:
                    $group[6]++;
                    break;
                case $value['Hm'] > 6 && $value['R'] > 0.1 && $value['R'] <= 0.3:
                    $group[7]++;
                    break;
                case $value['Hm'] > 6 && $value['R'] > 0.3 && $value['R'] <= 1:
                    $group[8]++;
                    break;
            }
        }
        $max_key = 0;
        $max = 0;

        foreach ($group as $key => $value) {
            if ($max < $value) {
                $max_key = $key;
                $max = $value;
            }
        }

        switch ($max_key) {
            case 0:
                return 'Система проста ймовірнісна.';

            case 1:
                return 'Система проста квазідетермінована.';

            case 2:
                return 'Система проста детермінована.';

            case 3:
                return 'Система складна ймовірнісна.';

            case 4:
                return 'Система складна квазідетермінована.';

            case 5:
                return 'Система складна детермінована.';

            case 6:
                return 'Система складна ймовірнісна.';

            case 7:
                return 'Система складна квазідетермінована.';

            case 8:
                return 'Система складна детермінована.';
        }

        return '';
    }

    public function getText($number)
    {
        $result = 'Система ';

        $data = $this->data[$number];

        if ($data['Hm'] > 6) {
            $result .= 'дуже складна ';
        } else if ($data['Hm'] > 0 && $data['Hm'] <= 3) {
            $result .= 'проста ';
        } else {
            $result .= 'складна ';
        }

        if ($data['R'] > 0 && $data['R'] <= 0.1) {
            $result .= 'ймовірнісна.';
        } else if ($data['R'] > 0.3 && $data['R'] <= 1) {
            $result .= 'детермінована.';
        } else if ($data['R'] > 0.1 && $data['R'] <= 0.3) {
            $result .= 'квазідетермінована.';
        }

        return $result;
    }
}
