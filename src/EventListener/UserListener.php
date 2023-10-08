<?php

namespace App\EventListener;

use App\Event\AddUserEvent;
use App\Event\TotalUserEvent;

class UserListener
{
    // Brancher la methode dans "config/services.yaml"
    public function onAddUserListener(AddUserEvent $event){
        dd("UserListener => L'utilisateur {$event->getUser()->getEmail()} a été ajouté avec succès !");
    }

    public function onTotalUserListener(TotalUserEvent $event){
        dd("UserListener => Nombre d'utilisateur affiché = {$event->getTotalUser()}");
    }
}