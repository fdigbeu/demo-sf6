<?php  

namespace App\EventSubscriber;
use App\Event\AddUserEvent;
use App\Event\TotalUserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
                AddUserEvent::ADD_USER_EVENT => ['onAddUserEvent', 100],
                TotalUserEvent::TOTAL_USER_EVENT => ['onTotalUserEvent', 10]
            ];
    }

    public function onAddUserEvent(AddUserEvent $event){
        dd("UserEventSubscriber => L'utilisateur {$event->getUser()->getEmail()} a été ajouté avec succès !");
    }

    public function onTotalUserEvent(TotalUserEvent $event){
        dd("UserEventSubscriber => Nombre d'utilisateur affiché = {$event->getTotalUser()}");
    }
}
