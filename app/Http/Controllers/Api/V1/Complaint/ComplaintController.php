<?php

namespace App\Http\Controllers\Api\V1\Complaint;

use App\Http\Controllers\Controller;
use App\Http\Requests\Complaint\SearchComplaintRequest;
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
        $this->complaintService->createComplaint($request->toDto());
        return success(msg: 'complaint stored successfully', statusCode: ResponseAlias::HTTP_CREATED);
    }

    public function getConfigs(): JsonResponse
    {
        return success(
            data: $this->complaintService->getComplaintConfigs(),
        );
    }

    public  function search(SearchComplaintRequest $request)
    {
        $this->complaintService->searchComplaint($request->validated());
    }

}
