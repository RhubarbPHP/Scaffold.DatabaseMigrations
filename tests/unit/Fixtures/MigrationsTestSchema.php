<?php


namespace Rhubarb\Scaffolds\DatabaseMigrations\Tests\Fixtures;


use Rhubarb\Stem\Schema\SolutionSchema;

class MigrationsTestSchema extends SolutionSchema
{
    public function __construct(int $version = 0)
    {
        parent::__construct($version);

        $this->addModel(TestUser::class, TestUser::VERSION);
    }
}