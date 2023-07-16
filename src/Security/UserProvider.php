<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class UserProvider
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUser($phone)
    {
        if (!$user = $this->userRepository->findOneBy(['phone' => $phone])) {
            throw new UserNotFoundException();
        }
        return $user;
    }
}