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
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ArrecadacoesResource::collection(Arrecadacoes::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tributo' => 'required|string|in:' . implode(',', Arrecadacoes::TRIBUTOS),
            'mes' => 'required|numeric|between:1,12',
            'ano' => 'required|numeric|min:2015|max:'.date('Y'),
            'valor' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->error('Dados incompletos ou inválidos!', 422, (array)$validator->errors());
        }

        $existe = Arrecadacoes::where('tributo', $request->tributo)
            ->where('mes', $request->mes)
            ->where('ano', $request->ano)
            ->exists();

        if ($existe) {
            return $this->error(
                'Já existe um registro para este tributo, mês e ano!',
                409,
            );
        }

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
        // visualiza pelo id
        return new ArrecadacoesResource(Arrecadacoes::where('id', $id)->first());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
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

        $existe = Arrecadacoes::where('tributo', $request->tributo)
            ->where('mes', $request->mes)
            ->where('ano', $request->ano)
            ->exists();

        if ($existe) {
            return $this->error(
                'Já existe um registro para este tributo, mês e ano!',
                409,
            );
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
}
