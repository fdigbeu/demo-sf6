<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class TotalUserEvent extends Event
{
    // Il faut injecter "private EventDispatcherInterface" dans le constructeur du controller Registration{}
    // Cela permettra de l'utiliser dans toutes les méthodes du controller
    const TOTAL_USER_EVENT = "users.count";

    public function __construct(private $users){}

    public function getTotalUser(): int{
        return count($this->users);
    }
}