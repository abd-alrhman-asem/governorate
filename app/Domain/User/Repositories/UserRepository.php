<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository implements UserRepositoryInterface
{
//    /**
//     * @var Model
//     */
//    protected $model;
//
//    /**
//     * BaseRepository constructor.
//     *
//     * @param Model $model
//     */
//    public function __construct(User $model)
//    {
//        $this->model = $model;
//    }
    /**
     * Create a new class instance.
     */
    public function create($data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }

}
