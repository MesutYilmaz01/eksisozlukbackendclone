<?php

namespace App\Http\Repositories;

use App\Http\RepositoryContracts\IUserRepository;
use App\Models\User;

class UserRepository extends BaseEloquentRepository implements IUserRepository
{
    protected $model = User::class;
}