<?php

namespace Porte22\Csvimporter;


class CsvImporter
{
    private $file;
    private $header;
    private $result;
    private $bomDefinition;
    private $bomSequence;

    public function __construct()
    {
        //$this->file = $file;
    }

    public function getRawFile()
    {
        $this->file->rewind();
        return $this->file->fread($this->file->getSize());
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function countRows()
    {
        return count($this->result);
    }

    public function getBomSequence()
    {
        return $this->bomSequence;
    }

    private function removeBom($text)
    {
        return preg_replace("/^".pack('H*', 'EFBBBF')."/", '', $text);
    }

    public function read(\SplFileObject $file)
    {
        $this->file = $file;
        $reader = new BOMReader($this->file);

        $fileConverted = mb_convert_encoding($this->getRawFile(), "UTF-8", $reader->getEncoding());

        $newFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('.txt');
        file_put_contents($newFile, $this->removeBom($fileConverted));

        $row = 1;
        if (($handle = fopen($newFile, "r")) !== false) {
            $i = 0;
            $this->header = [];
            $this->result = [];
            while (($data = fgetcsv($handle, 1000, "\t")) !== false) {
                if ($i == 0) {
                    $this->header = $data;
                } else {
                    $this->result[] = array_combine($this->header, $data);
                }
                $i++;
            }
            fclose($handle);
        }

        return $this->result;
    }
}