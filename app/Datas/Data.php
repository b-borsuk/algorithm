<?php

namespace App\Datas;

use Illuminate\Http\UploadedFile;
use Exception;

/**
*
*/
abstract class Data
{
    protected $file = null;

    public $file_data = null;

    public $incorrectly = false;

    protected $errors = [];

    protected $errors_messages = [];

    protected $csv = [];

    protected $result = [];

    public function getFileData()
    {
        if (!is_null($this->file_data)) {
            return $this->file_data;
        }

        $file = $this->getFile();

        if (is_null($file)) {
            $this->addError(new Exception("File is not added"), 'not_file');
            return;
        }

        $file->setCsvControl($this->getCsvConf('delimiter'), $this->getCsvConf('enclosure'));

        $data = [];
        while (!$file->eof()) {
            $row_data = $file->fgetcsv();

            if (!empty($row_data[0]) && count($row_data) > 0) {
                $data[] = $row_data;
            }
        }

        $this->file_data = $this->normaliseFileData($data);

        return $this->file_data;
    }

    public function normaliseFileData(array $data)
    {
        return $data;
    }

    public function addFile($file)
    {
        if ($file instanceof UploadedFile) {
            try {
                $file = $file->openFile();

            } catch (Exception $e) {
                $this->addError($e);
                return;
            }

            $this->file = $file;
        } elseif ($file instanceof \SplFileObject) {
            $this->file = $file;
        } else {
            $this->file = null;
            $this->addError(new Exception("File is incorrectly type"), 'file_type');
        }
    }

    public function getFile()
    {
        return $this->file;
    }


    public function getCsvConf($name = null, $default = null)
    {
        $default_csv = [
            'delimiter' => " ",
            'enclosure' => "\n",
        ];

        $config = $this->csv;
        $config = array_merge($default_csv, $config);

        if (is_null($name)) {
            return $config;
        }

        if(array_key_exists($name, $config)) {
            return $config[$name];
        } else {
            return $default;
        }
    }


    public function addError(Exception $e, $key = null, $throwing = false)
    {
        $this->incorrectly = true;
        $this->errors[] = $e;

        if (!is_null($key)) {
            $this->errors_messages[$key] = $e->getMessage();
        } else {
            $this->errors_messages[] = $e->getMessage();
        }

        if ($throwing) {
            throw $e;
        }
    }

    public function hasErrors()
    {
        return $this->incorrectly;
    }

    public function calculate()
    {
        if ($this->incorrectly) {
            $this->addError(new Exception("Can`t calculate."), 'calculate', true);
            return;
        }

        $result = $this->problemFunction();

        $this->setResult($result);

        return $result;
    }

    protected function setResult($result)
    {
        $this->result = $result;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getErrorsMessages()
    {
        return $this->errors_messages;
    }

    public function setArrayKey(array $values, array $keys)
    {
        if (count($keys) < count($values)) {
            $this->addError(new Exception("Not enough keys in the File Data"), 'bad_keys');
            return $values;
        }

        $values = array_values($values);
        $keys = array_values($keys);

        $array = [];
        foreach ($values as $counter => $value) {
            $array[$keys[$counter]] = $value;
        }

        return $array;
    }

}
