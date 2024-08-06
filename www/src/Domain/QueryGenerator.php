<?php

declare(strict_types=1);

namespace App\Domain;

use Danilocgsilva\ClassToSqlSchemaScript\DatabaseScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\SpitterInterface;
use Danilocgsilva\ClassToSqlSchemaScript\ForeignKeyScriptSpitter;
use Danilocgsilva\ClassToSqlSchemaScript\TableScriptSpitter;

class QueryGenerator
{
    private SpitterInterface $spitterImplementation;

    private array $fields = [];

    private array $tables = [];

    private array $foreigns = [];

    private string $returnString = "";

    private DatabaseScriptSpitter $databaseScriptSpitter;

    public function getString(): string
    {
        $databaseScriptSpitter = $this->databaseScriptSpitter ?? null;
        if ($databaseScriptSpitter) {
            $this->returnString .= $databaseScriptSpitter->getScript() . "\n";
        }
        
        foreach ($this->tables as $tableName) {
            $tableScriptSpitter = new TableScriptSpitter($tableName);
            if (count($this->fields) > 0) {
                foreach ($this->fields as $keyFieldName => $typePrimary) {
                    Front::addUserDataToTableScriptSpitter($tableScriptSpitter, $typePrimary, $keyFieldName);
                }
            }
            $this->returnString .= $tableScriptSpitter->getScript() . "\n";
        }

        if (count($this->tables) === 1) {
            foreach ($this->foreigns as $foreigns) {
                $sourceTarget = explode(":", $foreigns);
                $source = $sourceTarget[0];
                $target = $sourceTarget[1];
                $tableName = $this->tables[0];
                
                $foreignKeyScriptSpitter = new ForeignKeyScriptSpitter();
                $foreignKeyScriptSpitter->setConstraintName($source . "_" . $target . "_constraint");
                $foreignKeyScriptSpitter->setTable($tableName);
                $foreignKeyScriptSpitter->setTableForeignkey($source);
                $foreignKeyScriptSpitter->setForeignKey($target);
                $foreignKeyScriptSpitter->setForeignTable($tableName);

                $this->returnString .= $foreignKeyScriptSpitter->getScript();
            }
        }

        return $this->returnString;
    }

    public function setDatabaseSpitter(DatabaseScriptSpitter $databaseScriptSpitter): self
    {
        $this->databaseScriptSpitter = $databaseScriptSpitter;
        return $this;
    }

    public function addTables(array $tablesEntry): self
    {
        $this->tables = $tablesEntry;
        return $this;
    }

    public function addFields(array $fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    public function addForeigns(array $foreigns): self
    {
        $this->foreigns = $foreigns;
        return $this;
    }
}
