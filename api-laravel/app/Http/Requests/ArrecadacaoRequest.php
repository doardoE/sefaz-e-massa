<?php

namespace App\Http\Requests;

use App\Models\Arrecadacoes;
use Illuminate\Foundation\Http\FormRequest;

class ArrecadacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->tokenCan('arr-store')
            || $this->user()?->tokenCan('arr-update')
            || $this->user()?->tokenCan('arr-destroy');
    }


    public function rules(): array
    {
        return [
            'tributo' => 'required|string|in:' . implode(',', Arrecadacoes::TRIBUTOS),
            'mes'     => 'required|integer|between:1,12',
            'ano'     => 'required|integer|min:2015|max:' . date('Y'),
            'valor'   => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'tributo.required' => 'O tributo é obrigatório.',
            'tributo.in'       => 'Tributo inválido.',
            'mes.required'     => 'O mês é obrigatório.',
            'ano.required'     => 'O ano é obrigatório.',
            'valor.required'   => 'O valor é obrigatório.',
        ];
    }
}
