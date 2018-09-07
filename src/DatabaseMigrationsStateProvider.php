<?php


namespace Rhubarb\Scaffolds\DatabaseMigrations;


use Rhubarb\Modules\Migrations\Interfaces\MigrationScriptInterface;
use Rhubarb\Modules\Migrations\MigrationsStateProvider;
use Rhubarb\Modules\Migrations\UseCases\MigrationEntity;
use Rhubarb\Scaffolds\ApplicationSettings\Settings\ApplicationSettings;
use Rhubarb\Scaffolds\DatabaseMigrations\Models\MigrationScriptStatus;
use Rhubarb\Stem\Filters\Equals;
use Rhubarb\Stem\Filters\GreaterThan;
use Rhubarb\Stem\Filters\LessThan;

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

    public function getDataMigrationsPageSize(): int {
        if ($this->pageSize) {
            return $this->pageSize;
        }
        return $this->pageSize = ($this->getApplicationSettings()->PageSize ?? 100);
    }

    public function setDataMigrationsPageSize(int $pageSize): void {
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
     * Updates the Start, End and Priority points on the Migration Entity to change which scripts get ran.
     *
     * @param MigrationEntity $entity
     */
    public function applyResumePoint(MigrationEntity $entity): void
    {
        $filters = [];

        $filers[] = new Equals(MigrationScriptStatus::FIELD_COMPLETE, false);
        $filers[] = new Equals(MigrationScriptStatus::FIELD_STATUS, MigrationScriptStatus::STATUS_ERROR);

        if ($entity->startVersion) {
            $filters[] = new GreaterThan(MigrationScriptStatus::FIELD_VERSION, $entity->startVersion);
        }

        if ($entity->startPriority) {
            $filters[] = new GreaterThan(MigrationScriptStatus::FIELD_PRIORITY, $entity->startPriority);
        }

        if ($entity->endVersion) {
            $filters[] = new LessThan(MigrationScriptStatus::FIELD_VERSION, $entity->endVersion);
        }

        if ($entity->endPriority) {
            $filters[] = new LessThan(MigrationScriptStatus::FIELD_PRIORITY, $entity->endPriority);
        }

        $resumeScriptModel = MigrationScriptStatus::find(...$filters);
        if ($resumeScriptModel) {
            /** @var MigrationScriptInterface $resumeScript */
            $resumeScript = new $resumeScriptModel[MigrationScriptStatus::FIELD_CLASS]();
            if (is_a($resumeScript, MigrationScriptInterface::class)) {
                $entity->startVersion = $resumeScript->version();
                $entity->startPriority = $resumeScript->priority();
            }
        }
    }

    public function storeResumePoint(MigrationScriptInterface $failingScript)
    {
        $scriptRow =
            MigrationScriptStatus::find(new Equals(MigrationScriptStatus::FIELD_CLASS, get_class($failingScript)));

        if (empty($scriptRow)) {
            $scriptRow = new MigrationScriptStatus();
            $scriptRow[MigrationScriptStatus::FIELD_CLASS] = get_class($failingScript);
            $scriptRow[MigrationScriptStatus::FIELD_VERSION] = $failingScript->version();
            $scriptRow[MigrationScriptStatus::FIELD_PRIORITY] = $failingScript->priority();
        }
        $scriptRow[MigrationScriptStatus::FIELD_STATUS] = MigrationScriptStatus::STATUS_ERROR;
        $scriptRow[MigrationScriptStatus::FIELD_MESSAGE] = 'resume';
    }
}