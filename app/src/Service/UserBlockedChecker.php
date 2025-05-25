<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserBlockedChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if ($user instanceof \App\Entity\User && $user->isBlocked()) {
            throw new CustomUserMessageAccountStatusException('Twoje konto zostało zablokowane.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // Można dodać inne sprawdzenia po uwierzytelnieniu
    }
}
