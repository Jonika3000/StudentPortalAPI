<?php

namespace App\Support\Helper;

use App\Support\Utils\ImageResize;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileHelper
{
    public const AVATAR_SIZES = [50, 150, 300, 600];

    public function __construct(private readonly string $publicDir)
    {
    }

    public function uploadFile(UploadedFile $uploadedFile, string $path, bool $resizeImages): string
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $uploadedFile->guessExtension();
        $systemDir = $this->publicDir.'uploads'.$path;
        $newFilename = $originalFilename.'-'.uniqid();

        if ($resizeImages) {
            foreach (self::AVATAR_SIZES as $size) {
                $resizedFilename = $newFilename.'-'.$size.'x'.$size.'.'.$extension;
                $resizedPath = $systemDir.$resizedFilename;

                ImageResize::image_resize($size, $size, $resizedPath, $uploadedFile);
            }
        }

        $fullPath = 'uploads'.$path.$newFilename.'.'.$extension;
        $uploadedFile->move($systemDir, $newFilename.'.'.$extension);

        return $fullPath;
    }

    public function deleteFile(string $path, bool $resizeImages): void
    {
        $fullPath = $this->publicDir.$path;
        $fileInfo = pathinfo($fullPath);
        $directory = $fileInfo['dirname'];
        $filename = $fileInfo['filename'];
        $extension = $fileInfo['extension'];
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        if ($resizeImages) {
            foreach (self::AVATAR_SIZES as $size) {
                $resizedPath = $directory.DIRECTORY_SEPARATOR.$filename.'-'.$size.'x'.$size.'.'.$extension;
                if (file_exists($resizedPath)) {
                    unlink($resizedPath);
                }
            }
        }
    }
}
