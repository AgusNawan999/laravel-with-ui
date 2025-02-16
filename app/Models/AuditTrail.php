<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasHashSlug;
class AuditTrail extends Model
{
  use HasHashSlug;
  protected $primaryKey = "i_id";
  const CREATED_AT = 'dt_waktu_aksi';
  const UPDATED_AT = NULL;

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setTable('tl_audit_trail');
  }

  protected $fillable = [
    'v_user_aksi'
    , 'v_ip_user'
    , 'e_jenis_aksi'
    , 'v_nama_tabel'
    , 'tx_data'
  ];

  protected $casts = [
    'tx_data' => 'array'
  ];
}
