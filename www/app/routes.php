<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;
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

        if ($tableName = $queryStringsArray['database']) {
            $databaseScriptSpitter = new DatabaseScriptSpitter($tableName);
            
            $response->getBody()->write(
                $databaseScriptSpitter->getScript()
            );
            return $response;;
        }

        return "You must choose if you want to generate a database or a table sql script.";
    });
};
