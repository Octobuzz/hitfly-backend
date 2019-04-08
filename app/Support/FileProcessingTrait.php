<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 07.04.19
 * Time: 19:03.
 */

namespace App\Support;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

trait FileProcessingTrait
{
    /**
     * Сохрание файла в папку , возвращает название файла.
     *
     * @param string $file;
     *
     * @return string|null
     */
    public function fileProcessing(?string $file): ?string
    {
        if (empty($file)) {
            return null;
        }
        $baseFile = new File($file);

        $fileName = md5(microtime()).'.'.$baseFile->getExtension();
        $nameFolder = $this->nameFolder;

        if (false === empty($this->user_id)) {
            Storage::putFileAs("public/$nameFolder/".$this->user_id, $baseFile, $fileName);

            return $fileName;
        }

        return null;
    }

    /**
     * Получение url файла.
     *
     * @param $nameAttribute
     *
     * @return string|null
     */
    public function getUrlFile($nameAttribute): ?string
    {
        if (empty($nameAttribute)) {
            return null;
        }

        $userId = $this->user_id;
        $nameFolder = $this->nameFolder;

        return  url('/')."/storage/${nameFolder}/".$userId.DIRECTORY_SEPARATOR.$nameAttribute;
    }
}
