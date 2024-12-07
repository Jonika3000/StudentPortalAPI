<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileHelper
{
    public const AVATAR_SIZES = [50, 150, 300, 600];

    public function __construct(private readonly string $uploadDir)
    {
    }

    public function uploadImage(UploadedFile $uploadedFile, string $path, bool $resizeImages): string
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $uploadedFile->guessExtension();
        $systemDir = $this->uploadDir.$path;
        $newFilename = $originalFilename.'-'.uniqid();

        if ($resizeImages) {
            foreach (self::AVATAR_SIZES as $size) {
                $resizedFilename = $newFilename.'-'.$size.'x'.$size.'.'.$extension;
                $resizedPath = $systemDir.$resizedFilename;

                ImageResize::image_resize($size, $size, $resizedPath, $uploadedFile);
            }
        }

        $fullPath = 'uploads/images'.$path.$newFilename.'.'.$extension;
        $uploadedFile->move($systemDir, $newFilename.'.'.$extension);

        return $fullPath;
    }

    public function deleteImage(string $path, bool $resizeImages): void
    {
        $fullPath = $this->uploadDir.$path;

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
