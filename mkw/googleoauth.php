<?php

namespace mkw;

class googleoauth
{

    public static function getClient()
    {
        $client = new \Google_Client();
        $client->setAuthConfig(\mkw\store::keysPath('credentials.json'));
        $client->addScope(\Google_Service_Gmail::GMAIL_SEND);
        $client->setAccessType('offline');
        $client->setRedirectUri(\mkw\store::getFullUrl('/oauth2/callback'));

        return $client;
    }

    public static function getExistingToken()
    {
        return json_decode(file_get_contents(\mkw\store::tokensPath('token.json')), true);
    }
}
