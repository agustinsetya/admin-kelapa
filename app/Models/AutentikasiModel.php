<?php

namespace App\Models;

use CodeIgniter\Model;

class AutentikasiModel extends Model
{
    protected $table      = 'mt_user';
    protected $primaryKey = 'mt_user_id';
    protected $returnType = 'object';

    protected $allowedFields = ['kd_pegawai', 'password', 'nama', 'email', 'role'];

    public function check_login(string $kd_pegawai, string $password)
    {
        $user = $this->where('kd_pegawai', $kd_pegawai)->first();
        if (! $user || empty($user->password)) {
            return false;
        }

        if (! password_verify($password, $user->password)) {
            return false;
        }

        if (password_needs_rehash($user->password, PASSWORD_DEFAULT)) {
            $this->update($user->id, ['password' => password_hash($password, PASSWORD_DEFAULT)]);
        }

        return $user;
    }
}