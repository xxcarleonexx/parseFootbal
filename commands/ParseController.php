<?php

namespace app\commands;


use app\services\ImdbParser;
use Clue\React\Buzz\Browser;
use React\EventLoop\Factory;
use yii\console\Controller;
use yii\helpers\VarDumper;

class ParseController extends Controller
{

    public function actionParse()
    {
        $loop = Factory::create();

        $client = new Browser($loop);

        $parser = new ImdbParser($client);

        $parser->parse([
            'http://www.imdb.com/title/tt1270797/',
            'http://www.imdb.com/title/tt2527336/',
        ]);

        $loop->run();

        VarDumper::dump($parser->getMovieData());

    }

}
