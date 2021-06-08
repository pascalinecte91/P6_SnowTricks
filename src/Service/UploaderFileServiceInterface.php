<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface UploaderFileServiceInterface
 * @package App\Service
 */
interface UploaderFileServiceInterface
{
    /**
     * @param UploadedFile $file
     */
    public function upload(UploadedFile $file): string;

    /**
     * @return string
     */
    public function getTargetDirectory(): string;
}
