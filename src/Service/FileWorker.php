<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileWorker
{
    public function __construct(private string $targetDir)
    {
    }

    public function upload(UploadedFile $file): string
    {
        $fileName = substr(md5(uniqid('', true)), 0, 4) . '.' . $file->guessExtension();
        $file->move($this->getTargetDir(), $fileName);

        return $fileName;
    }

    public function getTargetDir(): string
    {
        return $this->targetDir;
    }

    public function getFile($imageWorker): File
    {
        return new File($imageWorker);
    }
}
