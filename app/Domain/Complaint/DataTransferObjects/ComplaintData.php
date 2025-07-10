<?php

namespace App\Domain\Complaint\DataTransferObjects;


class ComplaintData
{
    public function __construct(
        public int $userId,
        public int $destinationId,
        public int $complaintCategoryId,
        public ?int $complaintTypeId, // Nullable
        public string $text,
        public string $title,
        public string $locationText,
        public string $locationLat,
        public string $locationLng,
        public ?array  $attachments = []
    )
    {
    }
}
