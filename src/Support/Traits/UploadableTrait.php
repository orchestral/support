<?php namespace Orchestra\Support\Traits;

use Orchestra\Support\Str;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait UploadableTrait
{
    /**
     * Save uploaded file into directory.
     *
     * @param  \Symfony\Component\HttpFoundation\File\UploadedFile  $file
     * @param  string  $path
     *
     * @return string
     */
    protected function saveUploadedFile(UploadedFile $file, $path)
    {
        $file->move($path, $filename = $this->getUploadedFilename($file));

        return $filename;
    }

    /**
     * Delete uploaded from directory.
     *
     * @param  string  $file
     *
     * @return bool
     */
    protected function deleteUploadedFile($file)
    {
        return File::delete($file);
    }

    /**
     * Get uploaded filename.
     *
     * @param  \Symfony\Component\HttpFoundation\File\UploadedFile  $file
     *
     * @return string
     */
    protected function getUploadedFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();

        return sprintf('%s.%s', Str::random(10), $extension);
    }
}
