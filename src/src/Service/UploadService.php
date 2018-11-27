<?php

namespace App\Service;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService
{
    /** @var string */
    protected $webRoot;

    protected $targetDir;

    public function __construct(string $webRoot, string $targetDir)
    {
        $this->webRoot = $webRoot;
        $this->targetDir = $targetDir;
    }

    public function upload(string $dirUpload, UploadedFile $file) :string
    {
        if (null == $file) {
            throw new FileNotFoundException('File for upload not found');
        }

        $fileName = md5(uniqid()).'.'.$file->getClientOriginalName();

        if (!file_exists($this->getUploadRootDir(
            $dirUpload,
            $this->webRoot . '/' . $this->targetDir
        ))) {
            mkdir($this->getUploadRootDir(
                $dirUpload,
                $this->webRoot . '/' . $this->targetDir
            ), 0777, true);
        }

        $file->move($this->getUploadRootDir(
            $dirUpload,
            $this->webRoot . '/' . $this->targetDir
        ), $fileName);

        return $this->targetDir . $dirUpload. '/' . $fileName;
    }

    protected function getUploadRootDir(string $dirUpload, string $basePath = '/') :string
    {
        $path = $basePath . $dirUpload;
        return $path;
    }
}
