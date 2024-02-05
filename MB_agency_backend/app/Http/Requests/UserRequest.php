<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        if (request()->routeIs('users.store')) {
            $name = 'unique:users,name';
            $email = 'unique:users,email';
            $pasword = 'confirmed';
        } elseif (request()->routeIs('users.update')) {
            $name = 'unique:users,name,' . $this->route('item')->id;
            $email = 'unique:users,email,' . $this->route('item')->id;
            $pasword = '';

        }
        return [
            'name' => ['required', $name],
            'email' => ['required', $email],
            'password' => ['nullable',$pasword],
            'role_id' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nombre'
        ];
    }
}
