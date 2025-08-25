<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'mt_user';
    protected $primaryKey = 'mt_user_id';
    protected $returnType = 'object';
    protected $allowedFields = ['kd_pegawai','email','status'];
    protected $useTimestamps = true;

    public function getDataUser()
    {
        $user = $this->select('
                    mt_user.kd_pegawai,
                    mt_pegawai.nama AS nama_pegawai,
                    m_role.nama AS role_name,
                    m_gudang.nama as gudang_name,
                    mt_pegawai.jenis_kelamin,
                    m_role.role_scope,
                    mt_user.mt_user_id,
                    mt_pegawai.role_id,
                    mt_pegawai.penempatan_id,
                    mt_user.email,
                    mt_user.password,
                    mt_user.status,
                    mt_user.created_at,
                    mt_user.updated_at
                ')
                ->join('mt_pegawai', 'mt_pegawai.kd_pegawai = mt_user.kd_pegawai', 'left')
                ->join('m_role', 'm_role.m_role_id = mt_pegawai.role_id', 'left')
                ->join('m_gudang', 'm_gudang.m_gudang_id = mt_pegawai.penempatan_id', 'left');
        
		return $user->findAll();
    }
}