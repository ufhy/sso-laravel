<?php

namespace App\Socialite;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class EssProvider extends AbstractProvider implements ProviderInterface
{
    protected $scopes = ['*'];

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://hcis-dev3.kallagroup.co.id/oauth/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return 'https://hcis-dev3.kallagroup.co.id/oauth/token';
    }

    protected function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new Client([
                'verify' => false,
            ]);
        }

        return $this->httpClient;
    }

    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            RequestOptions::HEADERS => $this->getTokenHeaders($code),
            RequestOptions::FORM_PARAMS => $this->getTokenFields($code),
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://hcis-dev3.kallagroup.co.id/api/user', [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return Arr::get(json_decode($response->getBody(), true), 'data');
    }

    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'uid' => $user['uid'],
            'username' => $user['username'],
            'email' => $user['email'],
            'person' => $user['person'],
        ]);
    }
}
