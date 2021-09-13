<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RewardCounter
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $pathApiFull;

    /**
     * RewardCounter constructor.
     *
     * @param HttpClientInterface $client
     * @param string $pathDeal
     * @param string $urlApi
     */
    public function __construct(HttpClientInterface $client, string $pathDeal, string $urlApi)
    {
        $this->client = $client;
        $this->pathApiFull = $urlApi . $pathDeal;
    }


    public function getDeals()
    {
        $dealsResponse = $this->client->request('GET', $this->pathApiFull);
        $content = $dealsResponse->getContent();

        return json_decode($content);
    }
}