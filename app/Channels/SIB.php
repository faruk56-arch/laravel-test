<?php

namespace App\Channels;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\ApiException;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use SendinBlue\Client\Model\SendSmtpEmailSender;
use SendinBlue\Client\Model\SendSmtpEmailTo;

class SIB
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     *
     * @return string
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSendInBlue($notifiable);
        $config = Configuration::getDefaultConfiguration()
            ->setApiKey('api-key', config('app.SENDINBLUE_API_KEY'));
        $apiInstance = new TransactionalEmailsApi(
            new Client(),
            $config
        );
        $email = config('app.env') !== 'production'
            ? config('app.TEST_EMAIL') : $message['to'];
        $sendSmtpEmail = new SendSmtpEmail([
            'sender'     => new SendSmtpEmailSender([
                'name'  => 'Classic Parts Finder',
                'email' => 'info@classic-parts-finder.com',
            ]),
            'templateId' => $message['template'],
            'params'     => $message['params'],
            'to'         => [
                new SendSmtpEmailTo([
                    'email' => $email,
                    'name'  => $message['name'],
                    //                    'email' => $message['to'], 'name' => $message['name'],
                ]),
            ],
        ]);
        try {
            if (config('app.env') !== 'testing') {
                $apiInstance->sendTransacEmail($sendSmtpEmail);
            }
        } catch (ApiException $e) {
            \Log::info('Exception when calling SMTPApi->sendTransacEmail: '
                .$e->getMessage().PHP_EOL);
            \Log::info($message);
        }
        // TODO : swap to sib class
    }
}
