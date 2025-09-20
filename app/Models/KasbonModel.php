<?php

namespace App\Models;

use CodeIgniter\Model;

class KasbonModel extends Model
{
    protected $table         = 'mt_kasbon';
    protected $primaryKey    = 'mt_kasbon_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'tg_kasbon',
        'jumlah',
        'kd_pegawai',
        'status',
        'created_by',
        'updated_by',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataKasbon(array $filters = []): array
    {
        $kasbon = $this->select('
                    mt_kasbon.mt_kasbon_id,
                    mt_kasbon.tg_kasbon,
                    mt_kasbon.jumlah,
                    mt_kasbon.kd_pegawai,
                    mt_kasbon.status,
                    mt_pegawai.penempatan_id AS gudang_id,
                    m_gudang.nama AS nama_gudang,
                    mt_pegawai.nama AS nama_pegawai,
                ')
                ->join('mt_pegawai', 'mt_pegawai.kd_pegawai = mt_kasbon.kd_pegawai', 'left')
                ->join('m_gudang', 'm_gudang.m_gudang_id = mt_pegawai.penempatan_id', 'left');
        
        if (isset($filters['mt_kasbon_id']) && is_numeric($filters['mt_kasbon_id'])) {
            $kasbon->where('mt_kasbon.mt_kasbon_id', (int)$filters['mt_kasbon_id']);
        }
        
        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $kasbon->where('mt_pegawai.penempatan_id', (int)$filters['gudang_id']);
        }

        if (!empty($filters['tg_kasbon_start'])) {
            $kasbon->where('mt_kasbon.tg_kasbon >=', $filters['tg_kasbon_start']);
        }

        if (!empty($filters['tg_kasbon_end'])) {
            $kasbon->where('mt_kasbon.tg_kasbon <=', $filters['tg_kasbon_end']);
        }
        
		return $kasbon->orderBy('mt_kasbon.tg_kasbon', 'DESC')
                            ->findAll();
    }

    public function saveDataKasbon(array $data, $kasbonId = null): bool
    {
        $data['mt_kasbon_id'] = $kasbonId;

        $this->db->transStart();

        $exists = $this->where('mt_kasbon_id', $kasbonId)->countAllResults() > 0;

        if ($exists) {
            $ok = $this->update($kasbonId, $data);
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