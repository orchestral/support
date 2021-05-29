<?php

namespace Orchestra\Support\Concerns;

use Illuminate\Support\Facades\File;
use Orchestra\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait Uploadable
{
    /**
     * Save uploaded file into directory.
     *
     * @param  \Symfony\Component\HttpFoundation\File\UploadedFile  $file
     * @param  string  $path
     *
     * @return string
     */
    protected function saveUploadedFile(UploadedFile $file, string $path): string
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
    protected function deleteUploadedFile(string $file): bool
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
    protected function getUploadedFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();

        return sprintf('%s.%s', Str::random(10), $extension);
    }
}
