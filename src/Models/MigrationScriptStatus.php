<?php

namespace Rhubarb\Scaffolds\DatabaseMigrations\Models;

use Rhubarb\Stem\Models\Model;
use Rhubarb\Stem\Repositories\MySql\Schema\Columns\MySqlEnumColumn;
use Rhubarb\Stem\Schema\Columns\AutoIncrementColumn;
use Rhubarb\Stem\Schema\Columns\BooleanColumn;
use Rhubarb\Stem\Schema\Columns\IntegerColumn;
use Rhubarb\Stem\Schema\Columns\LongStringColumn;
use Rhubarb\Stem\Schema\ModelSchema;

/**
 * Class MigrationScriptStatus
 *
 * @property int    $id
 * @property string $class
 * @property int    $version
 * @property int    $priority
 * @property bool   $complete
 * @property string $status
 * @property string $message
 *
 * @package Rhubarb\Scaffolds\DatabaseMigrations\Models
 */
class MigrationScriptStatus extends Model
{
    const VERSION = 1;

    const
        FIELD_ID = 'id',
        FIELD_CLASS = 'class',
        FIELD_VERSION = 'version',
        FIELD_STATUS = 'status',
        FIELD_MESSAGE = 'message';

    const
        STATUS_PENDING = 'pending',
        STATUS_SUCCESSFUL = 'successful',
        STATUS_ERROR = 'error',
        STATUSES =
        [
            self::STATUS_PENDING,
            self::STATUS_SUCCESSFUL,
            self::STATUS_ERROR,
        ];

    protected function createSchema()
    {
        $schema = new ModelSchema('tblMigrationsScript');

        $schema->addColumn(
            new AutoIncrementColumn(self::FIELD_ID),
            new LongStringColumn(self::FIELD_CLASS),
            new IntegerColumn(self::FIELD_VERSION),
            new MySqlEnumColumn(self::FIELD_STATUS, self::STATUS_PENDING, self::STATUSES),
            new LongStringColumn(self::FIELD_MESSAGE)
        );

        return $schema;
    }
}
