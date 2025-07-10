<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class CreateDomainCommand extends GeneratorCommand
{
    protected $name = 'make:domain';
    protected $description = 'Create a new domain with its complete structure and related files';
    protected $type = 'Domain';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $domainName = Str::studly(Str::singular($this->argument('name')));

        if ($this->files->isDirectory(app_path('Domain/' . $domainName))) {
            $this->error($this->type . ' already exists!');
            return false;
        }

        $this->info("Creating domain [{$domainName}]...");
        $this->createStructure($domainName);
        $this->info("Domain [{$domainName}] created successfully!");
        $this->warn("Don't forget to include 'routes/{$domainName}/api.php' in your main route file.");
        $this->warn("And register the '{$domainName}Seeder' in your DatabaseSeeder.");
    }

    /**
     * Create the full directory and file structure by layer.
     */
    protected function createStructure(string $domainName)
    {
        $structures = $this->getStructureDefinitions($domainName);

        foreach ($structures as $layerName => $structure) {
            $this->comment("Creating {$layerName} Layer...");
            foreach ($structure as $path => $files) {
                foreach ($files as $fileName => $stubType) {
                    $this->generateFile($path, $fileName, $stubType, $domainName);
                }
            }
        }
    }

    /**
     * Get all structure definitions for the domain.
     */
    protected function getStructureDefinitions(string $domainName): array
    {
        $domainPrefix = "Domain/{$domainName}";
        $httpPrefix = "Http";
        $dbPrefix = "database";
        $routesPrefix = "routes";

        return [
            'Domain' => [
                "{$domainPrefix}/Actions" => ['Create' . $domainName . 'Action' => 'action', 'Update' . $domainName . 'Action' => 'action', 'Delete' . $domainName . 'Action' => 'action'],
                "{$domainPrefix}/DataTransferObjects" => [$domainName . 'Data' => 'dto'],
                "{$domainPrefix}/Models" => [$domainName => 'model'],
                "{$domainPrefix}/Repositories" => [$domainName . 'Repository' => 'repository', 'Eloquent' . $domainName . 'Repository' => 'repository.eloquent'],
                "{$domainPrefix}/Services" => [$domainName . 'Service' => 'service'],
            ],
            'Application' => [
                "{$httpPrefix}/Controllers/Api/V1/{$domainName}" => [$domainName . 'Controller' => 'controller.api'],
                "{$httpPrefix}/Requests/{$domainName}" => ['Store' . $domainName . 'Request' => 'request', 'Update' . $domainName . 'Request' => 'request'],
                "{$httpPrefix}/Resources/{$domainName}" => [$domainName . 'Resource' => 'resource', $domainName . 'Collection' => 'resource.collection'],
            ],
            'Database' => [
                "{$dbPrefix}/factories/{$domainName}" => [$domainName . 'Factory' => 'factory'],
                "{$dbPrefix}/seeders/{$domainName}" => [$domainName . 'Seeder' => 'seeder'],
            ],
            'Route' => [
                "{$routesPrefix}/{$domainName}" => ['api' => 'routes'],
            ],
        ];
    }

    /**
     * Generate a file from a stub. This is the core logic.
     */
    protected function generateFile(string $path, string $fileName, string $stubType, string $domainName)
    {
        $destinationPath = $this->getPath("{$path}/{$fileName}");

        // This is the most crucial step: Ensure the directory for the file exists.
        $this->makeDirectory($destinationPath);

        if ($this->files->exists($destinationPath)) {
            $this->error("File : {$destinationPath} already exists.");
            return;
        }

        $stubPath = $this->getStubPath($stubType);
        $stub = $this->files->get($stubPath);

        $this->files->put($destinationPath, $this->buildClassFromStub($stub, $path, $fileName, $domainName));
        $this->info("Created file: " . str_replace(base_path() . '/', '', $destinationPath));

        // After creating the model file, create its migration. This is the only Artisan call we need.
        if ($stubType === 'model') {
            $migrationName = 'create_' . Str::snake(Str::plural($domainName)) . '_table';
            $this->call('make:migration', ['name' => $migrationName]);
        }
    }

    /**
     * Build the class with the given name from a stub by replacing placeholders.
     */
    protected function buildClassFromStub(string $stub, string $path, string $fileName, string $domainName): string
    {
        $namespace = '';
        if (Str::startsWith($path, 'database/')) {
            $namespace = 'Database\\' . str_replace('/', '\\', Str::after($path, 'database/'));
        } elseif (!Str::startsWith($path, 'routes/')) {
            $namespace = 'App\\' . str_replace('/', '\\', $path);
        }

        // FQCN: Fully Qualified Class Name for linking models and factories
        $modelClass = "App\\Domain\\{$domainName}\\Models\\{$domainName}";
        $factoryClass = "Database\\factories\\{$domainName}\\{$domainName}Factory";
        $modelNamePluralKebab = Str::kebab(Str::plural($domainName));

        $replacements = [
            '{{ namespace }}' => rtrim($namespace, '\\'),
            '{{ class }}' => $fileName,
            '{{ domainName }}' => $domainName,
            '{{ modelName }}' => $domainName,
            '{{ modelNamePluralKebab }}' => $modelNamePluralKebab,
            '{{ modelClass }}' => $modelClass,
            '{{ factoryClass }}' => $factoryClass,
        ];

        if (Str::contains($fileName, 'Eloquent')) {
            $replacements['{{ interfaceName }}'] = str_replace('Eloquent', '', $fileName);
        }

        return str_replace(array_keys($replacements), array_values($replacements), $stub);
    }

    /**
     * Override the parent method to ensure the directory for a file path exists.
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0755, true, true);
        }
        return $path;
    }

    /**
     * Get the full path to the file.
     */
    protected function getPath($name)
    {
        if (Str::startsWith($name, ['database/', 'routes/'])) {
            return base_path($name . '.php');
        }
        $name = Str::replaceFirst('App\\', '', $name);
        return $this->laravel['path'] . '/' . str_replace('\\', '/', $name) . '.php';
    }

    /**
     * Required by GeneratorCommand.
     */
    protected function getStub() { return ''; }

    /**
     * Get the path to the stub file.
     */
    protected function getStubPath(string $stubType): string
    {
        return base_path("stubs/{$stubType}.stub");
    }

    /**
     * Define the command's arguments.
     */
    protected function getArguments()
    {
        return [['name', InputArgument::REQUIRED, 'The name of the domain.']];
    }
}
