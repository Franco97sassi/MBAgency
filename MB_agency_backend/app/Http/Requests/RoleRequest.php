<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if (request()->routeIs('roles.store')) {
            $name = 'unique:roles,name';
        } elseif (request()->routeIs('roles.update')) {
            $name = 'unique:roles,name,' . $this->route('item')->id;
        }
        return [
            'name' => ['required', $name],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nombre'
        ];
    }
}
