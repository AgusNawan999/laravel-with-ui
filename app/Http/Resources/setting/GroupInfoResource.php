<?php

namespace App\Http\Resources\setting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupInfoResource extends JsonResource
{
 /**
  * Resource into an array
  * @return array<string, mixed>
  */

  public function toArray(Request $request): array
  {
    $data = [
      'v_group_code' => $this->v_group_code,
      'v_group_name' => $this->v_group_name,
    ];

    return $data;
  }
}



?>
