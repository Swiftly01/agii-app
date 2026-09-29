<?php


namespace App\Helpers\Routes;


class RouteHelper
{
    public static function includeRouteFiles(string $folder)
    {
        $files = [];

        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folder, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($it as $file) {
            if ($file->isFile() && $file->isReadable() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }

        // Sorted, so route registration order is the same on every OS / server
        sort($files);

        foreach ($files as $path) {
            require $path;
        }
    }
}
