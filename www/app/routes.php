<?php

declare(strict_types=1);

use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Danilocgsilva\ClassToSqlSchemaScript\DatabaseScriptSpitter;
use App\Domain\Front;
use App\Domain\QueryGenerator;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        
        $queryStringsArray = $request->getQueryParams();
        
        $database = $queryStringsArray['database'] ?? false;
        $tables = $queryStringsArray['tables'] ?? false;
        $foreigns = $queryStringsArray['foreigns'] ?? false;
        $fields = $queryStringsArray['fields'] ?? false;
        
        if ($database || $tables || $foreigns) {
            $queryGenerator = new QueryGenerator();
            
            if ($database) {
                $databaseScriptSpitter = new DatabaseScriptSpitter($database);
                $queryGenerator->setDatabaseSpitter($databaseScriptSpitter);
            }
    
            if ($tables) {
                $queryGenerator->addTables($tables);
    
                if ($fields) {
                    $queryGenerator->addFields($fields);
                }
            }

            if ($foreigns) {
                $queryGenerator->addForeigns($foreigns);
            }

            $response->getBody()->write(
                str_replace("\n", "<br />", $queryGenerator->getString()) 
            );

            return $response;
        }

        $response->getBody()->write(
            "You must choose if you want to generate a database or a table sql script."
        );
        return $response;
    });
};
