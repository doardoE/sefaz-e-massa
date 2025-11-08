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
        if ($this->verificarDataMaior($request->mes, $request->ano)){
            return $this->error('Não é possível adicionar tributo para datas futuras!', 400);
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
    public function show(Arrecadacoes $arrecadacoes)
    {
        // visualizar arrecadação de tributo pelo id
        return new ArrecadacoesResource($arrecadacoes);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Arrecadacoes $arrecadacoes)
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

        if ($this->verificarDataMaior($request->mes, $request->ano)){
            return $this->error('Não é possível atualizar tributo para datas futuras!', 400);
        }

        $validated = $validator->validated();

        $updated = $arrecadacoes->update($validated);

        if(!$updated){
            return $this->error('Erro ao atualizar!', 400);
        }

        return $this->response('Dados atualizados com sucesso!', 200, new ArrecadacoesResource($arrecadacoes));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Arrecadacoes $arrecadacoes)
    {
        if (!$arrecadacoes) {
            return $this->error('Registro não encontrado!', 404);
        }

        $deleted = $arrecadacoes->delete();
        if (!$deleted) {
            return $this->error('Erro ao deletar!', 500);
        }

        return $this->response(
            'Tributo deletado com sucesso!',
            200,
            new ArrecadacoesResource($arrecadacoes)
        );
    }

    public function kpis()
    {
        // Total arrecadado
        $totalArrecadado = Arrecadacoes::sum('valor');

        // Quantidade de registros
        $quantidadeRegistros = Arrecadacoes::count();

        // Tributo destaque (maior arrecadação)
        $tributoDestaque = Arrecadacoes::selectRaw('tributo, SUM(valor) as total')
            ->groupBy('tributo')
            ->orderByDesc('total')
            ->first();

        return $this->response('Dados do dashboard', 200, [
            'resumo' => [
                'total_arrecadado' => $totalArrecadado,
                'quantidade_registros' => $quantidadeRegistros,
                'tributo_destaque' => [
                    'nome' => $tributoDestaque->tributo ?? null,
                    'valor' => $tributoDestaque->total ?? 0,
                ],
            ]
        ]);
    }

    /**
     * Retorna dados consolidados do dashboard (KPIs e gráficos)
     *
     * Filtros disponíveis via query string:
     * - ano_inicio / ano_fim → intervalo de anos
     * - mes_inicio / mes_fim → intervalo de meses
     * - tributo → único ou múltiplos tributos separados por vírgula
     *
     * Exemplos de uso:
     *   /api/dashboard
     *   /api/dashboard?ano_inicio=2023&ano_fim=2024
     *   /api/dashboard?tributo=IPTU,ISS
     *   /api/dashboard?ano_inicio=2023&mes_inicio=6&mes_fim=12&tributo=IPTU
     */
    public function dashboard(Request $request)
    {
        // filtros opcionais
        $anoInicio = $request->query('ano_inicio');
        $anoFim = $request->query('ano_fim');
        $mesInicio = $request->query('mes_inicio');
        $mesFim = $request->query('mes_fim');
        $tributos = $request->query('tributo'); // pode ser string ou array
        $anoAtual = date('Y');

        // base da query com possíveis filtros
        $query = Arrecadacoes::query();

        // aplicar filtros dinâmicos
        if ($anoInicio && $anoFim) {
            $query->whereBetween('ano', [$anoInicio, $anoFim]);
        } elseif ($anoInicio) {
            $query->where('ano', '>=', $anoInicio);
        } elseif ($anoFim) {
            $query->where('ano', '<=', $anoFim);
        }

        if ($mesInicio && $mesFim) {
            $query->whereBetween('mes', [$mesInicio, $mesFim]);
        } elseif ($mesInicio) {
            $query->where('mes', '>=', $mesInicio);
        } elseif ($mesFim) {
            $query->where('mes', '<=', $mesFim);
        }

        if ($tributos) {
            $tributosArray = is_array($tributos)
                ? $tributos
                : explode(',', $tributos);
            $query->whereIn('tributo', $tributosArray);
        }

        // total arrecadado e quantidade
        $totalArrecadado = $query->clone()->sum('valor');
        $quantidadeRegistros = $query->clone()->count();

        // tributo destaque (maior arrecadação)
        $tributoDestaque = $query->clone()
            ->selectRaw('tributo, SUM(valor) as total')
            ->groupBy('tributo')
            ->orderByDesc('total')
            ->first();

        // arrecadação mensal (6 últimos meses, considerando filtros)
        $arrecadacaoMensal = $query->clone()
            ->selectRaw('ano, mes, SUM(valor) as total')
            ->groupBy('ano', 'mes')
            ->orderBy('ano', 'desc')
            ->orderBy('mes', 'desc')
            ->limit(6)
            ->get()
            ->sortBy(fn($item) => sprintf('%04d-%02d', $item->ano, $item->mes))
            ->values();

        // arrecadação por tributo (para gráfico)
        $arrecadacaoPorTributo = $query->clone()
            ->selectRaw('tributo, SUM(valor) as total')
            ->groupBy('tributo')
            ->orderBy('tributo', 'asc')
            ->get();

        // listagem das arrecadacoes
        $arrecadacoes = $query->clone()->get(['id', 'tributo', 'ano', 'mes', 'valor']);


        // retorna tudo em JSON
        return $this->response('Dados do dashboard', 200, [
            'filtros' => [
                'ano_inicio' => $anoInicio,
                'ano_fim' => $anoFim,
                'mes_inicio' => $mesInicio,
                'mes_fim' => $mesFim,
                'tributos' => $tributos ?? null,
            ],
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
            'dados'=>[
                'arrecadacoes' => $arrecadacoes,
            ]
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

    private function verificarDataMaior($mes, $ano)
    {
        $mesAtual = date('m');
        $anoAtual = date('Y');

        // se o ano for maior que o atual
        if ($ano > $anoAtual) {
            return true;
        }

        // se o ano for o mesmo, mas o mês for maior que o atual
        if ($ano == $anoAtual && $mes > $mesAtual) {
            return true;
        }

        return false;
    }

}
