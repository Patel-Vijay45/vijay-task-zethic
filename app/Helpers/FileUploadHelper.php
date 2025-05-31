<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use ZipArchive;

class FileUploadHelper
{
    public static function uploadFile($file, $module, $filename = null)
    {
        // Generate a unique filename
        if (!$filename) {
            $fileName = time() . '_' . str_replace(' ', '', $file->getClientOriginalName());
        }

        // Get the storage path relative to 'public'
        $path = self::getPath($module);

        // Ensure the directory exists
        self::createDirectoryIfNotExists($path);

        // Store the file and get the relative path
        $filePath = $file->storeAs($path, $fileName, 'public');

        return $filePath;
    }

    public static function getPath($module)
    {
        // Define the paths for different modules
        return match ($module) {
            'categories' => 'categories',
            'products' => 'products',
            default => '',
        };
    }

    public static function createDirectoryIfNotExists($path)
    {
        // Ensure the directory exists using Storage facade
        if (!Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }
    }
}
