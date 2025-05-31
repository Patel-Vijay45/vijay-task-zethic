<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeServiceAndRepository extends Command
{
    protected $signature = 'make:sar {name}';
    protected $description = 'Create Service and Repository classes';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));

        $servicePath = app_path("Services/{$name}Service.php");
        $repoPath = app_path("Repositories/{$name}Repository.php");

        // Create directories if they don't exist
        if (!File::exists(app_path('Services'))) {
            File::makeDirectory(app_path('Services'));
        }
        if (!File::exists(app_path('Repositories'))) {
            File::makeDirectory(app_path('Repositories'));
        }
        $variableName = Str::camel($name);
        // Service class
        $serviceContent = "<?php
                        
namespace App\Services;

use App\Repositories\\{$name}Repository;

class {$name}Service
{ 
    public function __construct(private {$name}Repository \${$variableName}Repo)
    { 
    }

    public function getAll{$name}s(array \$conditions = [], array \$fields = [])
    {
        return \$this->{$variableName}Repo->all(\$conditions);
    }

    public function get{$name}(\$id)
    {
        return \$this->{$variableName}Repo->find(\$id);
    }

    public function create{$name}(array \$data)
    {
        
        return \$this->{$variableName}Repo->create(\$data);
    }

    public function update{$name}(\$id, array \$data)
    {
        return \$this->{$variableName}Repo->update(\$id, \$data);
    }

    public function delete{$name}(\$id)
    {
        return \$this->{$variableName}Repo->delete(\$id);
    } 

}
";
        File::put($servicePath, $serviceContent);
        $this->info("Created: Services/{$name}Service.php");

        // Repository class
        $repoContent = "<?php

namespace App\Repositories;

use App\Models\\{$name};

class {$name}Repository
{
    public function all(array \$condition = [], array \$fields = [])
    {
        return {$name}::all();
    }

    public function find(\$id)
    {
        return {$name}::findOrFail(\$id);
    }

    public function create(array \$data)
    {
        return {$name}::create(\$data);
    }

    public function update(\$id, array \$data)
    {
        \$model = \$this->find(\$id);
        \$model->update(\$data);
        return \$model;
    }

    public function delete(\$id)
    {
        \$model = \$this->find(\$id);
        return \$model->delete();
    }
}
";
        File::put($repoPath, $repoContent);
        $this->info("Created: Repositories/{$name}Repository.php");
    }
}
