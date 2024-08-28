<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeTransformer extends Command
{
    protected $signature = 'module:make-transformer {name} {module} {--model=Model}';
    protected $description = 'Create a new transformer for a specified module';

    public function handle()
    {
        $module = $this->argument('module');
        $name = $this->argument('name');
        $model = $this->option('model');
        $modulePath = base_path("Modules\\{$module}\\Transformers");
        $className = $name.'Transformer';

        // if (!is_dir($modulePath)) {
        //     $this->error("Module directory does not exist: {$modulePath}");
        //     return 1;
        // }
        if (!is_dir($modulePath)) {
            mkdir($modulePath, 0755, true);
            $this->info("Directory created: {$modulePath}");
        }

        $path = "{$modulePath}/{$className}.php";
        if (file_exists($path)) {
            $this->error('Transformer already exists!');
            return 1;
        }

        
        $useModel = '';
        if ($model != 'Model') {
            $useModel = "use Modules\\{$module}\\Entities\\{$model};";
            $model = $model.' ';
        }
        else
        {
            $model = '';
        }
        $stub = __DIR__ . '\\Stubs\\transformer.stub';
        $stubContent = file_get_contents($stub);
        $stubContent = str_replace(
            ['{{ class }}', '{{ namespace }}', '{{ item }}', '{{ useModel }}','{{ model }}'],
            [$className, "Modules\\{$module}\\Transformers", lcfirst($name) , $useModel, $model],
            $stubContent
        );

        file_put_contents($path, $stubContent);
        $this->info('Transformer created successfully.');

        return 0;
    }
}
