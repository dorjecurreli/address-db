<?php
namespace App\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class UserVerifiedSubscriber implements EventSubscriberInterface
{

    private $mailer;


    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;

    }

    /**
     * @return mixed[]
     */
    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
        return [
            UserVerifiedEvent::NAME => 'onUserVerified'
        ];
    }


    public function onUserVerified(UserVerifiedEvent $userVerifiedEvent)
    {
        //TODO: Send email with autogenerated password to the verified user onUserVerified event

        $tmpPassword = $userVerifiedEvent->getTmpPassword();

        //dd($tmpPassword);


        $email = (new Email())
            ->from('afterverification@test.test')
            ->to('dor.curreli@gmail.com')
            ->subject('BELLLLAAAAAAAAAAAAAAA')
            ->text('Your Temporary password: ' . $tmpPassword);

        $this->mailer->send($email);

        dd($email);

    }
}
