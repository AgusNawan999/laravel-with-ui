<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserEtpp;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Repositories\Contracts\RegisterRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RegisterRepository implements RegisterRepositoryInterface
{

  protected $userModel;
  protected $mappedColumn = [];

  public function __construct(User $userModel)
  {
    $this->userModel = $userModel;
  }

  public function daftar($user)
  {
    $data = [
      'username' => $user['username'],
      'v_full_name' => $user['v_full_name'],
      'password' => $user['password'],
      'si_user_enable' => 1,
      'v_created_by' => $user['username'],
      'dt_created_at' => Carbon::now()
    ];

    // cek user telah terdaftar/belum
    $cek = $this->userModel->where('username','=',$user['username'])->first();
    if($cek != null){
      $hasil = [
				'status' => 'error',
				'message' => 'User Sudah Terdaftar'];

      return json_encode($hasil);
    }

    DB::beginTransaction();

    try {

      $this->userModel->create($data);

      DB::commit();

			$hasil = [
				'status' => 'success',
				'message' => 'Pendaftaran Berhasil'];
    } catch (\Throwable $th) {
      Log::error($th->getMessage());
			DB::rollBack();
			$hasil = [
				'status' => 'error',
				'message' => 'Pendaftaran Gagal',
			];
    }

    return json_encode($hasil);
  }

}
