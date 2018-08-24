<?php


namespace Rhubarb\Scaffolds\Migrations;


use Rhubarb\Modules\Migrations\MigrationsStateProvider;
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
}