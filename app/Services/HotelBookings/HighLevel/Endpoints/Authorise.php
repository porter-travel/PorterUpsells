<?php

namespace App\Services\HotelBookings\HighLevel\Endpoints;

use AWS\CRT\HTTP\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

class Authorise extends HighlevelEndpoint
{

   /** @var array<string> $authParams */
   var $authParams=[];

   function __construct(array $config)
   {
      parent::__construct($config);
      $this->call($config);
   }

   /**
    *
    * @param array $params
    * @return array<string>
    * @throws GuzzleException
    */
     function call(array $params) : void
     {
//         dd($params);
         $endpoint = "/api/v1/authentication/login";
         $response = $this->client->request('POST', $endpoint,['json' => $params]);
         $responseString = (string) $response->getBody();

         if($response->getStatusCode()==500)
            throw new \Exception("Highlevel endpoint server error");



         $responseObject = json_decode($responseString);

         $responseArray = self::parseAuthResponse($responseObject);

         $this->authParams = $responseArray;
     }

    static function parseAuthResponse(object $authResponseObject) : array
    {
        $responseArray = [];
        if(empty($authResponseObject->data)) {
//            dd($authResponseObject);
            throw new \Exception("Auth failed");
        }
        $responseArray['access_token'] = $authResponseObject->data->access_token;
        $responseArray['refresh_token'] = $authResponseObject->data->refresh_token;
        $responseArray['session_expires'] = $authResponseObject->data->session_expires;
        $responseArray['responseObject'] = $authResponseObject->data;
        return $responseArray;
    }
}
