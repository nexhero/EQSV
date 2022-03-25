<?php
use \RedBeanPHP\R as R;

require __DIR__ . '/../vendor/autoload.php';

// setup the database
const DATABASE = 'sismos';
const HOST = 'localhost';
const USER = 'root';
const PASSWORD = 'root';

// define months
$months = array();
$months['Ene'] = "1";
$months['Feb'] = "2";
$months['Mar'] = "3";
$months['Abr'] = "4";
$months['May'] = "5";
$months['Jun'] = "6";
$months['Jul'] = "7";
$months['Ago'] = "8";
$months['Sep'] = "9";
$months['Oct'] = "10";
$months['Nov'] = "11";
$months['Dic'] = "12";

// setup fuse
R::setup('mysql:host='.HOST.';dbname='.DATABASE,USER,PASSWORD);
$states = R::getAll('select id,placeName from places');

// load the csv file
$csv = new \ParseCsv\Csv();
$csv->encoding('UTF-16', 'UTF-8');
$csv->delimiter = ",";
$csv->parseFile('sismos.csv');
$data = $csv->data;
$index = 0;


// $description = explode('.',$csv->data[$index]['description'])[0];

foreach ($data as $d) {
    $i = 0;
    $description = $d['description'];
    foreach ($states as $s) {
        if(stripos($description,$s['placeName'])){
            $states[$i]['percent'] = 100;
        }else{
            similar_text($description,$s['placeName'],$percent);
            $states[$i]['percent'] = $percent;

        }
        $i++;
    }

// check what on what place happened
    $onPlace = $states[0];
    foreach ($states as $s) {
        if($onPlace['percent'] < $s['percent']){
            $onPlace = $s;
        }
    }

// fix the datetime format
    $date = explode(' ',$d['date']);
    $time = $d['time'];
    $m = $date[0];
    $date[0] = $months[$m];
    $day = substr($date[1],0,2);
    $dt = $date[2] .'-'. $date[0] . '-'.$day .' '. $d['time'];
    $e = R::dispense('epicentre');
    $e->datetime = new DateTime($dt);
    $e->latitude = $d['latitude'];
    $e->longitude = $d['longitude'];
    $e->description = $d['description'];
    $e->depth = $d['depth'];
    $e->richter = $d['richter'];
    $e->u_id = $onPlace['id'];

    R::store($e);

    $index ++;
}
