#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

date_default_timezone_set('America/Sao_Paulo');

$config = json_decode(file_get_contents('config.json'));

$client = new \RodrigoRigotti\Api\OlhoVivo($config->token);

while (1) {

    $numLinhas = $config->linhas;


    foreach ($numLinhas as $numLinha) {

        $linha = $client->getBusLine($numLinha);

        $file = fopen("output/$numLinha.csv", "a");
        $date = date("Y-m-d H:i:s");

        foreach ($linha as $sentido) {
            $posicoes = $client->getBusPositionByLineCode($sentido->CodigoLinha);
            foreach ($posicoes->vs as $bus)
                fwrite($file, "{$numLinha};{$sentido->Sentido};{$date};{$posicoes->hr};{$bus->p};{$bus->px},{$bus->py}\n");
        }

        fclose($file);
    }
    sleep(60);
}
