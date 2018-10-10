<?php

namespace Rhubarb\Scaffolds\DatabaseMigrations\Models;

use Rhubarb\Stem\Schema\SolutionSchema;

class DatabaseMigrationsSolutionSchema extends SolutionSchema
{
    public function __construct($version = 0)
    {
        parent::__construct($version);

        $this->addModel("MigrationScriptStatus", MigrationScriptStatus::class, 1);
    }
}