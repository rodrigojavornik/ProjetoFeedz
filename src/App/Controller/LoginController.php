<?php

namespace App\Controller;


use App\Model\Usuario;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class LoginController extends GenericController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return ResponseInterface
     */
    public function auth(Request $request, Response $response, array $args)
    {
        $email = strip_tags($request->getParam('email'));
        $senha = $request->getParam('senha');
        $usuario = Usuario::where('email', $email)->first();

        if (!$usuario || !password_verify($senha, $usuario->senha)) {
            return $this->view->render($response, 'login.twig', ['message' => 'Errrrroooooouuuu. e-mail ou senha inválidos']);
        }

        $_SESSION['permission'] = true;
        $_SESSION['idUser'] = $usuario->id;

        return $response->withRedirect('/sistema/chamados');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return ResponseInterface
     */
    public function logout(Request $request, Response $response, array $args)
    {
        $_SESSION['permission'] = false;
        $_SESSION['idUser'] = '';

        return $response->withRedirect('/login');
    }
}