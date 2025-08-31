<?php

namespace App\Models;

use CodeIgniter\Model;

class PegawaiModel extends Model
{
    protected $table      = 'mt_pegawai';
    protected $primaryKey = 'mt_pegawai_id';
    protected $returnType = 'object';
    protected $allowedFields = ['kd_pegawai','nama','jenis_kelamin','email','role_id','penempatan_id'];
    protected $useTimestamps = true;

    public function getDataPegawai(?array $filters = null): array
    {
        $filters = $filters ?? [];
        
        $user = $this->select('
                    mt_pegawai.kd_pegawai,
                    mt_pegawai.nama AS nama_pegawai,
                    m_role.nama AS role_name,
                    m_gudang.nama as gudang_name,
                    mt_pegawai.jenis_kelamin,
                    m_role.role_scope,
                    mt_pegawai.mt_pegawai_id,
                    mt_pegawai.role_id,
                    mt_pegawai.penempatan_id,
                    mt_pegawai.created_at,
                    mt_pegawai.updated_at
                ')
                ->join('m_role', 'm_role.m_role_id = mt_pegawai.role_id', 'left')
                ->join('m_gudang', 'm_gudang.m_gudang_id = mt_pegawai.penempatan_id', 'left');

        if (isset($filters['role_id']) && is_numeric($filters['role_id'])) {
            $user->where('mt_pegawai.role_id', (int)$filters['role_id']);
        }

        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $user->where('mt_pegawai.penempatan_id', (int)$filters['gudang_id']);
        }
        
		return $user->findAll();
    }
}