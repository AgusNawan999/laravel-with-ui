<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\HasHashSlug;
use App\Models\Setting\Group;
use App\Models\Setting\UserGroup;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany, HasOne};

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable, HasHashSlug;


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
    'v_full_name',
    'v_email',
    'password',
    'dt_last_change_pass',
    'si_user_enable',
    'v_created_by',
    'dt_created_at',
    'v_updated_by',
    'dt_updated_at',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'dt_last_change_pass' => 'datetime',
    'password' => 'hashed',
    'si_user_enable' => 'boolean'
  ];

  /**
   * Create a new User model instance.
   *
   * @param  array  $attributes
   * @return void
   */
  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setTable('tm_users');
  }

  /**
   * Get all groups for the user.
   */
  public function groups() : BelongsToMany
  {
    return $this->belongsToMany(
      Group::class,
      'tm_user_groups',
      'username',
      'v_group_code',
      'username'
    )
    ->where('si_aktif', 1);
  }

  /**
   * Get profile data for the user.
   */
  public function profile() : HasOne
  {
    return $this->hasOne(UserProfile::class, 'nrk', 'v_userid');
  }

  /**
   * Get all features via group for the user.
   */
  public function featuresViaGroups() : BelongsToMany
  {
    return $this->groups()->with('features');
  }

  /**
   * Get the logHistory for the user login.
   */
  public function logHistory(): HasMany
  {
    return $this->hasMany(UserHistory::class, 'username', 'username');
  }


  public function userGroup(): HasMany
  {
    return $this->hasMany(UserGroup::class, 'username', 'username');
  }

  // public function PostKonfirmasi(): HasOne
  // {
  //   return $this->hasOne(Konfirmasi::class, 'v_userid', 'v_userid');
  // }
}
