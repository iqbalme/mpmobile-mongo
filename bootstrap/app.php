<?php

require_once __DIR__.'/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

// $app->withFacades();

// $app->withEloquent();

$app->withFacades(true, [
    Tymon\JWTAuth\Facades\JWTAuth::class => 'JWTAuth',
    Tymon\JWTAuth\Facades\JWTFactory::class => 'JWTFactory',
    Ixudra\Curl\Facades\Curl::class => 'Curl',
]);
$app->register(Jenssegers\Mongodb\MongodbServiceProvider::class);

$app->withEloquent();

if (env('APP_ENV') === 'local') {
    $app->bind(Illuminate\Database\ConnectionResolverInterface::class, Illuminate\Database\ConnectionResolver::class);
    $app->register(Niellles\LumenCommands\LumenCommandsServiceProvider::class);
}

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//     App\Http\Middleware\ExampleMiddleware::class
// ]);

// $app->routeMiddleware([
//     'auth' => App\Http\Middleware\Authenticate::class,
// ]);

$app->routeMiddleware([
    'auth' => App\Http\Middleware\Authenticate::class,
    'produk' => App\Http\Middleware\ProdukMiddleware::class,
    'toko' => App\Http\Middleware\TokoMiddleware::class,
    'toko.user' => App\Http\Middleware\TokoUserMiddleware::class,
    'order.seller' => App\Http\Middleware\OrderSellerMiddleware::class,
    'order.owner' => App\Http\Middleware\OrderOwnerMiddleware::class,
    // 'jwt.auth' => Tymon\JWTAuth\Middleware\GetUserFromToken::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
$app->register(App\Providers\EventServiceProvider::class);
$app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);
$app->register(Ixudra\Curl\CurlServiceProvider::class);
$app->register('Nathanmac\Utilities\Parser\ParserServiceProvider');
$app->register('Berkayk\OneSignal\OneSignalServiceProvider');
$app->register(Illuminate\Redis\RedisServiceProvider::class);
$app->register(Flipbox\LumenGenerator\LumenGeneratorServiceProvider::class);
// $app->register(Kamva\Moloquent\MoloquentServiceProvider::class);

class_alias('Nathanmac\Utilities\Parser\Facades\Parser', 'Parser');
class_alias('Berkayk\OneSignal\OneSignalFacade', 'OneSignal');

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
});

return $app;
