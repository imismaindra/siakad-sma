<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Upload, resize, compress, and convert an image to WebP.
     *
     * @param UploadedFile $file The uploaded file.
     * @param string $directory Storage directory (e.g., 'profiles', 'guru/foto').
     * @param int $maxWidth Maximum width of the image.
     * @param int $maxHeight Maximum height of the image.
     * @param int $quality WebP compression quality (0-100).
     * @return string|null Path to the saved WebP file relative to the disk, or null on failure.
     */
    public static function uploadAndOptimize(
        UploadedFile $file,
        string $directory,
        int $maxWidth = 300,
        int $maxHeight = 300,
        int $quality = 80
    ): ?string {
        // Get temporary path of the uploaded file
        $tempPath = $file->getRealPath();
        
        // Get image info
        $imageInfo = @getimagesize($tempPath);
        if (!$imageInfo) {
            return null;
        }
        
        [$originalWidth, $originalHeight, $imageType] = $imageInfo;
        
        // Create GD image resource based on type
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $sourceImage = @imagecreatefromjpeg($tempPath);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = @imagecreatefrompng($tempPath);
                break;
            case IMAGETYPE_GIF:
                $sourceImage = @imagecreatefromgif($tempPath);
                break;
            case IMAGETYPE_WEBP:
                $sourceImage = @imagecreatefromwebp($tempPath);
                break;
            default:
                // Fallback: store raw if type not supported
                return $file->store($directory, 'public');
        }
        
        if (!$sourceImage) {
            return $file->store($directory, 'public');
        }
        
        // Calculate aspect ratio
        $ratio = $originalWidth / $originalHeight;
        
        if ($originalWidth > $maxWidth || $originalHeight > $maxHeight) {
            if ($maxWidth / $maxHeight > $ratio) {
                $newWidth = $maxHeight * $ratio;
                $newHeight = $maxHeight;
            } else {
                $newWidth = $maxWidth;
                $newHeight = $maxWidth / $ratio;
            }
        } else {
            $newWidth = $originalWidth;
            $newHeight = $originalHeight;
        }
        
        // Create new truecolor image
        $destinationImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Handle transparency for PNG/GIF/WEBP
        if ($imageType === IMAGETYPE_PNG || $imageType === IMAGETYPE_GIF || $imageType === IMAGETYPE_WEBP) {
            imagealphablending($destinationImage, false);
            imagesavealpha($destinationImage, true);
            $transparent = imagecolorallocatealpha($destinationImage, 255, 255, 255, 127);
            imagefilledrectangle($destinationImage, 0, 0, $newWidth, $newHeight, $transparent);
        }
        
        // Resize
        imagecopyresampled(
            $destinationImage,
            $sourceImage,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $originalWidth, $originalHeight
        );
        
        // Generate filename
        $filename = Str::random(40) . '.webp';
        $fullDirectoryPath = storage_path('app/public/' . $directory);
        
        // Ensure directory exists
        if (!file_exists($fullDirectoryPath)) {
            mkdir($fullDirectoryPath, 0755, true);
        }
        
        $outputPath = $fullDirectoryPath . '/' . $filename;
        
        // Save as WebP
        $success = imagewebp($destinationImage, $outputPath, $quality);
        
        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($destinationImage);
        
        if ($success) {
            return $directory . '/' . $filename;
        }
        
        // Fallback to storing original file
        return $file->store($directory, 'public');
    }
}
