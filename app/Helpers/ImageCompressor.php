<?php

namespace App\Helpers;

use Intervention\Image\Facades\Image;

class ImageCompressor
{
    public static function compressAndSave($sourcePath, $destinationPath, $quality = 90)
    {
        // Open an image file
        $img = Image::make($sourcePath);

        // Compress the image with the specified quality
        $img->save($destinationPath, $quality);

        // Return the path to the compressed image
        return $destinationPath;
    }
}
