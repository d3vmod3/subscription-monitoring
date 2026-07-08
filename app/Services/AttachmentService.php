<?php

namespace App\Services;

use App\Models\Attachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttachmentService
{
    /**
     * Upload a file and save its metadata.
     */
    public function upload(
        UploadedFile $file,
        string $module,
        int $moduleId,
        string $directory = 'attachments'
    ): Attachment {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $path = Storage::disk('s3')->putFileAs(
            $directory,
            $file,
            $filename
        );

        return Attachment::create([
            'module' => $module,
            'module_id' => $moduleId,
            'disk' => 's3',
            'path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => Auth::id(),
        ]);
    }

    /**
     * Delete a file and its database record.
     */
    public function delete(Attachment $attachment): bool
    {
        if (Storage::disk($attachment->disk)->exists($attachment->path)) {
            Storage::disk($attachment->disk)->delete($attachment->path);
        }

        return $attachment->delete();
    }

    /**
     * Generate a temporary URL for private files.
     */
    public function temporaryUrl(
        Attachment $attachment,
        int $minutes = 10
    ): string {
        return Storage::disk($attachment->disk)->temporaryUrl(
            $attachment->path,
            now()->addMinutes($minutes)
        );
    }

    /**
     * Generate a public URL.
     */
    public function url(Attachment $attachment): string
    {
        return Storage::disk($attachment->disk)->url(
            $attachment->path
        );
    }
}