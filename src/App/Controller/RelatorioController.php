<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 29/08/2019
 * Time: 01:43
 */

namespace App\Controller;


use App\Model\Chamado;
use Slim\Http\Request;
use Slim\Http\Response;
use Illuminate\Database\Capsule\Manager as DB;

class RelatorioController extends GenericController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function showRelatorios(Request $request, Response $response, array $args)
    {
        $dataInicial = $request->getParam('dataInicial');
        $dataFinal =$request->getParam('dataFinal');

        $result1 = DB::table('Chamados')
        ->select(DB::raw('empresaSolicitante as nome, count(empresaSolicitante) as total'))
        ->groupBy('empresaSolicitante')
        ->whereBetween('dataAbertura', [$dataInicial, $dataFinal])
        ->get()
        ->toArray();

        $result2 = DB::table('Chamados')
            ->select(DB::raw('modulosChamado.nome as nome, count(chamados.ModulosChamado_id) as total'))
            ->join('modulosChamado', 'modulosChamado.id', '=', 'chamados.ModulosChamado_id')
            ->groupBy('chamados.ModulosChamado_id')
            ->whereBetween('dataAbertura', [$dataInicial, $dataFinal])
            ->get()
            ->toArray();


        return $this->view->render($response, 'relatorio.twig',
            [
                'empresas' => $result1,
                'modulos' => $result2
            ]
        );
    }
}