<?php

use \Slim\Http\Request;
use \Slim\Http\Response;
use \App\Controller\UsuarioController;
use \App\Controller\LoginController;
use \App\Controller\ChamadoController;
use \App\Controller\RelatorioController;

// Routes
$app->get('[/]', function (Request $request, Response $response, $args) use ($app) {
    return $response->withRedirect('/login');
});

$app->get('/login', function (Request $request, Response $response, $args) use ($app) {

    if (isset($_SESSION['permission']) && $_SESSION['permission']) {
        return $response->withRedirect('/sistema/chamados');
    }

    return $this->view->render($response, 'login.twig');
});

$app->post('/auth', function (Request $request, Response $response, $args) use ($app) {
    return (new LoginController($app))->auth($request, $response, $args);
});

$app->post('/usuario', function (Request $request, Response $response, $args) use ($app){
    return (new UsuarioController($app))->save($request, $response, $args);
});


$app->group('/sistema', function () use ($app) {

    $app->get('/chamados', function (Request $request, Response $response, $args) use ($app){
        return (new ChamadoController($app))->getList($request, $response, $args);
    });

    $app->get('/chamados/novo', function (Request $request, Response $response, $args) use ($app) {
        return (new ChamadoController($app))->showCadastro($request, $response, $args);
    });

    $app->post('/chamados/novo', function (Request $request, Response $response, $args) use ($app){
        return (new ChamadoController($app))->save($request, $response, $args);
    });

    $app->get('/chamados/{id}', function (Request $request, Response $response, $args) use ($app) {
        return (new ChamadoController($app))->showUpdate($request, $response, $args);
    });

    $app->put('/chamados/{id}', function (Request $request, Response $response, $args) use ($app) {
        return (new ChamadoController($app))->update($request, $response, $args);
    });

    $app->delete('/chamados/{id}', function (Request $request, Response $response, $args) use ($app) {
        return (new ChamadoController($app))->delete($request, $response, $args);
    });

    $app->get('/relatorios', function (Request $request, Response $response, $args) use ($app) {
        return (new RelatorioController($app))->showRelatorios($request, $response, $args);
    });


    $app->get('/logout', function (Request $request, Response $response, $args) use ($app) {
        return (new LoginController($app))->logout($request, $response, $args);
    });
})->add($auth);
