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
            $max = $max < max($sugar['data']) ? max($sugar['data']) : $max ;
        }

        if (is_null($max)) {
            return $this->addError(new Exeption("Max is null"), 'max');
        }

        $this->max = $max + 1;
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
        $y_max =$this->max;

        return $y_max * (1 - exp(- $L * $doza));
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
}
