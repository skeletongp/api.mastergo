<?php

namespace App\Console\Commands;

use DirectoryIterator;
use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Illuminate\View\Compilers\CompilerInterface;

class ViewCompileCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'view:compile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile and cache specified views';

    /**
     * @var Application
     */
    private $app;

    /**
     * @var CompilerInterface
     */
    private $compiler;

    /**
     * @var string
     */
    private $viewPath;

    /**
     * Create a new config clear command instance.
     *
     * @param Application $app
     */
    public function __construct(
        Application $app
    )
    {
        parent::__construct();

        $this->app = $app;
        $this->compiler = $this->app->make('blade.compiler');
        $this->viewPath = config('view')['paths'][0];
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->processDirectoryOfViews($this->viewPath);
        $this->info('Views were compiled and cached!');
    }

    /**
     * @param string $realPath
     */
    protected function processDirectoryOfViews(string $realPath)
    {
        foreach (new DirectoryIterator($realPath) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            if ($this->shouldExclude($fileInfo->getRealPath())) {
                continue;
            }

            if ($fileInfo->isFile()) {
                if ($fileInfo->getExtension() !== 'php') {
                    continue;
                }

                $this->compiler->compile($fileInfo->getRealPath());
                $this->info('Compiled: ' . $fileInfo->getRealPath());
                $this->info('Output: ' . $this->compiler->getCompiledPath($fileInfo->getRealPath()));
            } else {
                $this->processDirectoryOfViews($fileInfo->getRealPath());
            }
        }
    }

    /**
     * @param string $realPath
     * @return bool
     */
    protected function shouldExclude(string $realPath) : bool
    {
        $toBeExcludes = [
            realpath($this->viewPath . '/vendor/notifications')
        ];

        return in_array($realPath, $toBeExcludes);
    }
}
