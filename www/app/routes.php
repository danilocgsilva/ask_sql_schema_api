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

        if (isset($queryStringsArray['table'])) {
            $tableScriptSpitter = new TableScriptSpitter($queryStringsArray['table']);

            if (isset($queryStringsArray['fields'])) {
                foreach ($queryStringsArray['fields'] as $keyFieldName => $typePrimary) {
                    // $typePrimaryParts = explode(":", $typePrimary);
                    // $type = $typePrimaryParts[0];

                    // $field = (new FieldScriptSpitter($keyFieldName))
                    // ->setType($type);

                    // if (count($typePrimaryParts) > 1 && $typePrimaryParts[1] === "KEY") {
                    //     $field
                    //     ->setNotNull()
                    //     ->setPrimaryKey()
                    //     ->setUnsigned();
                    // }

                    // $tableScriptSpitter->addField($field);
                    Front::addUserDataToTableScriptSpitter($tableScriptSpitter, $typePrimary, $keyFieldName);
                }
            }

            $response->getBody()->write(
                str_replace("\n", "<br />", $tableScriptSpitter->getScript()) 
            );

            return $response;
        }

        $response->getBody()->write(
            "You must choose if you want to generate a database or a table sql script."
        );
        return $response;
    });
};
