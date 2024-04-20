<?php declare(strict_types=1);

namespace NathanBarrett\LaravelRepositories\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;

class MakeRepositoryCommand extends Command
{
    public $signature = 'make:repository {name} {--model=}';

    public $description = 'Creates a new repository for your model.';

    public function handle(): int
    {
        $repositoryNamePath = $this->argument('name');

        $repositoryBaseName = Str::contains($repositoryNamePath, '/') ?
            Str::afterLast($repositoryNamePath, '/') : $repositoryNamePath;

        $modelName = $this->option('model') ?? Str::replaceLast('Repository', '', $repositoryBaseName);

        $modelFQDN = $this->getModelFullQualifiedDomainName($modelName);

        $repositoryBasePath = $this->repositoryBasePath($repositoryNamePath);

        $repositoryNamespace = $this->guessRepositoryNamespace($repositoryBasePath);

        $fileContents = $this->getFileContents($repositoryNamespace, $repositoryBaseName, $modelFQDN);

        $repositoryPath = $repositoryBasePath . '/' . $repositoryBaseName . '.php';

        File::ensureDirectoryExists($repositoryBasePath);

        File::put($repositoryPath, $fileContents);

        return self::SUCCESS;
    }

    private function guessRepositoryNamespace(string $repositoryBasePath): string
    {
        $appPath = trim(Str::replace(base_path(), '', $repositoryBasePath), '/');

        /** @var string $repoNameSpace */
        $repoNameSpace = collect(explode('/', $appPath))
            ->reduce(function (string $namespace, string $segment) {
                if ($namespace === 'app') {
                    return Str::replaceFirst('\\', '', app()->getNamespace());
                }
                $segment = Str::studly($segment);
                return $namespace ? $namespace . '\\' . $segment : $segment;
            }, '');

        return $repoNameSpace;
    }

    private function repositoryBasePath(string $repositoryNamePath): string
    {
        if (Str::contains($repositoryNamePath, '/')) {
            $baseName = Str::afterLast($repositoryNamePath, '/');
            $path = Str::replaceLast('/' . $baseName, '', $repositoryNamePath);
            $path = Str::replaceMatches('/^\.?\//', '', $path);
            return Str::startsWith($repositoryNamePath, './') ? base_path($path) : app_path($path);
        }

        return app_path("Repositories");
    }

    private function getModelFullQualifiedDomainName(string $modelName): string
    {
        $model = $this->getModels()
            ->first(function (string $model) use ($modelName) {
                return Str::endsWith($model, $modelName);
            });

        if (!$model) {
            return sprintf('%sModels\%s', app()->getNamespace(), $modelName);
        }

        return Str::replaceFirst("\\", "", $model);
    }

    private function getModels(): Collection
    {
        $models = collect(File::allFiles(app_path()))
            ->map(function (SplFileInfo $item): string {
                $path = $item->getRelativePathName();
                $class = sprintf('\%s%s',
                    app()->getNamespace(),
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\'));

                return $class;
            })
            ->filter(function (string $class) {
                $valid = false;

                if (class_exists($class)) {
                    $reflection = new \ReflectionClass($class);
                    $valid = $reflection->isSubclassOf(Model::class) &&
                        !$reflection->isAbstract();
                }

                return $valid;
            });

        return $models->values();
    }

    private function getFileContents(
        string $repositoryNamespace,
        string $repositoryBaseName,
        string $modelFQDN,
    ): string
    {
        $parts = collect(explode('\\', $modelFQDN));
        $modeBaseName = $parts->last();
        return Str::of($this->stubContent())
            ->replace('{{namespace}}', $repositoryNamespace)
            ->replace('{{repositoryBaseName}}', $repositoryBaseName)
            ->replace('{{modelFQDN}}', $modelFQDN)
            ->replace('{{model}}', $modeBaseName)
            ->toString();
    }

    /**
     * @throws FileNotFoundException
     */
    private function stubContent(): string
    {
        return File::get(__DIR__ . '/stubs/repository.stub');
    }
}
