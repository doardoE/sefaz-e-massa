<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\ArrecadacoesResource;
use App\Models\Arrecadacoes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\HttpResponses;

class ArrecadacoesController extends Controller
{
    // traits para responde de erro ou sucess
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // retorna lista com todas as arrecadaçoes
        return ArrecadacoesResource::collection(Arrecadacoes::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // para validar a os dados da requisição do usuário
        $validator = Validator::make($request->all(), [
            'tributo' => 'required|string|in:' . implode(',', Arrecadacoes::TRIBUTOS),
            'mes' => 'required|numeric|between:1,12',
            'ano' => 'required|numeric|min:2015|max:'.date('Y'),
            'valor' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->error('Dados incompletos ou inválidos!', 422, (array)$validator->errors());
        }

        if ($this->existeArrecadacao($request->tributo, $request->mes, $request->ano)) {
            return $this->error('Já existe um registro para este tributo, mês e ano!', 409);
        }

        // Cria a arrecadação no banco de dados
        $created = Arrecadacoes::create($validator->validated());
        if(!$created){
            return $this->error('Erro ao cadastrar!', 400);
        }

        return $this->response('Dados cadastrados!', 200, new ArrecadacoesResource($created));


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // visualizar arrecadação de tributo pelo id
        return new ArrecadacoesResource(Arrecadacoes::where('id', $id)->first());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // para validar a os dados da requisição do usuário
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'tributo' => 'required|string|in:' . implode(',', Arrecadacoes::TRIBUTOS),
            'mes' => 'required|numeric|between:1,12',
            'ano' => 'required|numeric|min:2015|max:'.date('Y'),
            'valor' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->error('Dados inválidos!', 422, (array)$validator->errors());
        }

        if ($this->existeArrecadacao($request->tributo, $request->mes, $request->ano)) {
            return $this->error('Já existe um registro para este tributo, mês e ano!', 409);
        }

        $validated = $validator->validated();
        $arrecadacao = Arrecadacoes::find($id);

        $updated = $arrecadacao->update($validated);

        if(!$updated){
            return $this->error('Erro ao atualizar!', 400);
        }

        return $this->response('Dados atualizados com sucesso!', 200, new ArrecadacoesResource($arrecadacao));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $arrecadacao = Arrecadacoes::find($id);

        if (!$arrecadacao) {
            return $this->error('Registro não encontrado!', 404);
        }

        $deleted = $arrecadacao->delete();
        if (!$deleted) {
            return $this->error('Erro ao deletar!', 500);
        }

        return $this->response(
            'Tributo deletado com sucesso!',
            200,
            new ArrecadacoesResource($arrecadacao)
        );
    }

    public function dashboard()
    {
        $anoAtual = date('Y');

        // Total arrecadado
        $totalArrecadado = Arrecadacoes::sum('valor');

        // Quantidade de registros
        $quantidadeRegistros = Arrecadacoes::count();

        // Tributo destaque (maior arrecadação)
        $tributoDestaque = Arrecadacoes::selectRaw('tributo, SUM(valor) as total')
            ->groupBy('tributo')
            ->orderByDesc('total')
            ->first();

        // Gráfico: arrecadação mensal do ano atual
        $arrecadacaoMensal = Arrecadacoes::selectRaw('mes, SUM(valor) as total')
            ->where('ano', $anoAtual)
            ->groupBy('mes')
            ->orderBy('mes', 'asc')
            ->get();

        // Gráfico: arrecadação por tributo (distribuição)
        $arrecadacaoPorTributo = Arrecadacoes::selectRaw('tributo, SUM(valor) as total')
            ->groupBy('tributo')
            ->orderBy('tributo', 'asc')
            ->get();

        // Retorna tudo em um JSON organizado
        return $this->response('Dados do dashboard', 200, [
            'resumo' => [
                'total_arrecadado' => $totalArrecadado,
                'quantidade_registros' => $quantidadeRegistros,
                'tributo_destaque' => [
                    'nome' => $tributoDestaque->tributo ?? null,
                    'valor' => $tributoDestaque->total ?? 0,
                ],
            ],
            'graficos' => [
                'arrecadacao_mensal' => $arrecadacaoMensal,
                'arrecadacao_por_tributo' => $arrecadacaoPorTributo,
            ],
        ]);
    }





    //[... Métodos auxiliares...]
    private function existeArrecadacao($tributo, $mes, $ano)
    {
        return Arrecadacoes::where('tributo', $tributo)
            ->where('mes', $mes)
            ->where('ano', $ano)
            ->exists();
    }

}
