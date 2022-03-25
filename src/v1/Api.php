<?php
namespace EarthquakeSV;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \RedBeanPHP\R as R;

CONST _OK = 200;
CONST _NO_CONTENT = 204;

// TODO: validate numeric arguments

class Api
{
    // TODO: it need a better name and for validate the arguments
    // generate the response code and attach the data into the json
   private static function resMetadata($data){
        $countRecords = count($data['records']);
        $metaData = null;
        if($countRecords == 0){
            $metaData = [
                'response' => [
                    'code' => _NO_CONTENT,
                    'message'=>'No records found!',
                ],
            ];
        }else{
            $metaData = [
                'response' =>[
                    'code' =>_OK,
                    'message' =>'OK',
                    'total_records' => $countRecords,
                ],
                'data' => $data,
            ];
        }
        return json_encode($metaData);
    }
    public static function countryList(Request $request, Response $response ){
        $countries = R::getAll( 'select * from countries');
        $data = ['records'=>$countries];
        $payload = self::resMetadata($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type','application/json');
    }
    // TODO: also show the country name
    public static function placeList(Request $request, Response $response){
        $places = R::getAll( 'Select * from places');
        $data = ['records'=>$places];
        $payload = self::resMetadata($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type','application/json');
    }

    public static function byPlace(Request $request, Response $response, $args){
        $placeID = $args['place'];
        $epi  = R::find( 'epicentre', 'u_id = '.$placeID);
        $place = R::getAll('select places.placeName as epicentre, countries.countryName as country from places,countries where places.id=? and countries.id=places.countryID',[$placeID]);
        $data = [
            'place'=>$place,
            'records'=>$epi,
        ];
        $payload = self::resMetadata($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public static function byYear(Request $request, Response $response, $args){
        $year = $args['year'];
        $records = R::find('epicentre', ' year(datetime)=? ',[$year]);
        $data = ['records'=>$records];
        $payload = self::resMetadata($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type','application/json');
    }
    public static function byMonth(Request $request, Response $response, $args){

        $year = $args['year'];
        $month = $args['month'];
        $records = R::find('epicentre', ' year(datetime)=? AND month(datetime)=?',[$year,$month]);
        $data = ['records'=>$records];
        $payload = self::resMetadata($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type','application/json');
    }
    public static function byDay(Request $request, Response $response, $args){
        $year = $args['year'];
        $month = $args['month'];
        $records = R::find('epicentre', ' year(datetime)=? AND month(datetime)=? AND day(datetime)=?', [$year,$month,$day]);
        $data = ['records'=>$records];
        $payload = self::resMetadata($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type','application/json');
    }

    public static function byRichterScale(Request $request, Response $response, $args){
        $richterFrom = $args['richterFrom'];
        $richterTo = $args['richterTo'];
        $records = R::find('epicentre','richter BETWEEN ? AND ?',[$richterFrom, $richterTo]);
        $data = ['records'=>$records];
        $payload = self::resMetadata($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type','application/json');
    }
    public static function lastRecord(Request $request, Response $response){
        $record = R::getAll('select epicentre.*, c.countryName from epicentre join places on epicentre.u_id = places.id join countries c on places.countryid=c.id order by datetime desc limit 1');
        $data = ['records'=>$record];
        $payload = self::resMetadata($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type','application/json');
    }
    }
}
