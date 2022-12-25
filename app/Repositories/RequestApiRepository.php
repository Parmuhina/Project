<?php

namespace App\Repositories;

use App\Models\Collections\RequestCollection;
use App\Models\RequestObject;

class RequestApiRepository implements RequestRepository
{
    public function getRequest(string $convert, string $symbols): RequestCollection
    {
        $apiKey = $_ENV['API_KEY'];
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest';
        $parameters = [

            'convert' => $convert,
            'symbol' => implode(",", explode(",", $symbols))
        ];
        $headers = [
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY: ' . $apiKey
        ];
        $qs = http_build_query($parameters); // query string encode the parameters
        $request = "{$url}?{$qs}"; // create the request URL

        $curl = curl_init(); // Get cURL resource
        // Set cURL options
        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,            // set the request URL
            CURLOPT_HTTPHEADER => $headers,     // set the headers
            CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));

        $response = curl_exec($curl); // Send the request, save the response
        $response = (json_decode($response)); // print json decoded response
        curl_close($curl);

        $requestCollection = new RequestCollection();

        foreach ($response->data as $row) {

            $requestCollection->add(
                new RequestObject(
                    $row->name,
                    $row->symbol,
                    $row->max_supply,
                    $row->circulating_supply,
                    $row->last_updated,
                    $row->quote->{$convert}->price,
                    $row->quote->{$convert}->volume_24h,
                    $row->quote->{$convert}->volume_change_24h,
                    $row->quote->{$convert}->percent_change_1h,
                    $row->quote->{$convert}->percent_change_24h,
                    $this->logo($row->symbol),
                )
            );
        }
        return $requestCollection;
    }

    private function logo(string $symbol):string
    {
        $apiKey = $_ENV['API_KEY'];
        $url='https://pro-api.coinmarketcap.com/v2/cryptocurrency/info';
        $parameters = [
            'symbol' => $symbol
        ];
        $headers = [
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY: ' . $apiKey
        ];
        $qs = http_build_query($parameters); // query string encode the parameters

        $request = "{$url}?{$qs}"; // create the request URL

        $curl = curl_init(); // Get cURL resource
        // Set cURL options
        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,            // set the request URL
            CURLOPT_HTTPHEADER => $headers,     // set the headers
            CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));

        $response = curl_exec($curl); // Send the request, save the response
        $response = (json_decode($response)); // print json decoded response
        curl_close($curl);

        return $response->data->{$symbol}[0]->logo;
    }
}