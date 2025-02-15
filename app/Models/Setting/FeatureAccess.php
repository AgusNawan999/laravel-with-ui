<?php

namespace App\Models\Setting;

use App\Traits\HasHashSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeatureAccess extends Model
{
  use HasFactory, HasHashSlug;

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
  protected $primaryKey = 'v_id';

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
    $this->setTable('tm_feature_access');
  }

  /**
   * Get the childs for the feature-access.
   */
  public function hasChild() : HasMany
  {
    return $this->hasMany(FeatureAccess::class, 'v_parent_id', 'v_id');
  }
}
