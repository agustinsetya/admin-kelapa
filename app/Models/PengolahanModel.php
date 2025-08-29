<?php

namespace App\Models;

use CodeIgniter\Model;

class PengolahanModel extends Model
{
    protected $table         = 'mt_pengolahan';
    protected $primaryKey    = 'mt_pengolahan_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'tg_pengolahan',
        'gudang_id',
        'kd_pegawai',
        'berat_daging',
        'berat_kopra',
        'created_at',
        'updated_by',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataPengolahan(array $filters = []): array
    {
        $pengolahan = $this->select('
                    mt_pengolahan.mt_pengolahan_id,
                    mt_pengolahan.tg_pengolahan,
                    mt_pengolahan.gudang_id,
                    mt_pengolahan.kd_pegawai,
                    mt_pengolahan.berat_daging,
                    mt_pengolahan.berat_kopra,
                    m_gudang.nama AS nama_gudang,
                    mt_pegawai.nama AS nama_pegawai,
                    mt_pengolahan.created_at,
                ')
                ->join('m_gudang', 'm_gudang.m_gudang_id = mt_pengolahan.gudang_id', 'left')
                ->join('mt_pegawai', 'mt_pegawai.kd_pegawai = mt_pengolahan.kd_pegawai', 'left')
                ->orderby('mt_pengolahan.tg_pengolahan DESC');
        
        if (isset($filters['mt_pengolahan_id']) && is_numeric($filters['mt_pengolahan_id'])) {
            $pengolahan->where('mt_pengolahan.mt_pengolahan_id', (int)$filters['mt_pengolahan_id']);
        }

        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $pengolahan->where('mt_pengolahan.gudang_id', (int)$filters['gudang_id']);
        }
        
        if (isset($filters['kd_pegawai']) && is_numeric($filters['kd_pegawai'])) {
            $pengolahan->where('mt_pengolahan.kd_pegawai', (int)$filters['kd_pegawai']);
        }
        
		return $pengolahan->findAll();
    }

    public function saveDataPengolahan(array $data, $pengolahanId = null): bool
    {
        $data['mt_pengolahan_id'] = $pengolahanId;

        $this->db->transStart();

        $exists = $this->where('mt_pengolahan_id', $pengolahanId)->countAllResults() > 0;

        if ($exists) {
            $ok = $this->update($pengolahanId, $data);
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