<?php

namespace App\Services\Products;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

final class ListingImageUploader
{
    private const DIRECTORY = 'product-images';

    // Moves the uploads into public/product-images and returns their relative paths in upload order
    public function store(array $files): array
    {
        $directory = public_path(self::DIRECTORY);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        return array_map(fn (UploadedFile $file) => $this->move($file, $directory), $files);
    }

    // Removes previously stored photos, anything outside the listing images directory is ignored
    public function delete(array $paths): void
    {
        foreach ($paths as $path) {
            if (str_starts_with($path, self::DIRECTORY . '/')) {
                File::delete(public_path($path));
            }
        }
    }

    private function move(UploadedFile $file, string $directory): string
    {
        $filename = time() . '_' . Str::random(10) . '.' . strtolower($file->getClientOriginalExtension());

        $file->move($directory, $filename);

        return self::DIRECTORY . '/' . $filename;
    }
}
