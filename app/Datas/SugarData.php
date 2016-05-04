<?php

namespace App\Datas;

use App\Datas\Data;
use Illuminate\Http\UploadedFile;


/**
*
*/
class SugarData extends Data
{
    protected $file = [];

    public $doses = [];

    public $data = [];

    function __construct(UploadedFile $file)
    {
        $this->file = $file;

        $data = self::getFileData($file);

        self::setData($data);
    }

    public function setData(array $data)
    {
        $doses = [];
        $norm_data = [];

        foreach ($data as $value) {
            $first = head($value);

            $doses[] = $first;

            array_forget($value, 0);

            $norm_data[$first] = array_values($value);
        }

        $this->doses = array_sort_recursive(array_unique($doses));
        $this->data = array_sort_recursive($norm_data);
    }

}
