<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait UploadImageTrait
{
    public function uploadImage(Request $request, string $folder, string $fieldname = 'image'): ?string
    {
        if (! $request->hasFile($fieldname)) {
            return null;
        }

        $image = $request->file($fieldname);

        if ($image instanceof UploadedFile && $image->isValid()) {
            return $this->storeUploadedFile($image, $folder);
        }

        return null;
    }

    public function storeUploadedFile(UploadedFile $image, string $folder): ?string
    {
        if (! $image->isValid()) {
            return null;
        }

        $extension = $image->getClientOriginalExtension();
        $imageName = Str::uuid() . '.' . $extension;

        return $image->storeAs($folder, $imageName, 'public');
    }
}
