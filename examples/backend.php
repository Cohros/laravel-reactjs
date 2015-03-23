<?php

require('../vendor/autoload.php');

// Mock Config
class MyConfig
{
    public function get($d)
    {
        $response = '';
        switch ($d) {
            case 'reactjs::basepath':
                $response = '/var/www/html/laravel-reactjs/examples/js/';
                break;
            case 'reactjs::src_files':
                $response = ['bundle.js'];
                break;
            case 'reactjs::react_prefix':
                $response = 'Application.libs';
                break;
            case 'reactjs::components_prefix':
                $response = 'Application.components';
                break;
        }

        return $response;
    }
}

// Mock app
$app = array (
    'config' => new MyConfig,
);

$data = array (
    'data' => array (
        'contacts' => array (
            ['nome' => 'Geremias', 'email' => 'geremias@hotmail', 'title' => 'Cabra Macho!'],
            ['nome' => 'George', 'email' => 'george@hotmail', 'title' => 'Cabrito!'],
        ),
    ),
);
$rjs = new Sigep\LaravelReactJS\ReactJS($app);
$rjs->setErrorHandler(function ($message, $code) {
    echo '<script type="text/javascript">';
    echo 'window.message = "' . $message . '";';
    echo 'console.error(window.message);';
    echo '</script>';
});


$rjs->component('ContactList');
$rjs->data($data);