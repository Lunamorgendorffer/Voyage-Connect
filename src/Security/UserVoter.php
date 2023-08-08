<?php

namespace App\Service;


use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class userVoter extends Voter
{
    protected function supports($attribute, $subject): bool
    {
        //this Voter is always available
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;

        } elseif ($user->isIsBanned() === false) {
            // the user is not enabled; deny access
            return false;
        }
        //otherwise return true
        return true;
    }
}

