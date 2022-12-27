<?php

namespace App\Repositories;

use App\Models\Collections\SymbolCollection;
use App\Models\SymbolObject;

class SymbolApiRepository implements SymbolRepository
{
    public function getRequest(): SymbolCollection
    {
        $symbolCollection = new SymbolCollection();
        $queryBuilder = (new DatabaseConnection())->getConnection()->createQueryBuilder();

        $queries = $queryBuilder
            ->select('u.id', 'u.username', 'w.cash', 't.symbol', 't.price', 't.value', 't.paymount', 't.user_id')
            ->from('user_schema.usersRegister', 'u')
            ->leftJoin('u', 'user_schema.wallet', 'w', 'u.id=w.id')
            ->leftJoin('w', 'user_schema.transactions', 't', 'w.id=t.user_id')
            ->fetchAllAssociative();

        foreach ($queries as $query) {
            if ($query['id'] === $_SESSION['id']) {
                $symbolCollection->add(
                    new SymbolObject(
                        $query['id'],
                        $query['username'],
                        $query['cash'],
                        $query['symbol'],
                        $query['price'],
                        $query['value'],
                        $query['paymount'],
                        $query['user_id'],
                    )
                );
            }
        }
        return $symbolCollection;
    }

    private function logo(string $symbol): string
    {
        $apiKey = $_ENV['API_KEY'];

        $parameters = [
            'convert' => 'EUR',
            'symbol' => $symbol
        ];
        $headers = [
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY: ' . $apiKey
        ];
        $qs = http_build_query($parameters); // query string encode the parameters

        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/info?symbol={$symbol}';

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
        var_dump($response);die;
        return $response;
    }

    public function add(array $row): void
    {
        (new DatabaseConnection())->getConnection()->insert(
            "user_schema.transactions",
            [
                "symbol" => $row[0],
                "price" => $row[1],
                "value" => $row[2],
                "paymount" => $row[3],
                "user_id" => $row[4]
            ]
        );
    }

    public function update(float $cash, int $id): void
    {
        (new DatabaseConnection())->getConnection()->executeStatement(
            'UPDATE user_schema.wallet SET cash=cash+? WHERE id = ?',
            [$cash, $id]
        );
    }
}