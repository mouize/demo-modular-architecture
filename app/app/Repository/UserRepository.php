<?php

namespace App\Repository;

use App\Models\User;
use Modules\Authentication\Interfaces\UserInterface;
use Modules\Authentication\Interfaces\UserRepositoryInterface;
use Modules\Blog\Repositories\UserRepository as BlogUserRepository;

class UserRepository extends BlogUserRepository implements UserRepositoryInterface
{
    protected string $model = User::class;

    public function register(array $data): UserInterface
    {
        return User::create($data);
    }

    public function getUserByEmail(string $email): ?UserInterface
    {
        return User::where('email', $email)->first();
    }

    public function findOrFail(int $id): UserInterface
    {
        return User::findOrFail($id);
    }
}
