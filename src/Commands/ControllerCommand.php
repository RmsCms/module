<?php

namespace Module\Commands;


use Symfony\Component\Console\Input\InputArgument;

class ControllerCommand extends ModuleCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Controller for module';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {

        return __DIR__.'/stubs/controller.stub';
    }
    /**
     * @var string controller
     */
    protected $controller_name;
    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Modules';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [

        ];
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $namespace = $this->getNamespace($name);

        return str_replace("use $namespace\Modules;\n", '', parent::buildClass($name));
    }
    public function fire()
    {
        $inputAuthor = trim($this->ask('Name of author?'));
        $inputName = trim($this->ask('Name of module ?'));
        $controller = trim($this->ask('Name of controller'));
        $this->controller_name = $inputAuthor.'/'.$inputName.'/Controllers/'.$controller;
        $name = $this->parseName($this->controller_name);

        $path = $this->getPath($name);

        if ($this->alreadyExists($this->controller_name)) {
            $this->error($this->type.' already exists!');

            return false;
        }

        $this->makeDirectory($path);
        // create Module.php file
        $this->files->put($path, $this->buildClass($name));
        $this->info($this->type.' created successfully.');
    }
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::OPTIONAL, 'The name of the module'],
            ['author', InputArgument::OPTIONAL, 'The name of the author'],
            ['controller', InputArgument::OPTIONAL, 'The name of the controller'],
        ];
    }
}
