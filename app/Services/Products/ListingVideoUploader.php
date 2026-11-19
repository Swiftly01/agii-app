<?php

namespace App\Services\Products;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

final class ListingVideoUploader
{
    private const DIRECTORY = 'product-videos';

    // Moves the uploads into public/product-videos and returns their relative paths in upload order
    public function store(array $files): array
    {
        $directory = public_path(self::DIRECTORY);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        return array_map(fn (UploadedFile $file) => $this->move($file, $directory), $files);
    }

    // Removes previously stored videos, anything outside the videos directory is ignored
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
        // The extension comes from the file content, not the client, so a renamed script can't land in public/
        $filename = time() . '_' . Str::random(10) . '.' . strtolower($file->extension());

        $file->move($directory, $filename);

        return self::DIRECTORY . '/' . $filename;
    }
}
