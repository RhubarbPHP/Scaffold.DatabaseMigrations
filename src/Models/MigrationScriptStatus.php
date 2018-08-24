<?php

namespace Rhubarb\Scaffolds\DatabaseMigrations\Models;

use Rhubarb\Stem\Models\Model;
use Rhubarb\Stem\Schema\Columns\AutoIncrementColumn;
use Rhubarb\Stem\Schema\Columns\BooleanColumn;
use Rhubarb\Stem\Schema\Columns\IntegerColumn;
use Rhubarb\Stem\Schema\Columns\LongStringColumn;
use Rhubarb\Stem\Schema\ModelSchema;

class MigrationScriptStatus extends Model
{
    const VERSION = 1;

    const
        FIELD_ID = 'id',
        FIELD_CLASS = 'class',
        FIELD_VERSION = 'version',
        FIELD_COMPLETE = 'complete',
        FIELD_ERROR = 'error';

    protected function createSchema()
    {
        $schema = new ModelSchema('tblMigrationsScript');

        $schema->addColumn(
            new AutoIncrementColumn(self::FIELD_ID),
            new LongStringColumn(self::FIELD_CLASS),
            new IntegerColumn(self::FIELD_VERSION),
            new BooleanColumn(self::FIELD_COMPLETE, 0),
            new LongStringColumn(self::FIELD_ERROR)
        );

        return $schema;
    }
}
