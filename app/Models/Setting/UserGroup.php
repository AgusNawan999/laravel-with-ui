<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserGroup extends Model
{
  use HasApiTokens, Notifiable;


  /**
   * The name of the "created at" column.
   *
   * @var string
   */
  const CREATED_AT = 'dt_created_at';

  /**
   * The name of the "updated at" column.
   *
   * @var string
   */
  const UPDATED_AT = 'dt_updated_at';

  /**
   * The primary key for the model.
   *
   * @var string
   */
  protected $primaryKey = 'i_id';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'i_id',
    'username',
    'v_group_code',
    'v_input_by',
    'v_deleted_by',
    'dt_created_at',
    'dt_updated_at',
    'dt_deleted_date',
  ];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setTable('tm_user_groups');
  }


  public function detailGroup(): HasOne
  {
    return $this->hasOne(Group::class, 'v_group_code', 'v_group_code');
  }
}
