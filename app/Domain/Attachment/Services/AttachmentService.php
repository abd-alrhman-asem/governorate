<?php

namespace App\Domain\Attachment\Services;

use App\Domain\Complaint\Models\Complaint;

class AttachmentService
{
    public function __construct(
    )
    {
    }

    public function storeComplaintAttachments($files, $complaint)
    {
        foreach ($files as $attachmentFile) {
            $complaint->addMedia($attachmentFile)
                ->toMediaCollection(
                    'attachments',
                    'complaint_disk'
                );
        }
    }
}
