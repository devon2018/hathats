<?php

namespace App\Repositories;

use GuzzleHttp\Client;

class GoodTillRepository
{

    /**
     *
     * The Goodtill api call
     *
     * @var string
     */
    private $apiUrl = 'https://api.thegoodtill.com/api/';

    /**
     *
     * Authentication token for good-till calls.
     *
     * @var string
     */
    private $authenticationToken;

    /**
     *
     * Sets up the authentication and the connection with the good-till api.
     *
     */
    public function __construct()
    {
        try {
            $res = $this->makeCall('POST', 'login', ['form_params' => config('services.goodtill')]);
            $this->authenticationToken = $res->token;
        } catch (\Throwable $th) {
            abort(400, 'Failed to authenticate with Till provider.');
        }
    }


    /**
     *
     * Make the call to the goodtill api
     *
     * @param string $method
     * @param string $endpoint
     * @param array $data
     */
    public function makeCall(string $method = 'GET', string $endpoint = 'config', array $data = [])
    {
        $client = new Client();

        $headers = [
            'Accept'     => 'application/json'
        ];

        if (!empty($this->getToken())) $headers['Authorization'] = "Bearer {$this->getToken()}";

        $res = $client->request($method, "{$this->apiUrl}{$endpoint}", array_merge([
            'headers' => $headers,
        ], $data));

        return json_decode($res->getBody()->getContents());
    }

    /**
     *
     * Return the authenticated api token
     *
     * @return string
     */
    public function getToken(): ?string
    {
        return $this->authenticationToken;
    }
}
