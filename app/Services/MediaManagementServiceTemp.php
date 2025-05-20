<?php

namespace App\Services;

use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaManagementServiceTemp
{
    public static function uploadMedia($file, $path, $disk, $fileName)
    {
        try {
            // If $file is an array, turn it into a TemporaryUploadedFile
            if (is_array($file)) {
                $file = TemporaryUploadedFile::createFromLivewire($file);
            }
            dd($file);

            // Ensure it's a TemporaryUploadedFile or UploadedFile
            if ($file instanceof \Illuminate\Http\UploadedFile) {
                return Storage::disk($disk)->putFileAs($path, $file, $fileName);
            }

            throw new \Exception("Invalid file upload object.");
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public static function removeMedia($path)
    {
        // This assumes the file is stored under 'public' disk
        $fullPath = storage_path('app/public/' . $path);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    public static function mediaExist($path): bool
    {
        return $path ? Storage::exists($path) : false;
    }

    public static function checkDeleteUpload($imagePath, $file, $path, $disk, $fileName)
    {
        if (self::mediaExist($imagePath)) {
            self::removeMedia($imagePath);
        }

        return self::uploadMedia($file, $path, $disk, $fileName);
    }
}
