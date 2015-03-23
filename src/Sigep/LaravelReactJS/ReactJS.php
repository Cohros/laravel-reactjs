<?php

namespace Sigep\LaravelReactJS;

/**
 * Based on ReactJS from Facebook Inc.
 * https://github.com/reactjs/react-php-v8js
 */
use Illuminate\Foundation\Application;

class ReactJS
{
    /**
     * @var Application 
     */
    private $app = null;

    /**
     * @var \V8Js
     */
    private $v8 = null;

    /**
     * JavaScript source files concatenated
     * @var string
     */
    private $src = '';
    
    /**
     * Component name
     * @var string
     */
    private $component = '';
    
    /**
     * Component data
     * @var array
     */
    private $data = [];

    /**
     * Prefix to access React library
     * @var string
     */
    private $react_prefix = '';

    /**
     * Prefix to access Components
     * @var string
     */
    private $components_prefix = '';

    /**
     * Basepath of js source files
     * @var string
     */
    private $basepath = '';

    /**
     * Function to be executed when error occurs
     * @var function
     */
    private $errorHandler = null;

    /**
     * List of source files (including React)
     * @var array
     */
    private $src_files = [];

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct($app)
    {
        $this->v8 = new \V8Js();

        $this->app = $app;
        $this->basepath = $this->app['config']->get('reactjs::basepath');
        $this->react_src = $this->app['config']->get('reactjs::react_src');
        $this->src_files = $this->app['config']->get('reactjs::src_files');
        $this->react_prefix = $this->app['config']->get('reactjs::react_prefix');
        $this->components_prefix = $this->app['config']->get('reactjs::components_prefix');

        $this->checkFiles();
        $this->prepare();
    }

    /**
     * Checks if all source files exists
     * @throws \Exception
     */
    private function checkFiles()
    {
        if ($this->react_src && !file_exists($this->basepath . $this->react_src)) {
            throw new \Exception('React source file not found (' . $this->basepath . $this->react_src . ')');
        }

        foreach ($this->src_files as $file) {
            if (!file_exists($this->basepath . $file)) {
                throw new \Exception('Source file not found (' . $this->basepath . $file . ')');
            }
        }
    }

    /**
     * Concatenate source files and create a environment to run user code
     */
    private function prepare()
    {
        $this->src = [];
        $this->src[] = 'var console = {warn: function(){}, error: print, log: print}';
        $this->src[] = 'var window = {}';

        if ($this->react_src) {
            $this->src[] = file_get_contents($this->basepath . $this->react_src);
            $this->src[] = 'var React = window.React';
        }

        foreach ($this->src_files as $path) {
            $this->src[] = file_get_contents($this->basepath . $path);
        }

        $this->src = implode(";\n", $this->src);

        if ($this->react_prefix) {
            $this->react_prefix = "window.{$this->react_prefix}.";
        }

        if ($this->components_prefix) {
            $this->components_prefix = "window.{$this->components_prefix}.";
        }
    }

    /**
     * Setup error handler
     * This function will be executed when errors occurs
     * @param callable $errorHandler
     */
    public function setErrorHandler(callable $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    /**
     * Get and/or set component name
     * @param string $componentName
     * @return string
     */
    public function component($componentName = null)
    {
        if ($componentName && is_string($componentName)) {
            $this->component = $this->components_prefix . $componentName;
        }
        
        return $this->component;
    }
    
    /**
     * Get and/or set component data
     * @param array $data
     * @return mixed
     */
    public function data($data = null)
    {
        if (is_array($data)) {
            $this->data = $data;
        }
        
        return $this->data;
    }
    
    /**
     * Get markup string
     * If an error occurs, the error handler will be executed if exists, won't do anything otherwise
     * @return string
     */
    public function markup()
    {
        $react = $this->react_prefix . 'React';
        $component = $this->component;

        $code = $this->src;
        $code .= "var componentFactory = $react.createFactory($component);";

        $code .= sprintf(
            "$react.renderToString(componentFactory(%s));",
            json_encode($this->data)
        );

        try {
            return $this->v8->executeString($code);
        } catch (\Exception $e) {
            if (is_callable($this->errorHandler)) {
                call_user_func($this->errorHandler, $e->getMessage(), $code);
            }

            return '';
        }
    }
    
    /**
     * Get js markup to call renderComponent
     * @param string $element selector to wrapper element (will be used with document.querySelector())
     * @param string $return_var if a name is provided. assigns the component to a JavaScript variable with that name
     * @return string
     */
    public function js($element, $return_var = null)
    {
        $react = $this->react_prefix . 'React';
        $component = $this->component;
        $element = 'document.querySelector("' . $element . '")';

        $js = "var componentFactory = $react.createFactory($component);";
        $js .= ($return_var ? "var $return_var = " : '');
        $js .= sprintf(
            "$react.render(componentFactory(%s), %s);",
            json_encode($this->data),
            $element
        );

        return $js;
    }
}
