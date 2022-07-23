<?php

namespace App\Services;

use Illuminate\Notifications\Notification;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\ApiException;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use Storage;

/**
 * Class SendinBlueService.
 */
class SendInBlue
{
    /**
     * @var SendSmtpEmail
     */
    private $_sendSmtpEmail;

    /**
     * @var TransactionalEmailsApi
     */
    private $_apiInstance;

    /**
     * SendinBlueService constructor.
     */
    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()
            ->setApiKey('api-key', config('api_keys.sendinblue'));
        $this->_apiInstance = new TransactionalEmailsApi(
            null,
            $config
        );
        $this->_sendSmtpEmail = new SendSmtpEmail();
    }

    /**
     * @param $email
     * @param $name
     *
     * @return $this
     */
    public function recipient($email, $name): SendInBlue
    {
        $recipient = ! app()->environment('production')
            ? config('mail.test_email') : $email;
        $this->_sendSmtpEmail['to'] = [
            [
                'email' => $recipient,
                'name'  => $name,
            ],
        ];

        return $this;
    }

    /**
     * @param $templateId
     *
     * @return $this
     */
    public function template($templateId): SendInBlue
    {
        $this->_sendSmtpEmail['templateId'] = $templateId;

        return $this;
    }

    /**
     * @param  array|null  $paramList  ['project_name' => $projectName, 'user_name'
     *                         => $this->authUser->name]
     *
     * @return SendInBlue
     */
    public function params(?array $paramList): SendInBlue
    {
        if (! $paramList) {
            return $this;
        }
        $this->_sendSmtpEmail['params'] = $paramList;

        return $this;
    }

    /**
     * @param $filename
     * @param $fileLocation
     * @param $extension
     *
     * @return $this
     */
    public function attach($filename, $fileLocation, $extension): SendInBlue
    {
        $this->_sendSmtpEmail['attachment'] = [
            [
                'url'  => config('app.url').Storage::url($fileLocation),
                'name' => $filename.'.'.$extension,
            ],
        ];

        return $this;
    }

    /**
     * @param $filename
     * @param $extension
     * @param $content
     *
     * @return $this
     */
    public function attachContent($filename, $content): SendInBlue
    {
        $this->_sendSmtpEmail['attachment'] = [
            [
                'content'  => $content,
                'name' => $filename,
            ],
        ];

        return $this;
    }

    public function post(): void
    {
        try {
            $this->_apiInstance->sendTransacEmail($this->_sendSmtpEmail);
        } catch (ApiException $e) {
            echo 'Exception when calling SMTPApi->sendTransacEmail: ',
            $e->getMessage(), PHP_EOL;
        }
    }

    public function send($notifiable, Notification $notification): void
    {
        $params = $this->getTransactionalParameters($notifiable, $notification);
        $sib = $this->params($params['params'])
            ->template($params['template_id'])
            ->recipient($notifiable->email, $notifiable->first_name.' '.$notifiable->last_name);
        if (array_key_exists('attachment', $params)) {
            $this->setAttachmentType($sib, $params);
        }

        $sib->post();
    }

    public function setAttachmentType(SendInBlue $sib, array $params): void
    {
        if (array_key_exists('content', $params['attachment'])) {
            $sib->attachContent($params['attachment']['name'], $params['attachment']['content']);
        } else {
            $sib->attach($params['attachment']['filename'], $params['attachment']['fileLocation'], $params['attachment']['extension']);
        }
    }

    public function getTransactionalParameters($notifiable, SendInBlueInterface $notification): array
    {
        $params = array_merge([
            'first_name' => $notifiable->first_name,
            'last_name' => $notifiable->last_name,
        ],
            $notification->toSendInBlue($notifiable)
        );

        if (! array_key_exists('params', $params)) {
            $params['params'] = null;
        }

        return $params;
    }
}
