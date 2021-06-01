<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class UploaderFileService
 * @package App\Service
 */
class UploaderFileService implements UploaderFileServiceInterface
{
    private string $targetDirectory;
    private SluggerInterface $slugger;
    private LoggerInterface $logger;

    /**
     * UploaderFileService constructor.
     * @param string $targetDirectory
     * @param SluggerInterface $slugger
     * @param LoggerInterface $logger
     */
    public function __construct(string $targetDirectory, SluggerInterface $slugger, LoggerInterface $logger)
    {
                              $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
                             $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function upload(UploadedFile $file): string
    {
        $filename = null;

        try {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $filename = $safeFilename.'-'.uniqid('', true).'.'.$file->guessExtension();

            $file->move($this->getTargetDirectory(), $filename);
        } catch (FileException $exception) {
            $this->logger->error('Une erreur s\'est produite pendant le téléchargement.', [
                'exception' => $exception,
            ]);
        }

        return $filename;
    }

    /**
     * @inheritDoc
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}