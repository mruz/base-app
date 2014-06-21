<?php

class RoutingTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider routeProvider
     */
    public function testMatching($pattern, $expected)
    {
        $router = \Phalcon\DI::getDefault()->getShared('router');
        $router->handle($pattern);

        if ($router->wasMatched()) {
            $this->assertEquals($expected, [$router->getModuleName(), $router->getControllerName(), $router->getActionName(), $router->getParams()], $pattern);
        } else {
            $this->assertEquals($expected, null, "The route wasn't matched by any route");
        }
    }

    public function routeProvider()
    {
        /*
         * pattern, [module, controler, action, [params]]
         */
        return [
            ['/', ['frontend', 'index', 'index', []]],
            ['/index', ['frontend', 'index', 'index', []]],
            ['/index/index', ['frontend', 'index', 'index', []]],
            ['/index/test', ['frontend', 'index', 'test', []]],
            
            ['/user', ['frontend', 'user', 'index', []]],
            ['/user/signup', ['frontend', 'user', 'signup', []]],
            
            ['/admin', ['backend', 'index', 'index', []]],
            ['/admin/', ['backend', 'index', 'index', []]],
            ['/admin/index', ['backend', 'index', 'index', []]],
            ['/admin/index/index', ['backend', 'index', 'index', []]],
            ['/admin/index/test', ['backend', 'index', 'test', []]],
            
            ['/admin/clients', ['backend', 'clients', 'index', []]],
            ['/admin/clients/add', ['backend', 'clients', 'add', []]],
            ['/admin/clients/details/2', ['backend', 'clients', 'details', [0 => 2]]],
            
            ['/doc', ['doc', 'index', 'index', []]],
            ['/doc/index', ['doc', 'index', 'index', []]],
            ['/doc/index/index', ['doc', 'index', 'index', []]],
            ['/doc/index/test', ['doc', 'index', 'test', []]],
            
            ['/doc/install', ['doc', 'install', 'index', []]],
            ['/doc/install/requirements', ['doc', 'install', 'requirements', []]],
            ['/doc/install/requirements/php', ['doc', 'install', 'requirements', [0 => 'php']]],
            
            ['/user/', ['frontend', 'user', 'index', []]],
            ['/user/signup/', ['frontend', 'user', 'signup', []]],
            
            ['/buy', ['frontend', 'static', 'buy', []]],
            ['/contact', ['frontend', 'static', 'contact', []]],
            ['/devices/3', ['frontend', 'devices', 'index', ['id' => 3]]],
            ['/admin/devices/3', ['backend', 'devices', 'index', ['id' => 3]]],
        ];
    }

}
