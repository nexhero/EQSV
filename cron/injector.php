<?php
use \RedBeanPHP\R as R;
use Goutte\Client;

require __DIR__ . '/../vendor/autoload.php';

////////////////////
// Setup database //
////////////////////
const DATABASE = 'sismos';
const HOST = 'localhost';
const USER = 'root';
const PASSWORD = 'root';

R::setup('mysql:host='.HOST.';dbname='.DATABASE,USER,PASSWORD);


$c = new Client();

$crawler = $c->request('GET',"https://snet.gob.sv/ver/sismologia/monitoreo/sismos+reportados/ultimos+10+sismos");

// $crawler->filter('tr')->each(function($node,$i){
//     // print $node->text();
//     var_dump($node);
// });
foreach ($crawler as $domElement) {
    var_dump($domElement->nodeName);
    print($domElement->nodeName);
}
