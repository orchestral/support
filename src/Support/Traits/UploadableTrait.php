<?php namespace Orchestra\Support\Traits;

use Orchestra\Support\Str;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait UploadableTrait {

    /**
     * Save uploaded file into directory
     *
     * @param  use Symfony\Component\HttpFoundation\File\UploadedFile
     * @param  string $path
     * @return string
     */
    protected function save(UploadedFile $file, $path)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = Str::random(10).".{$extension}";

        $file->move($path, $filename);

        return $filename;
    }

    /**
     * Delete uploaded from directory
     * 
     * @param  string $file
     * @return bool
     */
    protected function delete($file)
    {
        return File::delete($file);
    }

}
