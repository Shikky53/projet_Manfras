<?php

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class MyNotConfirmedExecption extends AccountStatusException
{
    public function getMessageKey()
    {
        throw new AuthenticationException("Votre compte n'est pas encore validé.");
    }
}

?>