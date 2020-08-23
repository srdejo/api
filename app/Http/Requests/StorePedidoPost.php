<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePedidoPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pedido'     => 'required|array',
            'pedido.*'    => 'required|array',
            'pedido.*.cantidad' => 'required|int',
            'pedido.*.producto_id' => 'required|int',
            'pedido.*.negocio_id' => 'required|int',
        ];
    }

    public function messages()
    {
        return [
            'pedido.*.cantidad.required' => 'La cantidad es obligatoria',
            'pedido.*.producto_id.required' => 'El identificador del producto es obligatorio',
        ];
    }

    public function attributes()
    {
        return [
            'pedido.*.cantidad' => 'cantidad',
            'pedido.*.producto_id' => 'identificador del producto',
            'pedido.*.negocio_id' => 'identificador del negocio',
        ];
    }
}
