<?php

namespace App\Models;

use CodeIgniter\Model;

class AutentikasiModel extends Model
{
    protected $table      = 'mt_pegawai';
    protected $primaryKey = 'mt_pegawai_id';
    protected $returnType = 'object';

    public function checkLogin(string $kd_pegawai, string $password)
    {
        $user = $this->select('
                    mt_pegawai.mt_pegawai_id,
                    mt_pegawai.kd_pegawai,
                    mt_pegawai.nama AS nama_pegawai,
                    mt_pegawai.jenis_kelamin,
                    mt_pegawai.role_id,
                    mt_pegawai.penempatan_id,
                    mt_pegawai.email,
                    mt_pegawai.created_at,
                    mt_pegawai.updated_at,
                    m_role.nama AS role_name,
                    m_role.role_scope
                ')
                ->join('m_role', 'm_role.role_id = mt_pegawai.role_id', 'left')
                ->where('mt_pegawai.kd_pegawai', $kd_pegawai)
                ->first();
        
        if (! $user || empty($user->password)) {
            return false;
        }
        
        if (password_verify($password, $user->password)) {
            if (password_needs_rehash($user->password, PASSWORD_DEFAULT)) {
                $this->update($user->mt_pegawai_id, [
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ]);
            }
    
            return $user;
        } else {
            return false;
        }
    }
}