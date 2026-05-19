<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class GoogleDriveService
{
    public function uploadBase64Image(
        string $base64,
        string $folderType = 'attendance',
        string $prefix = 'photo'
    ): string {
        if (!str_contains($base64, ',')) {
            throw new \Exception('Invalid base64 format.');
        }

        [, $data] = explode(',', $base64);
        $binary = base64_decode($data);

        if ($binary === false) {
            throw new \Exception('Failed to decode base64 image.');
        }

        $filename = $prefix . '_' . now()->format('YmdHis') . '_' . uniqid() . '.png';

        return $this->uploadBinary($binary, $filename, $folderType);
    }

    public function uploadBinary(
        string $binary,
        string $filename,
        string $folderType = 'attendance'
    ): string {
        $baseConfig = config('filesystems.disks.google');

        $folderId = $baseConfig['folders'][$folderType] ?? null;

        if (!$folderId) {
            throw new \Exception("Google Drive folder not configured for: {$folderType}");
        }

        // inject selected folder into runtime config
        $runtimeConfig = $baseConfig;
        $runtimeConfig['folder'] = $folderId;

        // create temporary disk with selected folder
        $disk = Storage::build($runtimeConfig);

        $disk->put($filename, $binary);

        return $disk->url($filename);
    }
}
