<?php
use  EarthquakeSV\Handlers\HttpErrorHandler;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use \RedBeanPHP\R as R;

require __DIR__ . '/../vendor/autoload.php';

///////////////////////////
// create twing template //
///////////////////////////

$twig = Twig::create(__DIR__ . '/../templates/frontpage/',['cache'=> false]);

/////////////////////////////
// SETUP HTTP BAD REQUESTS //
/////////////////////////////

$displayErrorDetails = true;

$app = AppFactory::create();

// adding twig middlleware
$app->add(TwigMiddleware::create($app,$twig));

$callableResolver = $app->getCallableResolver();
$responseFactory = $app->getResponseFactory();

$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);


// Add Error Handling Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, false, false);
$errorMiddleware->setDefaultErrorHandler($errorHandler);


////////////////////
// Setup database //
////////////////////
const DATABASE = 'sismos';
const HOST = 'localhost';
const USER = 'root';
const PASSWORD = 'root';



R::setup('mysql:host='.HOST.';dbname='.DATABASE,USER,PASSWORD);


///////////////////
// DEFINE ROUTES //
///////////////////

$app->get('/', function ($request,$response, array $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response,'index.html');
})->setName('index');


// TODO: create a group for the v1 api

// Get country list
$app->get('/v1/get/countries','\EarthquakeSV\Api:countryList');
// Get places list
$app->get('/v1/get/places','\EarthquakeSV\Api:placeList');
// show earthquakes by specific place
$app->get('/v1/by/placeid/{place}','\EarthquakeSV\Api:byPlace');
// show all earthquakes by year
$app->get('/v1/by/date/{year}','\EarthquakeSV\Api:byYear');
// get earthquakes by month
$app->get('/v1/by/date/{year}/{month}','\EarthquakeSV\Api:byMonth');
// get earthquakes by day
$app->get('/v1/by/date/{year}/{month}/{day}','\EarthquakeSV\Api:byDay');
// get all earthquakes by richter scale
$app->get('/v1/by/richter/{richterFrom}/{richterTo}','\EarthquakeSV\Api:byRichterScale');
// get the last record
$app->get('/v1/get/last','\EarthquakeSV\Api:lastRecord');

// close database connection
R::close();
$app->run();
