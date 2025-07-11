<?php

namespace App\Domain\User\Repositories;

interface UserRepositoryInterface
{
    //
    public function create( $data);

    public function findByEmail(string $email);
}
