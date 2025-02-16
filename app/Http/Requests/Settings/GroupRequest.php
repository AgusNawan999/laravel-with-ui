<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
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
      'kode' => ['required', 'min:3', 'max:10'],
      'nama' => ['required', 'min:3', 'max:50'],
      'features' => ['required'],
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
      'kode.required' => 'Kode Group diperlukan',
      'kode.min' => 'Kode Group minimal :min karakter',
      'kode.max' => 'Kode Group maksimal :max karakter',
      'nama.required' => 'Nama Group diperlukan',
      'nama.min' => 'Nama Group minimal :min karakter',
      'nama.max' => 'Nama Group maksimal :max karakter',
      'features.required' => 'Fitur Akses diperlukan',
    ];
  }
}
