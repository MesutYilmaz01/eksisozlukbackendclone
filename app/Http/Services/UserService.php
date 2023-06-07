<?php

namespace App\Http\Services;

use App\Http\RepositoryContracts\IUserRepository;
use App\Http\ServiceContracts\IUserService;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserService implements IUserService
{
    public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store(array $data): User 
    {
        $data['password'] = Hash::make($data['password']);
        return $this->repository->store($data);
    }

    public function changePassword(array $data)
    {
            if(!Hash::check($data['old_password'], auth()->user()->password)) {
                throw new Exception('Old password is not correct', 400);
            }

            $data['password'] = Hash::make($data['new_password']);
            unset($data['old_password'], $data['new_password']);

            $isUpdated = $this->repository->update($data);

            if(!$isUpdated) {
                throw new Exception('An error occured.', 400);
            }

            return $isUpdated;
    }
}