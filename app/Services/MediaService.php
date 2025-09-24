<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class MediaService {
    public function handleUpload(Request $request): ?array
    {

        if (!$request->hasFile('media_file')) {
            return null;
        }

        $file = $request->file('media_file');
        $originalFilename = $file->getClientOriginalName();
        $filename = time() . '_' . pathinfo($originalFilename, PATHINFO_FILENAME) . '.' . $file->getClientOriginalExtension();
        $basePath = 'media/'; // Keep this path clean, the S3 disk handles the root
        $originalPath = $basePath . $filename;

        // Save the original file to S3 first
        $file->storeAs($basePath, $filename, 's3');

        $urls = [
            'original' => Storage::disk('s3')->url($originalPath),
        ];

        // Resize using Intervention Image
        $sizes = [
            'thumbnail' => [150, null],
            'medium' => [600, null],
            'large' => [1200, null],
        ];

        $manager = new ImageManager(new Driver());

        // If the file is a pdf, don't resize
        if ($file->getClientMimeType() === 'application/pdf') {
            $media = Media::create([
                'filename' => $originalFilename,
                'path' => $originalPath,
                'type' => $file->getClientMimeType(),
                'filesize' => $file->getSize(),
                'user_id' => auth()->id(),
            ]);

            return [
                'media' => $media,
                'urls' => $urls,
                'filename' => $filename,
            ];
        }

        // Read the image content from S3 after it's been stored
        // This is a crucial fix to use a saved file instead of the temporary upload
        $imageContent = Storage::disk('s3')->get($originalPath);
        $originalImage = $manager->read($imageContent);

        foreach ($sizes as $sizeName => $dimensions) {
            $resizedImage = clone $originalImage; // Clone the original image for each resize operation
            $resizedImage->scale(width: $dimensions[0]);

            $resizedFilename = pathinfo($filename, PATHINFO_FILENAME) . '_' . $sizeName . '.' . $file->getClientOriginalExtension();
            $resizedPath = $basePath . $resizedFilename;

            // Put the resized image to S3 with the 's3' disk
            Storage::disk('s3')->put($resizedPath, $resizedImage->encode());

            // Add resized image URL
            $urls[$sizeName] = Storage::disk('s3')->url($resizedPath);
        }

        $media = Media::create([
            'filename' => $originalFilename,
            'path' => $originalPath,
            'type' => $file->getClientMimeType(),
            'filesize' => $file->getSize(),
            'user_id' => auth()->id(),
        ]);

        return [
            'media' => $media,
            'urls' => $urls,
            'filename' => $filename,
        ];
    }

    public function appendImagePaths(array $data): array
    {
        // Step 1: Collect all image IDs recursively
        $ids = $this->collectImageIds($data);

        // Step 2: Fetch media in a single query
        $media = \App\Models\Media::whereIn('id', $ids)->get()->keyBy('id');

        // Step 3: Replace IDs with URLs in one recursive pass
        return $this->injectImageUrls($data, $media);
    }

    private function collectImageIds(array $data): array
    {
        $ids = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $ids = array_merge($ids, $this->collectImageIds($value));
            }

            if (is_string($key) && preg_match('/_image_id$/', $key) && !empty($value)) {
                $ids[] = $value;
            }
        }

        return $ids;
    }

    private function injectImageUrls(array $data, $media): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->injectImageUrls($value, $media);
            }

            if (is_string($key) && preg_match('/_image_id$/', $key) && !empty($value)) {
                if (isset($media[$value])) {
                    $urlKey = preg_replace('/_id$/', '_url', $key);
                    $data[$urlKey] = \Storage::url($media[$value]->path);
                }
            }
        }

        return $data;
    }

    public function getMediaUrls(Media $media): array
    {
        // Don't generate resized URLs for PDFs
        if ($media->type === 'application/pdf') {
            return [
                'original' => Storage::disk('s3')->url($media->path),
            ];
        }

        $basePath = pathinfo($media->path, PATHINFO_DIRNAME) . '/';
        $filename = pathinfo($media->path, PATHINFO_FILENAME);
        $extension = pathinfo($media->path, PATHINFO_EXTENSION);

        $urls = [
            'original' => Storage::disk('s3')->url($media->path),
        ];

        $sizes = ['thumbnail', 'medium', 'large'];

        foreach ($sizes as $sizeName) {
            $resizedFilename = $filename . '_' . $sizeName . '.' . $extension;
            $resizedPath = $basePath . $resizedFilename;
            $urls[$sizeName] = Storage::disk('s3')->url($resizedPath);
        }

        return $urls;
    }

}
