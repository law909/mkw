<?php

namespace Controllers;

class oauth2Controller
{

    public function initiate()
    {
        \mkw\store::writelog('Initiating authentication', 'oauth2.txt');
        if (!file_exists(\mkw\store::tokensPath('token.json'))) {
            \mkw\store::writelog('Token file does not exist', 'oauth2.txt');
            $client = \mkw\googleoauth::getClient();
            \mkw\store::writelog('Creating authentication URL', 'oauth2.txt');
            $client->setPrompt('consent');
            $authUrl = $client->createAuthUrl();
            \mkw\store::writelog('Redirecting to authentication URL>' . $authUrl, 'oauth2.txt');
            header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
        }
    }

    public function callback()
    {
        if (isset($_GET['code'])) {
            $client = \mkw\googleoauth::getClient();

            $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $token = $client->getAccessToken();

            if ($token) {
                file_put_contents(\mkw\store::tokensPath('token.json'), json_encode($token));
                \mkw\store::writelog('Token saved', 'oauth2.txt');
            } else {
                \mkw\store::writelog('Authentication failed', 'oauth2.txt');
            }
        } else {
            \mkw\store::writelog('"code" parameter is missing', 'oauth2.txt');
        }
    }

}