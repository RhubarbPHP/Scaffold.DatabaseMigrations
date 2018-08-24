<?php


namespace Rhubarb\Scaffolds\DatabaseMigrations\UseCases;


use Rhubarb\Scaffolds\DatabaseMigrations\MigrationsManager;
use Rhubarb\Scaffolds\DatabaseMigrations\MigrationsSettings;

class MigrationEntity
{
    /** @var int $targetVersion */
    public $targetVersion;
    /** @var int $localVersion */
    public $localVersion;
    /** @var string $resumeScript */
    public $resumeScript;
    /** @var string[] $skipScripts */
    public $skipScripts = [];

    public function __construct()
    {
        $this->localVersion = MigrationsSettings::singleton()->getLocalVersion();
    }
}