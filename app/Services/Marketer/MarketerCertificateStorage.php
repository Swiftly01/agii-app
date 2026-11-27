<?php

namespace App\Services\Marketer;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class MarketerCertificateStorage
{
    private const DISK = 'local';

    private const DIRECTORY = 'marketer-certificates';

    // Certificates are personal documents, so they go on the private disk and never into public/
    public function store(UploadedFile $file): string
    {
        return Storage::disk(self::DISK)->putFile(self::DIRECTORY, $file);
    }

    // Removes a stored certificate, a missing file is ignored
    public function delete(?string $path): void
    {
        if ($path) {
            Storage::disk(self::DISK)->delete($path);
        }
    }

    // Streams the certificate to the browser with a readable download name
    public function download(string $path, string $downloadName): StreamedResponse
    {
        return Storage::disk(self::DISK)->download($path, $downloadName . '.' . pathinfo($path, PATHINFO_EXTENSION));
    }

    public function exists(?string $path): bool
    {
        return $path !== null && Storage::disk(self::DISK)->exists($path);
    }
}
