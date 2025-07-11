<?php

namespace App\Domain\Complaint\Services\Complaint;

use App\Domain\Attachment\Services\AttachmentService;
use App\Domain\Complaint\DataTransferObjects\ComplaintData;
use App\Domain\Complaint\Models\Complaint;
use App\Domain\Complaint\Models\ComplaintCategory;
use App\Domain\Complaint\Models\ComplaintType;
use App\Domain\Complaint\Models\destination;
use App\Domain\Complaint\Repositories\Complaint\EloquentComplaintRepository;
use App\Http\Resources\ComplaintConfigResource;


class ComplaintService
{
    public function __construct(
        protected EloquentComplaintRepository $complaintRepository,
        protected AttachmentService           $attachmentService
    )
    {
    }

    public function createComplaint(ComplaintData $complaintData):Complaint
    {
        $complaint = $this->complaintRepository->create($complaintData);
        $this->attachmentService->storeComplaintAttachments(
            $complaintData->attachments,
            $complaint
        );
        return $complaint;
    }

    function getComplaintConfigs(): mixed
    {
        $complaintTypes = ComplaintType::all(['id', 'name']);
        $destinations = Destination::all(['id', 'name']);
        $competentAuthorities = ComplaintCategory::all(['id', 'name']);
        return success(new ComplaintConfigResource([
            'competent_authorities' => $competentAuthorities,
            'destinations' => $destinations,
            'complaint_types' => $complaintTypes,
        ]));
    }
}
