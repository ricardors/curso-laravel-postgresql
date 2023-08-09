<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserService
{
    public function __construct(private UserRepository $repository)
    {
    }



    /**
    * Cria um usuario com base nos dados enviados.
    *
    * @param Request $request
    * @return User
    */
    public function create(Request $request): User
    {
        $count = $this->repository->countByEmail(
            $request['email']
        );

        if ($count) {
            throw new ApiException(['message' => 'Already exists!'], 422);
        }
        $user = $this->repository->create($request->all());
        return $user ;
    }


    /**
     * Atualizar.
     *
     * @param string $number
     * @param array $data
     * @return array
     */
    public function update(string $number, array $data): array
    {
        return ['message' => 'Object updated successfully'];
    }

    /**
    * Lista 
    *
    * @param string $number
    * @param Request $request
    * @return array
    */
    public function get(Request $request, string $number): array
    {
        try {
  
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
    * Formata a resposta.
    *
    * @param User $user
    * @return array
    */
    private function formatResponse(User $user): array
    {
        return [
            'number' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at
        ];
    }
}
