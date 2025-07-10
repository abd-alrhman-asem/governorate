<?php

namespace App\Domain\Complaint\Repositories\Complaint;

use App\Domain\Complaint\DataTransferObjects\ComplaintData;
use App\Domain\Complaint\Models\Complaint;
use Illuminate\Database\Eloquent\Model;

class EloquentComplaintRepository implements ComplaintRepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * BaseRepository constructor.
     *
     * @param Complaint $model
     */
    public function __construct(Complaint $model)
    {
        $this->model = $model;
    }
    public function create(ComplaintData $complaintData): Complaint
    {
        return  Complaint::create([
            'user_id' => $complaintData->userId,
            'destination_id' => $complaintData->destinationId,
            'category_id' => $complaintData->complaintCategoryId,
            'type_id' => $complaintData->complaintTypeId,
            'title' => $complaintData->title,
            'text' => $complaintData->text,
            'LocationText' => $complaintData->locationText,
            'LocationLat' => $complaintData->locationLat,
            'LocationLng' => $complaintData->locationLng,
        ]);
    }
}
