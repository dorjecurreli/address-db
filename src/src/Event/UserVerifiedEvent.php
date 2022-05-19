<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Contracts\EventDispatcher\Event;


class UserVerifiedEvent extends Event
{

    const NAME = "user.verified";

    private $user;


    public function __construct(User $user)
    {
        $this->user = $user;

        $pippo = new Container();
    }

    public function getTmpPassword()
    {
        return $this->user->getTmpPassword();

    }

}