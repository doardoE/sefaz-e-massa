<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArrecadacaoRequest;
use App\Http\Resources\ArrecadacoesResource;
use App\Models\Arrecadacoes;
use App\Services\ArrecadacoesService;
use Illuminate\Http\Request;

class ArrecadacoesController extends Controller
{

    private ArrecadacoesService $service;

    public function __construct(ArrecadacoesService $service)
    {
        $this->service = $service;
    }
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
    public function store(ArrecadacaoRequest $request)
    {
        // chama service para criar arrecadação
        $created = $this->service->store($request->validated());;

        return response()->json([
            'message' => 'Dados cadastrados.',
            'data' => new ArrecadacoesResource($created),
        ], 201);
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
    public function update(ArrecadacaoRequest $request, Arrecadacoes $arrecadacoes)
    {
        $updated = $this->service->update($arrecadacoes, $request->validated());

        return response()->json([
            'message' => 'Dados atualizados com sucesso!',
            'data' => new ArrecadacoesResource($updated),
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Arrecadacoes $arrecadacoes)
    {
        $this->service->destroy($arrecadacoes);

        return response()->noContent();
    }


    public function kpis()
    {
        $anoAtual = date('Y');

        // total arrecadado no ano
        $totalArrecadado = Arrecadacoes::where('ano', $anoAtual)
            ->sum('valor');

        // Quantidade de registros
        $quantidadeRegistros = Arrecadacoes::where('ano', $anoAtual)
            ->count();

        // Tributo destaque (maior arrecadação)
        $tributoDestaque = Arrecadacoes::select('tributo', \DB::raw('SUM(valor) as total'))
            ->where('ano', $anoAtual)
            ->groupBy('tributo')
            ->orderByDesc('total')
            ->first();

        return response()->json([
            'message' => 'Dados dos KPIs',
            'resumo' => [
                'total_arrecadado' => $totalArrecadado,
                'quantidade_registros' => $quantidadeRegistros,
                'tributo_destaque' => [
                    'nome' => $tributoDestaque->tributo ?? null,
                    'valor' => $tributoDestaque->total ?? 0,
                ],
            ]
        ], 200);

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
        $totalArrecadado = (clone $query)
            ->sum('valor');

        $quantidadeRegistros = (clone $query)->count();

        $tributoDestaque = (clone $query)
            ->selectRaw('tributo, SUM(valor) as total')
            ->groupBy('tributo')
            ->orderByDesc('total')
            ->first();

        $arrecadacaoMensal = (clone $query)
            ->selectRaw('ano, mes, SUM(valor) as total')
            ->groupBy('ano', 'mes')
            ->orderBy('ano', 'desc')
            ->orderBy('mes', 'desc')
            ->limit(6)
            ->get()
            ->sortBy(fn($item) => sprintf('%04d-%02d', $item->ano, $item->mes))
            ->values();

        $arrecadacaoPorTributo = (clone $query)
            ->selectRaw('tributo, SUM(valor) as total')
            ->groupBy('tributo')
            ->orderBy('tributo', 'asc')
            ->get();

        $arrecadacoes = (clone $query)
            ->orderBy('ano', 'desc')
            ->orderBy('mes', 'desc')
            ->get(['id', 'tributo', 'ano', 'mes', 'valor']);

        // retorna tudo em JSON
        return response()->json(
            ['message' => 'Dados do dashboard',
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

}
