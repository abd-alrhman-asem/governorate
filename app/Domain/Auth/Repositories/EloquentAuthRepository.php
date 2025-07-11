<?php

namespace App\Domain\Auth\Repositories;
//todo
use App\Domain\Auth\Models\Auth;
use Illuminate\Database\Eloquent\Model;

class EloquentAuthRepository implements AuthRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Auth $model)
    {
        $this->model = $model;
    }
}
