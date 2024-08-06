<?php

declare(strict_types=1);

namespace App\Domain;

use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\FieldScriptSpitter;

class Front
{
    public static function addUserDataToTableScriptSpitter(
        TableScriptSpitter $tableScriptSpitter,
        string $typePrimary, 
        string $keyFieldName
    ): void
    {
        $typePrimaryParts = explode(":", $typePrimary);

        $tableScriptSpitter->addField(
            $field = (new FieldScriptSpitter($keyFieldName))
            ->setType($typePrimaryParts[0])
        );

        if (count($typePrimaryParts) > 1 && $typePrimaryParts[1] === "KEY") {
            $field
            ->setNotNull()
            ->setPrimaryKey()
            ->setUnsigned();
        }
    }
}