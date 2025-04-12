<?php

namespace Modules\Authentication\Interfaces;

interface UserInterface
{
    public function getId(): int;

    public function getEmail(): string;

    public function getPassword(): string;

    public function createToken(string $name);
}
