<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemporaryImageController extends Controller
{
    /**
     * Handle the incoming file upload and save to temporary storage.
     */
    public function upload(Request $request)
    {
        // Try any file that might be in the request
        $files = $request->allFiles();
        if (!empty($files)) {
            $file = reset($files);

            if (is_array($file)) {
                $file = $file[0];
            }

            if ($file) {
                // Check the raw PHP upload error (UPLOAD_ERR_OK = 0).
                // We do NOT use $file->isValid() because FilePond's ImageTransform plugin
                // sends processed Blobs that can have a non-zero error code in older PHP
                // while still being perfectly valid uploads.
                $phpError = $file->getError();
                if ($phpError !== UPLOAD_ERR_OK && $phpError !== UPLOAD_ERR_NO_FILE) {
                    return response()->json([
                        'error' => 'Upload failed (PHP error code ' . $phpError . ')',
                        'hint' => 'If the file is very large the server PHP settings may block it. Increase `upload_max_filesize` and `post_max_size` in php.ini and restart the server.'
                    ], 400);
                }

                if (!$file->getSize()) {
                    return response()->json(['error' => 'Empty file received'], 400);
                }

                $filename = $file->getClientOriginalName();
                $folder = uniqid() . '-' . now()->timestamp;
                $file->storeAs('tmp/' . $folder, $filename);

                // Attempt to compress/resize the uploaded image to reduce storage and ensure compatibility.
                // This runs only if GD functions are available and the uploaded file is a valid image.
                try {
                    // Use Storage::disk('local')->path() so the path matches
                    // where storeAs() actually wrote the file (local disk root = app/private).
                    $path = Storage::disk('local')->path('tmp/' . $folder . '/' . $filename);
                    if (file_exists($path) && function_exists('getimagesize')) {
                        $info = @getimagesize($path);
                        if ($info !== false) {
                            // Supported mime types: image/jpeg, image/png, image/gif, image/webp
                            $mime = $info['mime'] ?? '';
                            $maxWidth = 2000; // keep a reasonable maximum to avoid extremely large images
                            $maxHeight = 2000;
                            [$width, $height] = [$info[0], $info[1]];

                            // Only resize if larger than max
                            $shouldResize = $width > $maxWidth || $height > $maxHeight;

                            $imageData = @file_get_contents($path);
                            $src = @imagecreatefromstring($imageData);
                            if ($src !== false) {
                                $dst = $src;
                                if ($shouldResize) {
                                    $ratio = min($maxWidth / $width, $maxHeight / $height);
                                    $newW = (int) round($width * $ratio);
                                    $newH = (int) round($height * $ratio);
                                    $dst = imagecreatetruecolor($newW, $newH);
                                    // Preserve transparency for PNG/GIF
                                    if (in_array($mime, ['image/png', 'image/gif'])) {
                                        imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
                                        imagealphablending($dst, false);
                                        imagesavealpha($dst, true);
                                    }
                                    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);
                                }

                                // Save back according to mime type
                                if (strpos($mime, 'jpeg') !== false || strpos($mime, 'jpg') !== false) {
                                    imagejpeg($dst, $path, 85);
                                } elseif ($mime === 'image/png') {
                                    // compression level 0 (no) - 9
                                    imagepng($dst, $path, 6);
                                } elseif ($mime === 'image/gif') {
                                    imagegif($dst, $path);
                                } elseif (function_exists('imagewebp') && $mime === 'image/webp') {
                                    imagewebp($dst, $path, 85);
                                }

                                if ($dst !== $src) {
                                    imagedestroy($dst);
                                }
                                imagedestroy($src);
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    // If compression fails, do not block the upload — return success but log if needed.
                    // logger()->warning('Image compression failed: ' . $e->getMessage());
                }

                return response()->json(['id' => $folder]);
            }
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

    /**
     * Revert the uploaded file (delete from temporary storage).
     */
    public function revert(Request $request)
    {
        $folder = $request->getContent(); // FilePond sends the folder ID as body
        Storage::deleteDirectory('tmp/' . $folder);
        return '';
    }
}
