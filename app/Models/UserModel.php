<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'mt_user';
    protected $primaryKey = 'mt_user_id';
    protected $returnType = 'object';
    protected $allowedFields = ['kd_pegawai','email','password','created_by','updated_by'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataUser(array $filters = []): array
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
                    mt_user.created_at,
                    mt_user.updated_at
                ')
                ->join('mt_pegawai', 'mt_pegawai.kd_pegawai = mt_user.kd_pegawai', 'left')
                ->join('m_role', 'm_role.m_role_id = mt_pegawai.role_id', 'left')
                ->join('m_gudang', 'm_gudang.m_gudang_id = mt_pegawai.penempatan_id', 'left');

        if (isset($filters['mt_user_id']) && is_numeric($filters['mt_user_id'])) {
            $user->where('mt_user.mt_user_id', (int)$filters['mt_user_id']);
        }
        
        if (isset($filters['kd_pegawai']) && is_numeric($filters['kd_pegawai'])) {
            $user->where('mt_user.kd_pegawai', (int)$filters['kd_pegawai']);
        }
        
		return $user->findAll();
    }

    public function saveDataUser(array $data, $userId = null): bool
    {
        $data['mt_user_id'] = $userId;

        if (!empty($data['email'])) {
            $existingUser = $this->where('email', $data['email']);
    
            if ($userId) {
                $existingUser->where('mt_user_id !=', $userId);
            }
    
            $existingUser = $existingUser->first();
    
            if ($existingUser) {
                session()->setFlashdata('error', 'Email sudah digunakan oleh pengguna lain.');
                return false;
            }
        }

        $this->db->transStart();

        $user = $this->where('mt_user_id', $userId)->first();

        if ($user) {
            $ok = $this->update($userId, $data);
        } else {
            $ok = $this->insert($data, false) !== false;
        }

        if (!$ok) {
            $this->db->transRollback();
            return false;
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }
}