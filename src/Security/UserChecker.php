<?php

namespace App\Security;

use App\Entity\User;
use App\Exception\MyNotConfirmedExecption;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if(!$user instanceof User)
        {
            return;
        }

        if($user->getIsConfirmed() === false)
        {
            $execption = new MyNotConfirmedExecption();
            return $execption->getMessageKey();
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        // TODO
    }
}

?>