<?php

namespace App\Mail;

use SendGrid;
use SendGrid\Mail\Mail;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class SendGridTransport extends AbstractTransport
{
    protected $apiKey;

    public function __construct(string $apiKey)
    {
        parent::__construct();
        $this->apiKey = $apiKey;
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $sendgrid = new SendGrid($this->apiKey);
        $mail = new Mail();

        $from = $email->getFrom()[0];
        $mail->setFrom($from->getAddress(), $from->getName());

        foreach ($email->getTo() as $to) {
            $mail->addTo($to->getAddress(), $to->getName());
        }

        $mail->setSubject($email->getSubject());

        if ($email->getHtmlBody()) {
            $mail->addContent("text/html", $email->getHtmlBody());
        }

        if ($email->getTextBody()) {
            $mail->addContent("text/plain", $email->getTextBody());
        }

        $response = $sendgrid->send($mail);

        if ($response->statusCode() >= 400) {
            throw new \Exception('SendGrid API error: ' . $response->body());
        }
    }

    public function __toString(): string
    {
        return 'sendgrid';
    }
}
