<?php

namespace App\Services;

use SendinBlue\Client\Api\SMTPApi;
use SendinBlue\Client\ApiException;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use Storage;

/**
 * Class SendinBlueService.
 */
class SendinBlueService
{
    /**
     * @var SendSmtpEmail
     */
    private $_sendSmtpEmail;

    /**
     * @var SMTPApi
     */
    private $_apiInstance;

    /**
     * SendinBlueService constructor.
     */
    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()
            ->setApiKey('api-key', env('SENDINBLUEAPIKEY'));
        $this->_apiInstance = new SMTPApi(
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
    public function recipient($email, $name)
    {
        $recipient = env('APP_ENV') !== 'production'
            ? env('TEST_EMAIL') : $email;
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
    public function template($templateId)
    {
        $this->_sendSmtpEmail['templateId'] = $templateId;

        return $this;
    }

    /**
     * @param  array  $paramList  ['project_name' => $projectName, 'user_name'
     *                         => $this->authUser->name]
     *
     * @return SendinBlueService
     */
    public function params(array $paramList)
    {
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
    public function attach($filename, $fileLocation, $extension)
    {
        $this->_sendSmtpEmail['attachment'] = [
            [
                'url'  => env('APP_URL').Storage::url($fileLocation),
                'name' => $filename.'.'.$extension,
            ],
        ];

        return $this;
    }

    public function send()
    {
        try {
            $this->_apiInstance->sendTransacEmail($this->_sendSmtpEmail);
        } catch (ApiException $e) {
            echo 'Exception when calling SMTPApi->sendTransacEmail: ',
            $e->getMessage(), PHP_EOL;
        }
    }
}
