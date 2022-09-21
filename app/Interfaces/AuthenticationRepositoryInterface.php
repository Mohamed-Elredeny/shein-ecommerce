<?php

namespace App\Interfaces;

interface AuthenticationRepositoryInterface
{
    public function login(array $recordDetails);
    public function loginSocial(array $recordDetails);
    public function register(array $recordDetails);
    public function logout($token);
    public function viewProfile($token);
    public function updateProfile($token,array $recordDetails);
}
