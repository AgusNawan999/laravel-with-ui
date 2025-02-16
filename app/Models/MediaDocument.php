<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaDocument extends Media
{
  use HasUuids;

  public $incrementing = false;
  protected $guarded = [];  //all attribute are mass assignable, except;

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setTable('tt_media');
  }

  public function getUrlAttribute()
  {
    return $this->getUrl();
  }

  public function getTagAttribute()
  {
    return $this->getCustomProperty('tag');
  }

  public function getJenisAttribute()
  {
    return $this->getCustomProperty('jenis');
  }

  public function getLinkAttribute()
  {
    if(str_is('image/*',$this->mime_type))
    {
      return '<img src="'.$this->getUrl().'" alt="'.$this->file_name.'">';
    }
    return '<a href="'.$this->getUrl().'"><i class="fa '.mime2fa($this->mime_type).' fa-2x"></i> '.$this->file_name.' ('.$this->human_readable_size.')</a>';
  }

  public function getViewer($width='100%',$height='550',$download=true)
  {
    if(str_is('image/*',$this->mime_type))
    {
      return '<img src="'.$this->getUrl().'" alt="'.$this->file_name.'" width="'.$width.'" height="'.$height.'">';
    }
    if ($download)
    {
      return '<iframe src ="'.asset('/viewer/download.html#../media/download/'.$this->id).'" width="'.$width.'" height="'.$height.'" allowfullscreen webkitallowfullscreen></iframe>';
    }
    return '<iframe src ="'.asset('/viewer/index.html#../media/download/'.$this->id).'" width="'.$width.'" height="'.$height.'" allowfullscreen webkitallowfullscreen></iframe>';
  }

  public function getSharedViewer($width='100%',$height='550',$download=true)
  {
    if(str_is('image/*',$this->mime_type))
    {
      return '<img src="'.$this->getSharedUrl().'" alt="'.$this->file_name.'" width="'.$width.'" height="'.$height.'">';
    }
    if ($download)
    {
      return '<iframe src ="'.asset('/viewer/download.html#../shared/m/'.$this->id.'/'.$this->file_name).'" width="'.$width.'" height="'.$height.'" allowfullscreen webkitallowfullscreen></iframe>';
    }
    return '<iframe src ="'.asset('/viewer/index.html#../shared/m/'.$this->id.'/'.$this->file_name).'" width="'.$width.'" height="'.$height.'" allowfullscreen webkitallowfullscreen></iframe>';
  }

}
