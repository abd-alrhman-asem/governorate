<?php

namespace App\Domain\Complaint\Repositories\Complaint;

use App\Domain\Complaint\DataTransferObjects\ComplaintData;
use App\Domain\Complaint\Models\Complaint;

interface ComplaintRepositoryInterface
{
    public  function create(ComplaintData $complaintData): Complaint;
}
