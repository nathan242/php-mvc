<?php

use PHPUnit\Framework\Attributes\DataProvider;
use Framework\Mvc\Router;
use Framework\Mvc\Request;

class RouterTest extends BaseTestCase
{
    protected $routerConfig = [
        'routes' => [
            'GET' => [
                '/' => ['LoginController', 'loginMethod'],
                '/main' => ['MainController', 'mainMethod'],
                '/vars/(\d+)' => ['VarController', 'varMethod']
            ],
            'POST' => [
                '/' => ['LoginController', 'loginMethod'],
                '/post_main' => ['MainController', 'mainMethod'],
                '/post_vars/(\d+)' => ['VarController', 'varMethod']
            ],
            '*' => [
                '/' => ['LoginController', 'loginMethod'],
                '/any_main' => ['MainController', 'mainMethod'],
                '/any_vars/(\d+)' => ['VarController', 'varMethod']
            ]
        ]
    ];

    protected function getRouterWithConfig(): Router
    {
        return new Router($this->routerConfig);
    }

    public static function requestValidParamsProvider(): array
    {
        return [
            ['GET', '/', [['LoginController', 'loginMethod'], []]],
            ['GET', '/main', [['MainController', 'mainMethod'], []]],
            ['GET', '/vars/123', [['VarController', 'varMethod'], [1 => '123']]],
            ['POST', '/', [['LoginController', 'loginMethod'], []]],
            ['POST', '/post_main', [['MainController', 'mainMethod'], []]],
            ['POST', '/post_vars/123', [['VarController', 'varMethod'], [1 => '123']]],
            ['PATCH', '/', [['LoginController', 'loginMethod'], []]],
            ['PATCH', '/any_main', [['MainController', 'mainMethod'], []]],
            ['PATCH', '/any_vars/123', [['VarController', 'varMethod'], [1 => '123']]]
        ];
    }

    public static function requestInvalidParamsProvider(): array
    {
        return [
            ['GET', ''],
            ['GET', '/mai'],
            ['GET', '/var/123'],
            ['POST', ''],
            ['POST', '/post_mai'],
            ['POST', '/post_var/123'],
            ['PATCH', ''],
            ['PATCH', '/any_mai'],
            ['PATCH', '/any_var/123']
        ];
    }

    #[DataProvider('requestValidParamsProvider')]
    public function testValidRoutesWithConfig(string $method, string $path, array $expect)
    {
        $router = $this->getRouterWithConfig();
        $request = new Request();

        $request->method = $method;
        $request->path = $path;

        $route = $router->process($request);

        $this->assertEquals($expect, $route, "Failed for route {$method} {$path}");
    }

    #[DataProvider('requestInvalidParamsProvider')]
    public function testInvalidRoutesWithConfig(string $method, string $path)
    {
        $router = $this->getRouterWithConfig();
        $request = new Request();

        $request->method = $method;
        $request->path = $path;

        $route = $router->process($request);

        $this->assertEquals([], $route, "Failed for route {$method} {$path}");
    }

    public function testValidRouteAddedByMethod()
    {
        $router = new Router();
        $request = new Request();

        $router->route('/', 'GET', ['IndexController', 'indexMethod']);
        $router->route('/test', 'GET', ['IndexController', 'testMethod']);

        $request->method = 'GET';
        $request->path = '/test';

        $route = $router->process($request);

        $this->assertEquals([['IndexController', 'testMethod'], []], $route, "Failed for route {$request->method} {$request->path}");
    }

    public function testInvalidRouteAddedByMethod()
    {
        $router = new Router();
        $request = new Request();

        $router->route('/', 'GET', ['IndexController', 'indexMethod']);
        $router->route('/test', 'GET', ['IndexController', 'testMethod']);

        $request->method = 'GET';
        $request->path = '/tes';

        $route = $router->process($request);

        $this->assertEquals([], $route, "Failed for route {$request->method} {$request->path}");
    }
}

