<?php

namespace Modules\Authentication\Interfaces;

interface UserRepositoryInterface
{
    public function register(array $data): UserInterface;

    public function getUserByEmail(string $email): ?UserInterface;

    public function findOrFail(int $id): UserInterface;
}
