<?php

namespace App\Models\Content;

use App\Traits\HasHashSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia};

class Banner extends Model implements HasMedia
{
  use HasApiTokens, HasFactory, HasHashSlug, InteractsWithMedia;


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
  protected $primaryKey = 'i_id';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'v_title',
    'tx_description',
    'v_input_by',
    'dt_input_date',
    'v_update_by',
    'dt_update_date',
  ];

  protected $hidden = [
    'media'
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
    $this->setTable('tm_banner');
  }
  public function getFileDokumenAttribute()
  {
    $media = $this->getMedia('banner')->first();
    $url = $media ? route('download.show', ['media' => $media->id]) : null;
    return $url;
  }

  public function getFileNameAttribute() {
    $media = $this->getMedia('banner')->first();
    $file = $media ? $media->file_name : null;
    return $file;
  }
}
