<?php

namespace App\Models;

use CodeIgniter\Model;

class AutentikasiModel extends Model
{
    protected $table      = 'mt_user';
    protected $primaryKey = 'mt_user_id';
    protected $returnType = 'object';

    public function checkLogin(string $email, string $password)
    {
        $user = $this->select('
                    mt_user.mt_user_id,
                    mt_user.kd_pegawai,
                    mt_pegawai.nama AS nama_pegawai,
                    mt_pegawai.jenis_kelamin,
                    mt_pegawai.role_id,
                    mt_pegawai.penempatan_id,
                    mt_user.email,
                    mt_user.password,
                    mt_user.created_at,
                    mt_user.updated_at,
                    m_role.nama AS role_name,
                    m_role.role_scope
                ')
                ->join('mt_pegawai', 'mt_pegawai.kd_pegawai = mt_user.kd_pegawai', 'left')
                ->join('m_role', 'm_role.m_role_id = mt_pegawai.role_id', 'left')
                ->where('mt_user.email', $email)
                ->first();
        
        if (! $user) {
            log_message('error', 'User not found: ' . $email);
            return false;
        }

        if (!isset($user->password)) {
            log_message('error', 'Password not set for user: ' . $email);
            return false;
        }
        
        if (password_verify($password, $user->password)) {
            if (password_needs_rehash($user->password, PASSWORD_DEFAULT)) {
                $this->update($user->mt_user_id, [
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ]);
            }
    
            unset($user->password);
            return $user;
        } else {
            return false;
        }
    }
}