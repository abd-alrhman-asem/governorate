<?php

namespace App\Support\PathGenerators;


use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class ComplaintPathGenerator implements PathGenerator
{
    /**
     * الحصول على المسار للملف الأصلي.
     *
     * @param Media $media
     * @return string
     */
    public function getPath(Media $media): string
    {
        $complaintModel = $media->model;
        $complaintId = $complaintModel->id;
        $userId = $complaintModel->user_id;
        return "attachments/{$userId}/{$complaintId}/";
    }
    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media);
    }
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media);
    }
}
