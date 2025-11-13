<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tipo_documento' => 'required|in:DNI,RUC,PASAPORTE',
            'numero_documento' => 'required|max:15',
            'nombre' => 'nullable|max:255',
            'direccion' => 'nullable|max:255',
            'id_ubigeo' => 'nullable|size:6',
            'telefono' => 'nullable|max:20',
            'correo' => 'nullable|email|max:100',
            'activo' => 'required|boolean',
        ];
    }
}