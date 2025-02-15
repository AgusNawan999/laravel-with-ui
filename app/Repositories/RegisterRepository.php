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
      'v_userid' => $user['v_userid'],
      'v_username' => $user['v_username'],
      'v_userpass' => $user['userpass_new'],
      'si_user_enable' => 1,
      'si_use_ekin' => 0,
      'v_created_by' => $user['v_userid'],
      'dt_created_at' => Carbon::now()
    ];

    // cek user telah terdaftar/belum
    $cek = $this->userModel->where('v_userid','=',$user['v_userid'])->first();
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

  public function getUsereTppByNrk($user){
    $result = UserEtpp::where('v_userid', $user->nrk)
      ->whereExists(
        fn ($q) =>
          $q->selectRaw(1)
            ->from('vw_pegawai')
            ->whereColumn('vw_pegawai.nrk', 'user_etpp.v_userid')
      )
      ->first();
    if($result == null){
      $result = [];
    }else{
      $result = $result->toArray();
    }

    return $result;
  }
}
