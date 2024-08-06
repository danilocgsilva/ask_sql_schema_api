<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Danilocgsilva\ClassToSqlSchemaScript\DatabaseScriptSpitter;

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

            // if (isset($queryStringsArray['id'])) {
            //     $tableScriptSpitter->addField(
            //         (new FieldScriptSpitter($queryStringsArray['id']))
            //         ->setType("INT")
            //         ->setUnsigned()
            //         ->setAutoIncrement()
            //         ->setNotNull()
            //         ->setPrimaryKey()
            //     );
            // }

            if () {

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
