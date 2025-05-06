<?php

namespace App\Helpers;

use Exception;

class ImageHelper
{
    /**
     * Upload dan resize gambar.
     *
     * @param \Illuminate\Http\UploadedFile $file File yang diunggah
     * @param string $directory Direktori tujuan penyimpanan
     * @param string $fileName Nama file yang akan disimpan
     * @param int|null $width Lebar gambar baru (opsional)
     * @param int|null $height Tinggi gambar baru (opsional)
     * @return string Nama file yang disimpan
     * @throws Exception Jika jenis file tidak didukung
     */
    public static function uploadAndResize($file, $directory, $fileName, $width = null, $height = null)
    {
        // Tentukan jalur direktori tujuan
        $destinationPath = public_path($directory);

        // Pastikan direktori tujuan ada
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0755, true); // Buat direktori jika belum ada
        }

        // Dapatkan ekstensi file dan validasi
        $extension = strtolower($file->getClientOriginalExtension());
        $image = null;

        // Buat gambar berdasarkan ekstensi
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($file->getRealPath());
                break;
            case 'png':
                $image = imagecreatefrompng($file->getRealPath());
                break;
            case 'gif':
                $image = imagecreatefromgif($file->getRealPath());
                break;
            default:
                throw new Exception('Jenis file gambar tidak didukung.');
        }

        // Resize gambar jika diperlukan
        if ($width) {
            $oldWidth = imagesx($image);
            $oldHeight = imagesy($image);
            $aspectRatio = $oldWidth / $oldHeight;

            if (!$height) {
                $height = $width / $aspectRatio; // Hitung tinggi dengan mempertahankan rasio
            }

            $newImage = imagecreatetruecolor($width, $height);
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $oldWidth, $oldHeight);
            imagedestroy($image);
            $image = $newImage;
        }

        // Simpan gambar ke direktori tujuan
        $filePath = $destinationPath . DIRECTORY_SEPARATOR . $fileName;
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($image, $filePath);
                break;
            case 'png':
                imagepng($image, $filePath);
                break;
            case 'gif':
                imagegif($image, $filePath);
                break;
        }

        // Hapus sumber daya gambar
        imagedestroy($image);

        // Kembalikan nama file
        return $fileName;
    }
}
