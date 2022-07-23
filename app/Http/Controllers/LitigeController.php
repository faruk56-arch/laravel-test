<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Log;
use ReCaptcha\ReCaptcha;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\ApiException;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use SendinBlue\Client\Model\SendSmtpEmailSender;
use SendinBlue\Client\Model\SendSmtpEmailTo;

class LitigeController extends Controller
{
    public function sendLitige(Request $request)
    {
        if ($this->userIsRobot($request)) {
            return response()->json(['error' => 'User is a robot'], 400);
        }

        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', env('SENDINBLUE_API_KEY'));
        if ($request->file('file')) {
            $attachment = env('APP_URL').'/'.$request->file('file')->store('public/litiges');
            $attachment = str_replace('/public', '', $attachment);
        } else {
            $attachment = null;
        }

        $apiInstance = new TransactionalEmailsApi(
            new Client(),
            $config
        );
        $email = env('APP_ENV') !== 'production' ? env('TEST_EMAIL') : 'info@classic-parts-finder.com';
        $sendSmtpEmail = new SendSmtpEmail(
            [
                'sender'     => new SendSmtpEmailSender(
                    [
                        'name'  => 'Classic Parts Finder',
                        'email' => 'info@classic-parts-finder.com',
                    ]
                ),
                'templateId' => 809,
                'params'     => array_merge($request->except('file'), ['attachment' => $attachment]),
                'to'         => [
                    new SendSmtpEmailTo(
                        [
                            'email' => $email,
                            'name'  => $request->firstname.' '.$request->lastname,
                        // 'email' => $message['to'], 'name' => $message['name'],
                        ]
                    ),
                ],
            ]
        );
        try {
            if (env('APP_ENV') !== 'testing') {
                $apiInstance->sendTransacEmail($sendSmtpEmail);
            }
        } catch (ApiException $e) {
            Log::info(
                'Exception when calling SMTPApi->sendTransacEmail: '.$e->getMessage().PHP_EOL
            );
        }

        return $sendSmtpEmail;
    }

    //end sendLitige()

    public function sendContact(Request $request)
    {
        if ($this->userIsRobot($request)) {
            return response()->json(['error' => 'User is a robot'], 400);
        }

        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', env('SENDINBLUE_API_KEY'));
        $apiInstance = new TransactionalEmailsApi(
            new Client(),
            $config
        );
        $email = env('APP_ENV') !== 'production' ? env('TEST_EMAIL') : 'info@classic-parts-finder.com';
        $sendSmtpEmail = new SendSmtpEmail(
            [
                'sender'     => new SendSmtpEmailSender(
                    [
                        'name'  => 'Classic Parts Finder',
                        'email' => 'info@classic-parts-finder.com',
                    ]
                ),
                'templateId' => 810,
                'params'     => $request->all(),
                'to'         => [
                    new SendSmtpEmailTo(
                        [
                            'email' => $email,
                            'name'  => $request->name,
                        // 'email' => $message['to'], 'name' => $message['name'],
                        ]
                    ),
                ],
            ]
        );
        try {
            if (env('APP_ENV') !== 'testing') {
                $apiInstance->sendTransacEmail($sendSmtpEmail);
            }
        } catch (ApiException $e) {
            Log::info(
                'Exception when calling SMTPApi->sendTransacEmail: '.$e->getMessage().PHP_EOL
            );
        }

        return view('thanks');
    }

    //end sendContact()

    private function userIsRobot(Request $request)
    {
        $request->validate(['g-recaptcha-response' => 'required|filled']);
        $secret = env('CAPTCHA_API_KEY');
        $hostname = app()->environment('local') ? 'classic-parts-finder.test' : 'classic-parts-finder.com';
        $recaptcha = new ReCaptcha($secret);
        $resp = $recaptcha->setExpectedHostname($hostname)->verify($request['g-recaptcha-response'], $request->ip());
        if ($resp->isSuccess()) {
            return false;
        }

        Log::error(print_r($resp->getErrorCodes(), true));

        return true;
    }

    //end userIsRobot()
}//end class
