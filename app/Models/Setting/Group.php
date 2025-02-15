<?php

namespace App\Models\Setting;

use App\Traits\HasHashSlug;
use App\Models\ReferensiUmum;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasOne};

class Group extends Model
{
  use HasFactory, HasHashSlug;
  /**
   * The name of the "created at" column.
   *
   * @var string
   */
  const CREATED_AT = 'dt_input_date';

  /**
   * The name of the "updated at" column.
   *
   * @var string
   */
  const UPDATED_AT = 'dt_update_date';

  /**
   * The primary key for the model.
   *
   * @var string
   */
  protected $primaryKey = 'v_group_code';

  /**
   * The "type" of the primary key ID.
   *
   * @var string
   */
  protected $keyType = 'string';

  /**
   * Indicates if the IDs are auto-incrementing.
   *
   * @var bool
   */
  public $incrementing = false;

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $guarded = [];

  /**
   * Create a new User model instance.
   *
   * @param  array  $attributes
   * @return void
   */
  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setTable('tm_group');
  }

  /**
   * The "booted" method of the model.
   */
  protected static function booted(): void
  {
    static::addGlobalScope('is_active', fn (Builder $builder) => $builder->where('si_aktif', 1));
  }

  /**
   * The groups that belong to the features.
   */
  public function features() : BelongsToMany
  {
    return $this->belongsToMany(
      FeatureAccess::class,
      'tm_feature_access_group',
      'v_group_code',
      'v_id_feature_access'
    );
  }

  /**
   * Group code which is used as a benchmark as ADM.
   */
  public function admRole() : HasOne
  {
    return $this->hasOne(ReferensiUmum::class, 'v_key', 'v_group_code');
  }
}
