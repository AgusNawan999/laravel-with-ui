<?php

use Carbon\Carbon;
use App\Models\AuditTrail;
use App\Models\Setting\Group;
use Illuminate\Support\Facades\Storage;

if (!function_exists('custom_captcha')) {
  /**
   * @param string $config
   * @return array|ImageManager|mixed
   * @throws Exception
   */
  function custom_captcha(string $config = 'default', $api = false)
  {
    return app('captcha')->createCaptcha($config, $api);
  }
}

if (!function_exists('check_captcha')) {
  /**
   * @param string $string
   * @return bool
   */
  function check_captcha(string $string)
  {
    return app('captcha')->checkCaptcha($string);
  }
}

if (!function_exists('include_route_files')) {
  /**
   * Loops through a folder and requires all PHP files
   * Searches sub-directories as well.
   *
   * @param $folder
   */
  function include_route_files($folder)
  {
    try {
      $rdi = new recursiveDirectoryIterator($folder);
      $it = new recursiveIteratorIterator($rdi);

      while ($it->valid()) {
        if (
          !$it->isDot()
          && $it->isFile()
          && $it->isReadable()
          && $it->current()->getExtension() === 'php'
        ) {
          require $it->key();
        }

        $it->next();
      }
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }
}

/**
 * Translate prepared statement to ready execute statement
 */
if (!function_exists('getQueries')) {
  /**
   * getQueries function
   *
   * @param [type] $builder
   * @param boolean $clear
   * @return void
   */
  function getQueries($builder, $clear = false)
  {
    $addSlashes = str_replace('?', "'?'", $builder->toSql());
    $temp = vsprintf(str_replace('?', '%s', $addSlashes), $builder->getBindings());
    return $clear ? clean_and_trim($temp) : $temp;
  }
}

/**
 * Clean and trim string
 */
if (!function_exists('clean_and_trim')) {
  /**
   * Clean and trim string
   *
   * @param String $sentence
   * @return String
   */
  function clean_and_trim($sentence)
  {
    if (strlen($sentence) == 0) return $sentence;
    return trim(preg_replace('/\s+/', ' ', str_replace("\n", '', $sentence)));
  }
}

if (!function_exists('encrypt_params')) {
  function encrypt_params($id, $length = 5)
  {
    $salt = base64_encode(config('app.name'));

    $hashIds = new \Hashids\Hashids($salt, $length);
    $temp = bin2hex($id);
    return $hashIds->encodeHex($temp);
  }
}

if (!function_exists('decrypt_params')) {
  function decrypt_params($id, $length = 5)
  {
    $salt = base64_encode(config('app.name'));

    $hashIds = new \Hashids\Hashids($salt, $length);
    $decoded = $hashIds->decodeHex($id);

    if (!isset($decoded)) {
      return null;
    }
    return hex2bin($decoded);
  }
}

if (!function_exists("getIp")) {
  /**
   * Get IP address.
   *
   */
  function getIp()
  {
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
      if (array_key_exists($key, $_SERVER) === true) {
        foreach (explode(',', $_SERVER[$key]) as $ip) {
          $ip = trim($ip); // just to be safe
          if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
            return $ip;
          }
        }
      }
    }

    return request()->ip(); // it will return server ip when no client ip found
  }
}

if (!function_exists('encode_slug')) {
  function encode_slug($slug, $id, $length = 5)
  {
    $pad = substr($slug, ($length * -1));
    $encryptedId = encrypt_params($id);

    return $slug . $encryptedId . strrev($pad);
  }
}

if (!function_exists('decode_slug')) {
  function decode_slug($encrypted, $length = 5)
  {
    $pad = strrev(substr($encrypted, ($length * -1)));
    $woPad = substr($encrypted, 0, strlen($encrypted) - strlen($pad));
    return decrypt_params(substr($woPad, ((strlen($woPad) - (mb_strpos($woPad, $pad) + $length)) * -1)));
  }
}

if (!function_exists('file_path')) {
  function file_path($date, $id)
  {
    $dateFormat = new Carbon($date);
    $file_path = 'public/';
    return Storage::path($file_path . $dateFormat->format('Y/m/d') . '/' . $id);
  }
}

if (!function_exists('str_is')) {
  /**
   * Determine if a given string matches a given pattern.
   *
   * @param  string|array  $pattern
   * @param  string  $value
   * @return bool
   */
  function str_is($pattern, $value)
  {
    return Str::is($pattern, $value);
  }
}

if (!function_exists("createLog")) {
  /**
   * Store a newly created log.
   *
   */
  function createLog($table, $aksi, $data)
  {
    $user_id = auth()->user()->username;
    AuditTrail::create([
      'v_user_aksi' => $user_id,
      'v_ip_user' => getIp(),
      'e_jenis_aksi' => $aksi,
      'v_nama_tabel' => $table,
      'tx_data' => json_encode($data),
    ]);
  }
}

if (!function_exists('adm_role')) {
  /**
   * Get ADM role code.
   *
   * @return string
   */
  function adm_role()
  {
    $group = Group::whereHas('admRole', fn($q) => $q->where('v_type', 'adm_role_access'))->first();
    return $group->v_group_code ?? null;
  }

  /**
   * Generate file by given connectionDb, settings and params.
   *
   * @param  array  $connectionDb = [
   *                    'connection' => 'oracle',
   *                    'port' => '1521',
   *                    'host' => 'host',
   *                    'username' => 'user',
   *                    'password' => 'pass',
   *                    'database' => 'db'
   *                ]
   *
   * @param  array  $settings = [
   *                    'type' => 'pdf',
   *                    'src' => 'source_file_jasper',
   *                    'output_dir' => 'output_dir',
   *                    'file_name' => 'file_name',
   *                    'userid' => 'userlogin',
   *                    'preview' => 'true'
   *                ]
   * @param  array  $params = [
   *                    ['param1' => 'val1'],
   *                    ['param2' => 'val2'],
   *                    ['param3' => 'val3'],
   *                    '...'
   *                ]
   */
  function jasperReport($connectionDb,$settings,$params)
  {
    $t=time();
    $date_str = join(explode("-",date("Y-m-d",$t)));
    $time_str = join(explode(":",date("h:i:s",$t)));

    $preview = $settings['preview'];

    if($preview){
      $preview = 'inline';
    }else{
      $preview = 'attachment';
    }

    $userid = $settings['userid'];
    $filename = $settings['file_name'];
    $filename_output = $filename.'_'.$userid.$date_str.$time_str;
    $type = $settings['type'];
    $output_dir = $settings['output_dir'];
    if(!is_dir($output_dir)){
      mkdir($output_dir);
    }
    exec("chmod -R 755 ".$output_dir);
    $fullpath = $output_dir.$filename_output.'.'.$type;
    $jasperstarter = base_path('vendor/geekcom/phpjasper/bin/jasperstarter/bin/jasperstarter');
    $src = $settings['src'];

    // database
    $host = $connectionDb['host'];
    $port = $connectionDb['port'];
    $database = $connectionDb['database'];
    $username = $connectionDb['username'];
    $password = $connectionDb['password'];
    // composer require geekcom/phpjasper
    $commandPath = '"'.$jasperstarter.'"';
    $command = 'pr ';
    $command .= '"'.$src.'" ';//input file jasper
    $command .= '-f '.$type.' ';//document type
    $command .= '-o "'.$output_dir.$filename_output.'" ';//output
    $command .= '--db-url jdbc:oracle:thin:@'.$host.':'.$port.'/'.$database.' ';//jdbc connection
    $command .= '-u '.$username.' ';//db username
    $command .= '-p '.$password.' ';//db password
    $command .= '-t generic ';//db driver
    $command .= '--jdbc-dir "'.storage_path('app/jasper/driver/').'" ';//driver directory ojdbc8.jar
    $command .= '--db-driver oracle.jdbc.driver.OracleDriver ';//db jdbc driver

    if(count($params) > 0){
      $command .= '-P ';//params
    }

    foreach ($params as $param) {
      $command .= ' '.array_keys($param)[0].'="'.$param[array_keys($param)[0]].'"';//
    }

    $command .=' 2>&1';

    //remove existing file before
    if(file_exists($fullpath)){
      @unlink($fullpath);
    }
    ob_start();
    exec($commandPath.' '.$command, $message,$status);
    ob_get_contents();
    ob_end_clean();
    if($status == 1){
      abort('404', json_encode([
        'title'=> 'Download '.$filename_output.'.'.$type,
        'code'=> 'ERROR',
        'message'=>'Generate File Pdf Gagal'
      ]));
    }else{
        if (file_exists($fullpath)) {
          header('Content-Type: application/' . $type);
          header("Content-Length: " . filesize($fullpath));
          header('Content-Disposition: '.$preview.'; filename=' . $filename_output . '.' . $type);
          header('Content-Transfer-Encoding: binary');
          header('Accept-Ranges: bytes');
          if(@readfile($fullpath)){
            @unlink($fullpath);
          }
          exit;


      } else {
        abort('404', json_encode([
          'title'=> 'Download '.$filename_output.'.'.$type,
          'code'=> 'ERROR',
          'message'=>'File hasil generate tidak ditemukan.'
        ]));
      }

    }


  }

}
