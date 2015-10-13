#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

date_default_timezone_set('America/Sao_Paulo');

$config = json_decode(file_get_contents('config.json'));

$client = new \RodrigoRigotti\Api\OlhoVivo($config->token);

$config = json_decode(file_get_contents('config.json'));

$numLinhas = $config->linhas;
$client->authorize();

foreach ($numLinhas as $numLinha) {

    $linha = $client->getBusLine($numLinha);

    $file = fopen("output/$numLinha.csv", "a");
    $date = date("Y-m-d H:i:s");
    echo "$numLinha - $date\n";

    try {
       foreach ($linha as $sentido) {
           $posicoes = $client->getBusPositionByLineCode($sentido->CodigoLinha);
           foreach ($posicoes->vs as $bus)
               fwrite($file, "{$numLinha};{$sentido->Sentido};{$date};{$posicoes->hr};{$bus->p};{$bus->px},{$bus->py}\n");
       }

       fclose($file);
    } catch (\Exception $e) {
    }
}
