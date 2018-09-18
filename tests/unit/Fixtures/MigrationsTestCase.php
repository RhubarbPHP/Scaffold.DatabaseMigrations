<?php


namespace Rhubarb\Scaffolds\DatabaseMigrations\Tests\Fixtures;


use Rhubarb\Crown\Tests\Fixtures\TestCases\RhubarbTestCase;
use Rhubarb\Modules\Migrations\MigrationsManager;
use Rhubarb\Modules\Migrations\MigrationsModule;
use Rhubarb\Modules\Migrations\MigrationsStateProvider;
use Rhubarb\Scaffolds\DatabaseMigrations\DatabaseMigrationsStateProvider;
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

        $this->application->registerModule(new MigrationsModule(DatabaseMigrationsStateProvider::class));
        $this->application->initialiseModules();

        Repository::setDefaultRepositoryClassName(Offline::class);
        Model::deleteRepositories();
        SolutionSchema::registerSchema("Schema", MigrationsTestSchema::class);

        $this->manager = MigrationsManager::getMigrationsManager();
        $this->stateProvider = MigrationsStateProvider::getProvider();

        return $parent;
    }
}