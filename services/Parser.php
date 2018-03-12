<?php


namespace app\services;


use Clue\React\Buzz\Browser;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

class Parser
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
    public function extractFromHtml($html) : array
    {
        $crawler = new Crawler($html);

        $title = trim($crawler->filter('h1')->text());

        $genres = $crawler->filter('[itemprop="genre"] a')->extract(['_text']);
        $description = trim($crawler->filter(['itemprop="description"'])->text());

        $crawler->filter('#titleDetails .txt-block')->each(
            function(Crawler $crawler) {
                foreach ($crawler->children() as $node) {
                    $node->parentNode->removeChild($node);
                }
            }
        );

        $releaseDate = trim($crawler->filter('#titleDetails .txt-block')->eq(3)->text());

        return [
            'title' => $title,
            'genres' => $genres,
            'description' => $description,
            'release_date' => $releaseDate,
        ];
    }


    public function getMovieData() : array
    {
        return $this->parsed;
    }

}
