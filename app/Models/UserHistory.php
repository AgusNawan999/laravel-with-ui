<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserHistory extends Model
{
  use HasFactory;

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
    $this->setTable('th_users');
  }
}
