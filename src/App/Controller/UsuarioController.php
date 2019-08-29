<?php

namespace App\Controller;


use App\Model\Usuario;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class UsuarioController extends GenericController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return ResponseInterface
     */
    public function save(Request $request, Response $response, array $args)
    {
        try {
            $nome = strip_tags(trim($request->getParam('nome')));
            $cpf = strip_tags(trim($request->getParam('cpf')));
            $email = strip_tags(trim($request->getParam('email')));
            $senha = $request->getParam('senha');

            if ($this->userExists($email)) {
                return $response->withJson([
                    'success' => false,
                    'message' => 'e-mail já cadastrado no sistema.'
                ]);
            }

            $usuario = new Usuario;
            $usuario->nome = $nome;
            $usuario->email = $email;
            $usuario->senha = password_hash($senha, PASSWORD_DEFAULT, ['cost' => 10]);

            $usuario->save();

            return $response->withJson([
                'success' => true,
                'message' => 'Usuário salvo com sucesso'
            ]);

        } catch (\Exception $exception) {
            return $response->withJson([
                'success' => false,
                'message' => 'Ocorreu uma falha no sistema. Por favor tente novamente.'
            ]);
        }
    }

    /**
     * @param $email
     * @return Usuario
     */
    private function userExists($email)
    {
        return Usuario::where('email', $email)->first();
    }
}