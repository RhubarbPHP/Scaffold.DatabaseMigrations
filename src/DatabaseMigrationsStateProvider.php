<?php


namespace Rhubarb\Scaffolds\Migrations;


use Rhubarb\Modules\Migrations\MigrationsStateProvider;
use Rhubarb\Modules\Migrations\UseCases\MigrationEntity;
use Rhubarb\Scaffolds\ApplicationSettings\Settings\ApplicationSettings;

class DatabaseMigrationsStateProvider extends MigrationsStateProvider
{
    /**
     * @return int
     */
    public function getLocalVersion(): int
    {
        if (isset($this->localVersion)) {
            return $this->localVersion;
        }
        return $this->localVersion = $this->getApplicationSettings()->localVersion;
    }

    /**
     * @param int $newLocalVersion
     */
    public function setLocalVersion(int $newLocalVersion): void
    {
        $this->getApplicationSettings()->localVersion = $this->localVersion = $newLocalVersion;
    }

    /**
     * @return ApplicationSettings
     */
    protected function getApplicationSettings(): ApplicationSettings
    {
        return ApplicationSettings::singleton();
    }

    /**
     * Updates the Start, End and Priority points on the Migration Entity to change which scripts get ran.
     *
     * @param MigrationEntity $entity
     */
    public function applyResumePoint(MigrationEntity $entity): void
    {
        // No default behaviour, nor a demand that it be implemented.
    }

    /**
     * @param MigrationScriptInterface $failingScript
     */
    public function storeResumePoint(MigrationScriptInterface $failingScript) {

    }
}