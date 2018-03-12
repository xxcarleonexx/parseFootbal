<?php


namespace app\services;

use Symfony\Component\DomCrawler\Crawler;

class ImdbParser extends Parser
{

    public function extractFromHtml($html) : array
    {

        $crawler = new Crawler($html);

        $title = trim($crawler->filter('h1')->text());

        $genres = $crawler->filter('[itemprop="genre"] a')->extract(['_text']);
        $description = trim($crawler->filter('[itemprop="description"]')->text());

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

}
