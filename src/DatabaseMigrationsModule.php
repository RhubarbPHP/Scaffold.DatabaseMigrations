<?php

/*
 *	Copyright 2015 RhubarbPHP
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

namespace Rhubarb\Scaffolds\DatabaseMigrations;

use Rhubarb\Modules\Migrations\MigrationsModule;
use Rhubarb\Modules\Migrations\MigrationsStateProvider;
use Rhubarb\Scaffolds\ApplicationSettings\ApplicationSettingModule;
use Rhubarb\Scaffolds\DatabaseMigrations\Commands\GetMigrationSettingsCommand;
use Rhubarb\Scaffolds\DatabaseMigrations\Commands\MigrateCommand;
use Rhubarb\Scaffolds\DatabaseMigrations\Commands\RunMigrationScriptCommand;
use Rhubarb\Scaffolds\Migrations\DatabaseMigrationsStateProvider;

class DatabaseMigrationsModule extends MigrationsModule
{
    protected function initialise(string $databaseMigrationsStateProviderClass = DatabaseMigrationsStateProvider::class)
    {
        parent::initialise();

        MigrationsStateProvider::setProviderClassName($databaseMigrationsStateProviderClass);
    }


    public function getCustardCommands()
    {
        return
            array_merge(
                parent::getCustardCommands(),
                [
                    new MigrateCommand(),
                    new RunMigrationScriptCommand(),
                    new GetMigrationSettingsCommand()
                ]
            );
    }

    protected function getModules()
    {
        return
            array_merge(
                [
                    new ApplicationSettingModule()
                ],
                parent::getModules()
            );
    }
}