<?php

namespace App\Http\Controllers\Api\V1\Complaint;

use App\Http\Controllers\Controller;
use App\Http\Requests\Complaint\StoreComplaintRequest;
use App\Domain\Complaint\Services\Complaint\ComplaintService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class ComplaintController extends Controller
{
    public function __construct(
        protected ComplaintService $complaintService,
    )
    {
    }

    /**
     * @throws Throwable
     */
    public function store(StoreComplaintRequest $request)
    {
        $complaint = $this->complaintService->createComplaint($request->toDto());
//        if ($request->hasFile('attachments')) {
//            $complaint->addMultipleMediaFromRequest(['attachments'])
//                ->each(function ($fileAdder) {
//                    $fileAdder->toMediaCollection(
//                        'attachments',
//                        'complaint_disk'
//                    );
//                });
//        }
        return success(msg: 'complaint stored successfully', statusCode: ResponseAlias::HTTP_CREATED);
    }

    public function getConfigs(): JsonResponse
    {
        return success(
            data: $this->complaintService->getComplaintConfigs(),
        );
    }

}
