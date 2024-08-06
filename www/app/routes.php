<?php

declare(strict_types=1);

use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Danilocgsilva\ClassToSqlSchemaScript\DatabaseScriptSpitter;
use App\Domain\Front;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $queryStringsArray = $request->getQueryParams();

        if (isset($queryStringsArray['database'])) {
            $databaseScriptSpitter = new DatabaseScriptSpitter($queryStringsArray['database']);
            
            $response->getBody()->write(
                $databaseScriptSpitter->getScript()
            );
            return $response;
        }

        if (isset($queryStringsArray['tables'])) {
            $tablesToGenerate = [];
            foreach ($queryStringsArray['tables'] as $tableName) {
                $tableScriptSpitter = new TableScriptSpitter($tableName);
                if (isset($queryStringsArray['fields'])) {
                    foreach ($queryStringsArray['fields'] as $keyFieldName => $typePrimary) {
                        Front::addUserDataToTableScriptSpitter($tableScriptSpitter, $typePrimary, $keyFieldName);
                    }
                }
                $tablesToGenerate[] = $tableScriptSpitter;
            }
            
            $returnString = "";
            foreach ($tablesToGenerate as $tableToGenerate) {
                $returnString .= $tableToGenerate->getScript() . "\n";
            }

            $response->getBody()->write(
                str_replace("\n", "<br />", $returnString) 
            );

            return $response;
        }

        $response->getBody()->write(
            "You must choose if you want to generate a database or a table sql script."
        );
        return $response;
    });
};
