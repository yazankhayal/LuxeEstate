<?php

namespace App\Mail\Transport;

use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Configuration;
use Brevo\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mime\Email;

class BrevoTransport extends AbstractTransport
{
    protected TransactionalEmailsApi $apiInstance;

    public function __construct()
    {
        parent::__construct(); // Important for Symfony Mailer internal properties

        $config = Configuration::getDefaultConfiguration()
            ->setApiKey('api-key', env('BREVO_API_KEY'));

        $this->apiInstance = new TransactionalEmailsApi(new GuzzleClient(), $config);
    }

    protected function doSend(SentMessage $sentMessage): void
    {
        /** @var Email $email */
        $email = $sentMessage->getOriginalMessage();

        $to = [];
        foreach ($email->getTo() as $address) {
            $to[] = [
                'email' => $address->getAddress(),
                'name'  => $address->getName() ?: $address->getAddress(),
            ];
        }

        $sendSmtpEmail = new SendSmtpEmail([
            'subject' => $email->getSubject(),
            'sender'  => [
                'name'  => env('MAIL_FROM_NAME'),
                'email' => env('MAIL_FROM_ADDRESS'),
            ],
            'to'      => $to,
            'htmlContent' => $email->getHtmlBody() ?? $email->getTextBody(),
        ]);

        $this->apiInstance->sendTransacEmail($sendSmtpEmail);
    }

    public function __toString(): string
    {
        return 'brevo';
    }
}
