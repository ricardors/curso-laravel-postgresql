<?php

namespace App\Services;

use App\Exceptions\ApiException;
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
    public function create(UserRequest $request): array
    {
        $count = $this->repository->countByUserServiceId(
            $request['service']
        );

        if ($count) {
            $msg = 'Já existe!';
            throw new ApiException($msg, 422);
        }
        $user = $this->repository->create($request->all());
        return $this->formatResponse($user);
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
