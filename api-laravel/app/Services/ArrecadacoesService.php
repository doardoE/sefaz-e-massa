<?php

namespace App\Services;

use DomainException;
use InvalidArgumentException;
use App\Models\Arrecadacoes;

class ArrecadacoesService
{
    public function store(array $data)
    {
        if ($this->existeArrecadacao($data['tributo'], $data['mes'], $data['ano']))
        {
            throw new DomainException('Já existe um registro para este tributo nesta data.');
        }

        if ($this->verificarDataMaior($data['mes'], $data['ano']))
        {
            throw new InvalidArgumentException('Não é possível adicionar tributo para datas futuras.');
        }

        return Arrecadacoes::create($data);
    }

    public function update(Arrecadacoes $arrecadacoes, array $data)
    {
        if ($this->existeArrecadacao($data['tributo'], $data['mes'], $data['ano'], $arrecadacoes->id)) {
            throw new DomainException('Já existe um registro para este tributo nesta data.');
        }

        if ($this->verificarDataMaior($data['mes'], $data['ano'])) {
            throw new InvalidArgumentException('Não é possível adicionar tributo para datas futuras.');
        }

        $arrecadacoes->update($data);

        return $arrecadacoes;
    }

    public function destroy(Arrecadacoes $arrecadacoes): void
    {
        $deleted = $arrecadacoes->delete();
        if (!$deleted) {
            throw new DomainException('Erro ao deletar arrecadação.');
        }
    }



    //[... Métodos auxiliares...]
    private function existeArrecadacao($tributo, $mes, $ano, $ignoreId = null)
    {
        $query = Arrecadacoes::where('tributo', $tributo)
            ->where('mes', $mes)
            ->where('ano', $ano);

        if (!is_null($ignoreId)) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }


    private function verificarDataMaior($mes, $ano): bool
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
