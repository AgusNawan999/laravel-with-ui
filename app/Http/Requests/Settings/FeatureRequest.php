<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class FeatureRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return auth()->check();
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
   */
  public function rules(): array
  {
    if (request()->has('_method') && request('_method') == 'delete') {
      return ['slug' => 'required'];
    }

    $rules = [
      'nama' => ['required', 'min:3', 'max:50'],
      'alias' => ['required', 'min:3', 'max:100'],
      'tipe' => ['required', 'max:50'],
      'order' => ['nullable', 'numeric'],
      'keterangan' => ['nullable', 'max:2000'],
      'route' => ['nullable', 'string', 'max:255'],
      'icon' => ['nullable', 'string', 'max:100'],
    ];

    if (request()->has('_method') && request('_method') == 'put') {
      $rules['slug'] = ['required'];
    }

    return $rules;
  }

  /**
   * Get custom messages for validator errors.
   *
   * @return array
   */
  public function messages()
  {
    return [
      'slug.required' => 'slug_path diperlukan',
      'nama.required' => 'Nama Fitur Akses diperlukan',
      'nama.min' => 'Nama Fitur Akses minimal :min karakter',
      'nama.max' => 'Nama Fitur Akses maksimal :max karakter',
      'alias.required' => 'Alias Fitur Akses diperlukan',
      'alias.min' => 'Alias Fitur Akses minimal :min karakter',
      'alias.max' => 'Alias Fitur Akses maksimal :max karakter',
      'tipe.required' => 'Tipe Fitur Akses diperlukan',
    ];
  }
}
