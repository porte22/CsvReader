<?php
/**
 * Created by PhpStorm.
 * User: Edimotive
 * Date: 18/05/2018
 * Time: 11:46
 */

namespace Porte22\Csvimporter;


define('UTF32_BIG_ENDIAN_BOM', chr(0x00) . chr(0x00) . chr(0xFE) . chr(0xFF));
define('UTF32_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE) . chr(0x00) . chr(0x00));
define('UTF16_BIG_ENDIAN_BOM', chr(0xFE) . chr(0xFF));
define('UTF16_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE));
define('UTF8_BOM', chr(0xEF) . chr(0xBB) . chr(0xBF));

class BOMReader
{
    private $fileContent;

    public function __construct(\SplFileObject $file)
    {
        $file->seek(0);
        $this->fileContent = $file->fread($file->getSize());
    }

    function getEncoding()
    {
        $first2 = substr($this->fileContent, 0, 2);
        $first3 = substr($this->fileContent, 0, 3);
        $first4 = substr($this->fileContent, 0, 3);

        if ($first3 == UTF8_BOM) return 'UTF-8';
        elseif ($first4 == UTF32_BIG_ENDIAN_BOM) return 'UTF-32BE';
        elseif ($first4 == UTF32_LITTLE_ENDIAN_BOM) return 'UTF-32LE';
        elseif ($first2 == UTF16_BIG_ENDIAN_BOM) return 'UTF-16BE';
        elseif ($first2 == UTF16_LITTLE_ENDIAN_BOM) return 'UTF-16LE';
    }
}