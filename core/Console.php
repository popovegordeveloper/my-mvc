<?php

require __DIR__ . '/function.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\Yaml\Yaml;

class Console
{
    private $root_path;
    private $config_database;
    private $capsule;
    private $created_migrations;

    public function __construct()
    {
        $this->root_path = str_replace('\core','', __DIR__);
        require $this->root_path . '/vendor/autoload.php';
        $this->config_database = Yaml::parseFile("$this->root_path/config.yaml")['database'];
        $this->capsule = new Capsule;
        $this->capsule->addConnection($this->config_database);
        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();
    }

    public function migrate($migration = null)
    {
        $this->created_migrations = collect();
        if (Capsule::schema()->hasTable('migrations')){
            $this->created_migrations = Capsule::table('migrations')->get();
        }
        if ($migration == null) {
            $migrations = get_files('/database/migrations');
            foreach ($migrations as $migration) {
                $this->creteMigrationOrFail($migration);
            }
        } else {
            $this->creteMigrationOrFail($migration);
        }
    }

    private function creteMigrationOrFail($migration)
    {
        if ($this->created_migrations->where('name', $migration)->first() == null) {
            require "$this->root_path/database/migrations/$migration";
            Capsule::table('migrations')->insert(['name' => $migration]);
            echo "Migration $migration was created\r\n";
        }
    }
}