<?php

use Illuminate\Support\Facades\Facade;

class ReactJSTest extends Orchestra\Testbench\TestCase
{
    protected function getPackageProviders()
    {
        return [
            '\Sigep\LaravelReactJS\ReactJSServiceProvider',
        ];
    }

    protected function getPackageAliases()
    {
        return [
            'ReactJS' => '\Sigep\LaravelReactJS\ReactJSFacade',
        ];
    }

    public function setUp()
    {
        parent::setUp();

        // reset configs
        Facade::clearResolvedInstance('reactjs');
        $this->app['config']->set('basepath', '');
        $this->app['config']->set('react_src', '');
        $this->app['config']->set('src_files', []);
        $this->app['config']->set('react_prefix', '');
        $this->app['config']->set('components_prefix', '');
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['path.base'] = dirname(__DIR__) . '/src';
    }

    protected function setupErrorHandling()
    {
        ReactJS::setErrorHandler(function ($message, $code) {
            throw new Exception($message);
        });
    }

    public function testShouldPassWithSeparatedSourceFiles()
    {
        $this->app['config']->set('reactjs::basepath', dirname(__FILE__));
        $this->app['config']->set('reactjs::react_src', '/js/react.min.js');
        $this->app['config']->set('reactjs::src_files', [
            '/js/app.js',
        ]);
        $this->setupErrorHandling();

        $data = ['nome' => 'Luis Henrique', 'email' => 'luish.faria@gmail.com'];
        ReactJS::component('Person');
        ReactJS::data($data);

        $doc = new DOMDocument();
        $doc->loadHTML(ReactJS::markup());

        $this->assertEquals(
            $data['nome'],
            $doc->getElementsByTagName('p')->item(0)->getElementsByTagName('span')->item(0)->textContent
        );

        $this->assertEquals(
            $data['email'],
            $doc->getElementsByTagName('p')->item(1)->getElementsByTagName('span')->item(0)->textContent
        );
    }

    /**
     * @expectedException \Exception
     */
    public function testShouldThrowExceptionWhenReactNotFound()
    {
        $this->app['config']->set('reactjs::react_src', '/xpto.js');
        $this->setupErrorHandling();

        ReactJSTest::component('Xpto');
    }

    /**
     * @expectedException \Exception
     */
    public function testShouldThrowExceptionWhenSourcesNotFound()
    {
        $this->app['config']->set('reactjs::basepath', dirname(__FILE__));
        $this->app['config']->set('reactjs::react_src', '/js/react.min.js');
        $this->app['config']->set('reactjs::src_files', [
            '/js/appp.js',
        ]);
        $this->setupErrorHandling();

        ReactJSTest::component('Xpto');
    }

    public function testShouldReturnEmptyStringWhenComponentDoesntExists()
    {
        $this->app['config']->set('reactjs::basepath', dirname(__FILE__));
        $this->app['config']->set('reactjs::react_src', '/js/react.min.js');
        $this->app['config']->set('reactjs::src_files', [
            '/js/app.js',
        ]);

        $data = ['nome' => 'Luis Henrique', 'email' => 'luish.faria@gmail.com'];
        ReactJS::component('Xpto');
        ReactJS::data($data);

        $this->assertEquals('', ReactJS::markup());
    }

    /**
     * @expectedException \Exception
     */
    public function testShouldCallErrorHandlerWhenComponentDoesntExists()
    {
        $this->app['config']->set('reactjs::basepath', dirname(__FILE__));
        $this->app['config']->set('reactjs::react_src', '/js/react.min.js');
        $this->app['config']->set('reactjs::src_files', [
            '/js/app.js',
        ]);
        $this->setupErrorHandling();

        $data = ['nome' => 'Luis Henrique', 'email' => 'luish.faria@gmail.com'];
        ReactJS::component('Xpto');
        ReactJS::data($data);
        ReactJS::markup();
    }

    public function testShouldPassWithBrowserify()
    {
        $this->app['config']->set('reactjs::basepath', dirname(__FILE__));
        $this->app['config']->set('reactjs::react_src', '');
        $this->app['config']->set('reactjs::src_files', ['/js/bundle.js']);
        $this->app['config']->set('reactjs::react_prefix', 'Application.libs');
        $this->app['config']->set('reactjs::components_prefix', 'Application.components');
        $this->setupErrorHandling();

        $data = ['nome' => 'Luis Henrique', 'email' => 'luish.faria@gmail.com'];
        ReactJS::component('Person');
        ReactJS::data($data);

        $doc = new DOMDocument();
        $doc->loadHTML(ReactJS::markup());

        $this->assertEquals(
            $data['nome'],
            $doc->getElementsByTagName('p')->item(0)->getElementsByTagName('span')->item(0)->textContent
        );

        $this->assertEquals(
            $data['email'],
            $doc->getElementsByTagName('p')->item(1)->getElementsByTagName('span')->item(0)->textContent
        );

        $selector = '.xpto';
        $jsMarkup = ReactJS::js($selector);
        $this->assertTrue((bool) strpos($jsMarkup, 'Application.libs.React.render'));
        $this->assertTrue((bool) strpos($jsMarkup, json_encode($data)));
        $this->assertTrue((bool) strpos($jsMarkup, 'document.querySelector("'.$selector.'")'));
    }
}
