Stem Migrations Scaffold
========================

This scaffold is a stem-specific implementation of [the Migrations module.](https://github.com/RhubarbPHP/Module.Migrations) It includes:

`DatabaseMigrationsStateProvider` which stores the local state of your application using Stem.

`DataMigrationScript` an implementation of MigrationScriptInterface with additional helper methods to perform common data migrations. 

## Registering your script

Scripts will not be ran unless they are registered. Scripts can be registered by calling `registerMigrationScripts($scriptsArray)` on MigrationsModule.  

~~~php
   MigrationsManager::getMigrationsManager()->registerMigrationScripts([
       SplitNameColumnScript::class,
       DeleteAllImagesScript::class,
       UpdatedGdprInfoScript::class
   ]);
~~~

## Data Migration Scripts

DataMigrationScripts implement MigrationsScriptInterface and includes common migration types that can be called during execution of your script. 

~~~php
class ContactNameSplitting extends DataMigrationScript
{
        public function execute()
        {
            foreach (Image::all(new Equals('active', false)) as $image) {
                unlink($image->filePath);
                $image->delete();
            }
            
            try {
                $this->updateEnumOption(
                    User::class,
                    'status',
                    'on line',
                    'online'
                );
            } catch (\Rhubarb\Crown\Exceptions\ImplementationException $e) {
            }
        }
    
        public function version(): int
        {
            return 18;
        }

        public function priority(): int
        {
            return 10;
        }
}
~~~
