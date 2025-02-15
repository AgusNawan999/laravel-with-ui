<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StructureMenu extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    $data = [
      'label' => $this->v_name,
      'alias' => $this->v_alias,
      'route' => $this->v_route,
      'icon' => $this->v_icon,
      'childs' => []
    ];

    if ($this->hasChild && !$this->v_parent_id) {
      $data['childs'] = StructureMenu::collection($this->hasChild);
    }

    return $data;
  }
}
