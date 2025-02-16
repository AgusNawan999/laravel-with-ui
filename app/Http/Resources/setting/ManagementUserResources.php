<?php

namespace App\Http\Resources\setting;

use App\Models\Setting\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class ManagementUserResources extends JsonResource
{
 /**
  * Resource into an array
  * @return array<string, mixed>
  */

  public function toArray(Request $request): array
  {
    // dd($this->userGroup->loadMissing('detailGroup'));
    $data = [
      'slug' => $this->slug_path,
      'username' => $this->username,
      'v_full_name' => $this->v_full_name,
      'email' => $this->v_email,
      'si_user_enable' => $this->si_user_enable,

    ];
    $data['user_group'] = $this->userGroup ? $this->userGroup->loadMissing('detailGroup') : 'Kosong';

    return $data;
  }
}



?>
