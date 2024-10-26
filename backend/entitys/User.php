<?php

namespace Palmo\entitys;

class User
{
    private string $userName;
    private string $email;
    private int $userId;
    private bool $isAdmin;
    private string $password;
    private string $confirmPassword;
    private string $passwordHash;
    private string $role;


    public function __construct(string $userName, string $email)
    {
        $this->userName = $userName;
        $this->email = $email;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }
    public function getPassword(): string {
        return $this->password;
    }

    public function getConfirmPassword(): string
    {
        return $this->confirmPassword;
    }

    public function setPassword(string $password) {
        $this->password = $password;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function setIsAdmin(bool $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
    }

    public function setConfirmPassword(string $confirmPassword): void
    {
        $this->confirmPassword = $confirmPassword;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

}