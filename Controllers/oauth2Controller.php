<?php

namespace Controllers;

class oauth2Controller
{

    public function initiate()
    {
        if (!file_exists(\mkw\store::tokensPath('token.json'))) {
            $client = \mkw\googleoauth::getClient();
            $client->setPrompt('consent');
            $authUrl = $client->createAuthUrl();
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