<?php

namespace Sigep\LaravelReactJS;

use Illuminate\Support\Facades\Facade;

class ReactJSFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'reactjs';
    }
}
