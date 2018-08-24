<?php


namespace Rhubarb\Scaffolds\DatabaseMigrations\Tests\Fixtures;


use Rhubarb\Crown\Tests\Fixtures\TestCases\RhubarbTestCase;
use Rhubarb\Scaffolds\DatabaseMigrations\MigrationsManager;
use Rhubarb\Scaffolds\DatabaseMigrations\DatabaseMigrationsModule;
use Rhubarb\Scaffolds\DatabaseMigrations\MigrationsSettings;
use Rhubarb\Stem\Models\Model;
use Rhubarb\Stem\Repositories\Offline\Offline;
use Rhubarb\Stem\Repositories\Repository;
use Rhubarb\Stem\Schema\SolutionSchema;

class MigrationsTestCase extends RhubarbTestCase
{
    protected $manager;

    protected $settings;

    protected function setUp()
    {
        $parent = parent::setUp();

        $this->application->registerModule(new DatabaseMigrationsModule());
        $this->application->initialiseModules();

        Repository::setDefaultRepositoryClassName(Offline::class);
        Model::deleteRepositories();
        SolutionSchema::registerSchema("Schema", MigrationsTestSchema::class);

        MigrationsManager::registerMigrationManager(TestMigrationsManager::class);
        $this->manager = MigrationsManager::getMigrationsManager();
        $this->settings = MigrationsSettings::singleton();

        $this->settings->pageSize = 100;
        $this->settings->repositoryType = Offline::class;

        return $parent;
    }
}