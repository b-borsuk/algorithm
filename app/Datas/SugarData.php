<?php

namespace App\Datas;

use App\Datas\Data;
use Illuminate\Http\UploadedFile;

use Exeption;

/**
*
*/
class SugarData extends Data
{
    public $doses = [];

    public $data = [];

    public $max;

    public $minL;

    public $minLPow;

    public $minL2;

    public $minL2Pow;

    function __construct(UploadedFile $file)
    {
        $this->addFile($file);
        $this->data = $this->getFileData();

        self::calcMat();
        self::setMaxY();
        self::calcL();
        self::calcSumPow();
        self::setMinL();
        self::setMinL2();
    }

    public function normaliseFileData(array $data)
    {
        $doses = [];
        $norm_data = [];

        foreach ($data as $value) {
            $first = head($value);

            $doses[] = $first;

            array_forget($value, 0);

            $norm_data[$first]['data'] = array_values($value);
        }

        $this->doses = array_sort_recursive(array_unique($doses));

        return array_sort_recursive($norm_data);
    }

    public function calcMat()
    {
        foreach ($this->data as &$sugar) {
            $sugar['mat'] = array_sum($sugar['data'])/count($sugar['data']);
        }
    }

    public function calcL()
    {
        $max = $this->max;

        foreach ($this->data as $key => &$sugar) {
            $x = $key;
            $sugar['L'] = (- 1/ $x) * log(1 - $sugar['mat']/$max);
        }
    }

    public function calcSumPow()
    {
        foreach ($this->data as $key => &$sugar) {
            $x = $key;
            $sugar['pow'] = self::sumPow($sugar['L']);
        }
    }

    public function setMaxY()
    {
        $data = $this->data;

        $max = null;

        foreach ($data as $sugar) {
            $max = $max < $sugar['mat'] ? $sugar['mat'] : $max;
        }

        if (is_null($max)) {
            return $this->addError(new Exeption("Max is null"), 'max');
        }

        $this->max = $max + 5;
    }

    public function sumPow($L)
    {
        $data = $this->data;

        $sum = 0;

        foreach ($data as $key => $sugar) {
            $sum += pow((self::funY($key, $L) - $sugar['mat']), 2);
        }

        return $sum;
    }

    public function funY($doza, $L)
    {
        $y_max = $this->max;

        return $y_max * (1 - exp(-$L * $doza));
    }

    public function setMinL()
    {
        $data = $this->data;

        $minL = null;
        $minLPow = null;

        foreach ($data as $key => $sugar) {
            if (is_null($minLPow) || $minLPow > $sugar['pow']) {
                $minL = $sugar['L'];
                $minLPow = $sugar['pow'];
            }
        }

        $this->minL = $minL;
        $this->minLPow = $minLPow;
    }

    public function setMinL2()
    {
        $data = $this->data;

        $minL2 = 0;
        $minL2Pow = 0;

        $avgL = 0;
        $count_suga = count($data);

        foreach ($data as $key => $sugar) {
            $avgL += $sugar['L'] / $count_suga;
        }

        $this->minL2 = $avgL;

        $this->minL2Pow = self::sumPow($avgL);
    }

    public function getJsonPoints()
    {
        $result = [];

        $max = $this->max;

        foreach ($this->data as $doses => $value) {
            $result[] = [$doses, $value['mat'], $max];
        }

        return json_encode($result);
    }

    public function getJsonResult()
    {
        $data = $this->data;
        $doses = array_keys($data);

        $min_doses = min($doses);
        $max_doses = max($doses);

        $range = 1;

        $result = [];
        $doses_row = ['dose'];

        $y_max = $this->max;
        $minL  = $this->minL;
        $minL2 = $this->minL2;

        foreach ($data as $dose => $value) {
            $doses_row[] = 'Dose ' . $dose . ': ' . $y_max . '*(1-e^(-' . $value['L'] . 'x))';
        }

        $minLPow  = $this->minLPow;
        $minL2Pow = $this->minL2Pow;
        $doses_row[] = 'L1 Pow ' . $minLPow . ': ' . $y_max . '*(1-e^(-' . $minL . 'x))';
        $doses_row[] = 'L2 Pow ' . $minL2Pow . ': ' . $y_max . '*(1-e^(-' . $minL2 . 'x))';

        $doses_row[] = 'MAX';



        $result[] = $doses_row;
        for ($dose = $min_doses; $dose <= $max_doses; $dose += $range) {
            $row = [$dose];
            foreach ($data as $dose_val => $value) {
                $row[] = $this->funY($dose, $value['L']);
            }

            $row[] = $this->funY($dose, $minL);
            $row[] = $this->funY($dose, $minL2);
            $row[] = $this->funY($dose, $y_max);

            $result[] = $row;
        }

        return json_encode($result);
    }

    public function getMaxH()
    {
        return max($this->doses) + min($this->doses);
    }

    public function getOptimalLineNumber()
    {
        $doses_line_count = count($this->data);

        return $this->minLPow < $this->minL2Pow ? $doses_line_count + 1 : $doses_line_count + 2;
    }
}
