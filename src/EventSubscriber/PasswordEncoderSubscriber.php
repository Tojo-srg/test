<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordEncoderSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * PasswordEncoderSubscriber constructor.
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param ViewEvent $event
     */
    public function encodePassword(ViewEvent $event)
    {
        $value = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($value instanceof User && $method === "POST") {
            $hash = $this->passwordHasher->hashPassword($value, $value->getPassword());
            $value->setPassword($hash);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.view' => ['encodePassword',EventPriorities::PRE_WRITE]
        ];
    }
}
