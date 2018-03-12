<?php


namespace app\services;


use Clue\React\Buzz\Browser;
use Psr\Http\Message\ResponseInterface;

abstract class Parser
{

    /** @var Browser */
    private $client;

    /** @var array */
    private $parsed = [];

    public function __construct(Browser $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $urls
     */
    public function parse(array $urls = [])
    {
        foreach ($urls as $url) {
            $this->client->get($url)->then(
                function (ResponseInterface $response) {
                    $this->parsed[] = $this->extractFromHtml((string) $response->getBody());
                }
            );
        }
    }

    /**
     * @param $html
     * @return array
     */
    abstract public function extractFromHtml($html) : array;

    /**
     * @return array
     */
    public function getMovieData() : array
    {
        return $this->parsed;
    }

}
