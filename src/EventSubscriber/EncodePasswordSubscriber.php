<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\EventSubscriber;

use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\Request\RequestEvent;
use KejawenLab\Semart\Skeleton\Security\Service\PasswordEncoderService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class EncodePasswordSubscriber implements EventSubscriberInterface
{
    private $passwordEncoder;

    public function __construct(PasswordEncoderService $encodePasswordService)
    {
        $this->passwordEncoder = $encodePasswordService;
    }

    public function setPassword(RequestEvent $event)
    {
        $user = $event->getObject();
        if (!$user instanceof User) {
            return;
        }

        $request = $event->getRequest();
        $user->setPlainPassword($request->request->get('plainPassword'));
        $request->request->remove('plainPassword');

        $this->passwordEncoder->encode($user);
    }

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => 'setPassword',
        ];
    }
}
