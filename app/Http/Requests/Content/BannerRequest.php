<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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
      'title' => ['required', 'min:3', 'max:50'],
      'keterangan' => ['nullable', 'max:2000'],
      'file_image' => request('_method') == 'put' ? ['mimes:jpeg,jpg,png','max:2048'] : ['required', 'mimes:jpeg,jpg,png','max:2048'],
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
      'title.required' => 'Nama banner diperlukan',
      'title.min' => 'Nama banner minimal :min karakter',
      'title.max' => 'Nama banner maksimal :max karakter',
      'file_image.required' => 'File Image Layanan diperlukan',
      'file_image.mimes' => 'File Image Layanan harus berekstensi :mimes',
      'file_image.max' => 'File Image Layanan harus berekstensi :max',
    ];
  }
}
