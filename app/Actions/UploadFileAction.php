<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadFileAction
{
    /**
     * Upload a file to a given directory on the specified disk with a custom name and timestamp.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $disk
     * @param string|null $customName
     * @return string|bool
     */
    public function execute(UploadedFile $file, string $directory = 'uploads', string $disk = 'public', string $customName = null)
    {
        if ($file->isValid()) {
            // Get the original file extension
            $extension = $file->getClientOriginalExtension();


            // Create a custom filename with a timestamp
            $filename = 'bookings_' . time() . '.' . $extension;

            // Store the file with the custom filename
            $file = $file->storeAs($directory, $filename, $disk);

            if(!empty($file)){
                return $filename;
            }else {
                return '';
            }



        }

        return false;
    }
}
