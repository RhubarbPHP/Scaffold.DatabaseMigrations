<?php


namespace Rhubarb\Scaffolds\DatabaseMigrations;


use Rhubarb\Modules\Migrations\Interfaces\MigrationScriptInterface;
use Rhubarb\Modules\Migrations\MigrationsStateProvider;
use Rhubarb\Scaffolds\ApplicationSettings\Settings\ApplicationSettings;
use Rhubarb\Scaffolds\DatabaseMigrations\Models\MigrationScriptStatus;
use Rhubarb\Stem\Exceptions\RecordNotFoundException;
use Rhubarb\Stem\Filters\Equals;

class DatabaseMigrationsStateProvider extends MigrationsStateProvider
{
    private $pageSize;

    /**
     * @return int
     */
    public function getLocalVersion(): int
    {
        return $this->localVersion = $this->getApplicationSettings()->localVersion;
    }

    /**
     * @param int $newLocalVersion
     */
    public function setLocalVersion(int $newLocalVersion): void
    {
        $this->getApplicationSettings()->localVersion = $this->localVersion = $newLocalVersion;
    }

    public function getDataMigrationsPageSize(): int
    {
        if ($this->pageSize) {
            return $this->pageSize;
        }
        return $this->pageSize = ($this->getApplicationSettings()->PageSize ?? 100);
    }

    public function setDataMigrationsPageSize(int $pageSize): void
    {
        $this->getApplicationSettings()->DataMigrationsPageSize = $pageSize;
    }

    /**
     * @return ApplicationSettings
     */
    protected function getApplicationSettings(): ApplicationSettings
    {
        return ApplicationSettings::singleton();
    }

    /**
     * Locally stores a MigrationScript as having been successfully executed.
     *
     * @param MigrationScriptInterface $migrationScript
     */
    public function markScriptCompleted(MigrationScriptInterface $migrationScript): void
    {
        $script = $this->getMigrationScriptStatus($migrationScript);
        $script[MigrationScriptStatus::FIELD_STATUS] = MigrationScriptStatus::STATUS_SUCCESSFUL;
        $script->save();
    }

    /**
     * Checks if a migration script has already been successfully executed locally.
     *
     * @param string $className
     * @return bool
     */
    public function isScriptComplete(string $className): bool
    {
        if (class_exists($className)) {
            $script = $this->getMigrationScriptStatus(new $className());
            return ($script->status == MigrationScriptStatus::STATUS_SUCCESSFUL) ? true : false;
        };
        return false;
    }

    /**
     * @param MigrationScriptInterface $migrationScript
     * @return MigrationScriptStatus
     */
    private function getMigrationScriptStatus(MigrationScriptInterface $migrationScript): MigrationScriptStatus
    {
        $class = get_class($migrationScript);
        try {
            $script = MigrationScriptStatus::findFirst(new Equals(MigrationScriptStatus::FIELD_CLASS, $class));
        } catch (RecordNotFoundException $e) {
            $script = new MigrationScriptStatus();
            $script[MigrationScriptStatus::FIELD_CLASS] = $class;
            $script[MigrationScriptStatus::FIELD_VERSION] = $migrationScript->version();
            $script->save();
        }
        return $script;
    }

    /**
     * Returns all migration scripts which have been run on the local application.
     *
     * @return array
     */
    public function getCompletedScripts(): array
    {
        return MigrationScriptStatus::find(
            new Equals(
                MigrationScriptStatus::FIELD_STATUS,
                MigrationScriptStatus::STATUS_SUCCESSFUL
            )
        );
    }
}