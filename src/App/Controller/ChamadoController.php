<?php

namespace App\Controller;


use App\Model\CanalAtendimentoChamado;
use App\Model\Chamado;
use App\Model\ModuloChamado;
use App\Model\StatusChamado;
use App\Model\TipoChamado;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ChamadoController extends GenericController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function showCadastro(Request $request, Response $response, array $args)
    {
        return $this->view->render($response, 'chamadoCreate.twig',
            [
                'title' => 'Novo Chamado',
                'isNew' => true,
                'canais' => CanalAtendimentoChamado::all()->toArray(),
                'tipos' => TipoChamado::all()->toArray(),
                'modulos' => ModuloChamado::all()->toArray()
            ]
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function showUpdate(Request $request, Response $response, array $args)
    {
        $chamado = Chamado::find($args['id']);
        return $this->view->render($response, 'chamadoUpdate.twig',
            [
                'title' => 'Atualizar Chamado',
                'status' => StatusChamado::all()->toArray(),
                'canais' => CanalAtendimentoChamado::all()->toArray(),
                'tipos' => TipoChamado::all()->toArray(),
                'modulos' => ModuloChamado::all()->toArray(),
                'chamado' => $chamado->toArray(),
                'operador' => $chamado->usuario->toArray()
            ]
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function getList(Request $request, Response $response, array $args)
    {
        try {
//            $chamados = DB::table('Chamados')
            $chamados = Chamado::join('StatusChamado', 'chamados.StatusChamado_id', '=', 'StatusChamado.id')
                ->select('chamados.*', 'StatusChamado.nome as status')
                ->get();

            return $this->view->render($response, 'listagemChamado.twig',
                [
                    'title' => 'Listagem de Chamados',
                    'chamados' => $chamados
                ]
            );

        } catch (\Exception $exception) {

        }
    }


    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return ResponseInterface
     */
    public function save(Request $request, Response $response, array $args)
    {
        try {
            $chamado = new Chamado;

            $chamado->dataAbertura = (new \DateTime('now'))->format('Y-m-d h:i:s');
            $chamado->nomeSolicitante = strip_tags(trim($request->getParam('nomeSolicitante')));
            $chamado->empresaSolicitante = strip_tags(trim($request->getParam('empresaSolicitante')));
            $chamado->descricao = strip_tags(trim($request->getParam('descricaoChamado')));
            $chamado->Usuarios_id = $_SESSION['idUser'];
            $chamado->TiposChamado_id = strip_tags(trim($request->getParam('tipoChamado')));
            $chamado->StatusChamado_id = 1;
            $chamado->CanaisAtendimentoChamado_id = strip_tags(trim($request->getParam('canalAtendimento')));
            $chamado->ModulosChamado_id = strip_tags(trim($request->getParam('modulo')));

            if (!$chamado->save()) {
                throw new \Exception();
            }

            return $response->withJson([
                'success' => true,
                'message' => 'Chamado salvo com sucesso.'
            ]);
        } catch (\Exception $exception) {
            return $response->withJson([
                'success' => false,
                'message' => 'Não foi possível salvar o chamado. Por favor tente novamente.'
            ]);
        }

    }


    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return ResponseInterface
     */
    public function update(Request $request, Response $response, array $args)
    {
        try {
            $chamado = Chamado::findOrFail($args['id']);

            $chamado->nomeSolicitante = strip_tags(trim($request->getParam('nomeSolicitante')));
            $chamado->empresaSolicitante = strip_tags(trim($request->getParam('empresaSolicitante')));
            $chamado->descricao = strip_tags(trim($request->getParam('descricaoChamado')));
            $chamado->descricaoResolucao = strip_tags(trim($request->getParam('descricaoResolucao')));
            $chamado->Usuarios_id = $_SESSION['idUser'];
            $chamado->TiposChamado_id = strip_tags(trim($request->getParam('tipoChamado')));
            $chamado->StatusChamado_id = strip_tags(trim($request->getParam('status')));
            $chamado->CanaisAtendimentoChamado_id = strip_tags(trim($request->getParam('canalAtendimento')));
            $chamado->ModulosChamado_id = strip_tags(trim($request->getParam('modulo')));

            if (!$chamado->save()) {
                throw new \Exception();
            }

            return $response->withJson([
                'success' => true,
                'message' => 'Chamado atualizado com sucesso.'
            ]);
        } catch (\Exception $exception) {
            return $response->withJson([
                'success' => false,
                'message' => 'Não foi possível alterar o chamado. Por favor tente novamente.'
            ]);
        }

    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return ResponseInterface
     */
    public function delete(Request $request, Response $response, array $args)
    {
        try {
            $chamado = Chamado::find($args['id']);

            if (!$chamado->delete()) {
                throw new \Exception();
            }

            return $response->withJson([
                'success' => true,
                'message' => 'Chamado excludo com sucesso.'
            ]);
        } catch (\Exception $exception) {
            return $response->withJson([
                'success' => false,
                'message' => 'Não foi possível excluir o chamado. Por favor tente novamente.'
            ]);
        }
    }


}