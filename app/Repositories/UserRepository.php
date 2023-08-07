<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * Cria Request
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Obtem request por id
     *
     * @param integer $id
     * @return User|null
     */
    public function getById(int $id): ?User
    {
        return User::findOrFail($id);
    }

    public function countByUserServiceId(int $serviceId): ?User
    {         
        return User::whereServiceId($serviceId)->count();
    }
    
}
