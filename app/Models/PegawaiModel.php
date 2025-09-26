<?php

namespace App\Models;

use CodeIgniter\Model;

class PegawaiModel extends Model
{
    protected $table      = 'mt_pegawai';
    protected $primaryKey = 'mt_pegawai_id';
    protected $returnType = 'object';
    protected $allowedFields = ['kd_pegawai','nama','jenis_kelamin','email','role_id','penempatan_id','created_by','updated_by'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataPegawai(?array $filters = null): array
    {
        $filters = $filters ?? [];
        
        $user = $this->select('
                    mt_pegawai.kd_pegawai,
                    mt_pegawai.nama AS nama_pegawai,
                    m_role.nama AS role_name,
                    m_gudang.nama as nama_gudang,
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

        if (!empty($filters['exclude_existing_user']) && $filters['exclude_existing_user'] === true) {
            $user->join('mt_user', 'mt_user.kd_pegawai = mt_pegawai.kd_pegawai', 'left');
            $user->where('mt_user.kd_pegawai IS NULL');
        }
        
        if (isset($filters['mt_pegawai_id']) && is_numeric($filters['mt_pegawai_id'])) {
            $user->where('mt_pegawai.mt_pegawai_id', (int)$filters['mt_pegawai_id']);
        }

        if (isset($filters['pegawai_id_not']) && is_numeric($filters['pegawai_id_not'])) {
            $user->where('mt_pegawai.kd_pegawai !=' . (int)$filters['pegawai_id_not']);
        }       
        
        if (isset($filters['role_id']) && is_numeric($filters['role_id'])) {
            $user->where('mt_pegawai.role_id', (int)$filters['role_id']);
        }

        if (isset($filters['role_id_not']) && is_numeric($filters['role_id_not'])) {
            $user->where('mt_pegawai.role_id !=' . (int)$filters['role_id_not']);
        }        

        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $user->where('mt_pegawai.penempatan_id', (int)$filters['gudang_id']);
        }
        
		return $user->findAll();
    }

    public function saveDataPegawai(array $data, $pegawaiId = null): bool
    {
        $this->db->transStart();

        $ok = false;

        if (empty($pegawaiId)) {
            $roleId = $data['role_id'] ?? null;
            $kodeGudang = null;

            if ($roleId == 1) {
                $prefix = "0";
            } else {
                // Ambil kode_gudang dari tabel m_gudang  
                $gudang = $this->db->table('m_gudang')
                    ->select('kode_gudang')
                    ->where('m_gudang_id', $data['penempatan_id'])
                    ->get()
                    ->getRow();

                if (!$gudang) {
                    $this->db->transRollback();
                    return false;
                }

                $prefix = (string) $gudang->kode_gudang;
            }

            // cari kd_pegawai terakhir berdasarkan prefix
            $lastPegawai = $this->select('kd_pegawai')
                ->like('kd_pegawai', $prefix, 'after')
                ->orderBy('kd_pegawai', 'DESC')
                ->first();

            if ($lastPegawai) {
                $lastNumber = (int)substr($lastPegawai->kd_pegawai, strlen($prefix));
            } else {
                $lastNumber = 0;
            }
    
            $newNumber = $lastNumber + 1;
            $newKode = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    
            $data['kd_pegawai'] = $newKode;

            $ok = $this->insert($data, false) !== false;
        } else {  
            $user = $this->where('mt_pegawai_id', $pegawaiId)->first();  
            
            if ($user) {  
                $ok = $this->update($pegawaiId, $data);  
            } else {
                $ok = false;
            }
        }  

        if (!$ok) {  
            $this->db->transRollback();  
            return false;  
        }

        $this->db->transComplete();  
        return $this->db->transStatus();
    }
}