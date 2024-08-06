<?php

declare(strict_types=1);

namespace App\Domain;

use Danilocgsilva\ClassToSqlSchemaScript\SpitterInterface;

class QueryGenerator
{
    private SpitterInterface $spitterImplementation;
    
    public function setSpitter(SpitterInterface $spitterImplementation): self
    {
        $this->spitterImplementation = $spitterImplementation;
        return $this;
    }

    public function getString(): string
    {
        return $this->spitterImplementation->getScript();
    }
}
