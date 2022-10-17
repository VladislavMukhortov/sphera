<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FileService
{
    /**
     * Сохранить изображение & вернуть имя файла
     *
     * @param UploadedFile $photo
     *
     * @return string
     */
    public function saveImage(UploadedFile $photo): string
    {
        $image = Image::make($photo->getRealPath())->encode('jpg');
        $imageName = Str::uuid() . '.jpg';
        Storage::disk('public')->put($imageName, $image->__toString());

        return $imageName;
    }

    /**
     * Сохранить файл & вернуть имя файла
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    public function saveFile(UploadedFile $file): string
    {
        $fileName = 'document-' . Str::uuid() . '.' . $file->clientExtension();
        Storage::putFileAs('public', $file, $fileName);

        return $fileName;
    }
}
