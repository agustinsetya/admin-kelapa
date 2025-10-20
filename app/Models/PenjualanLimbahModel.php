<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanLimbahModel extends Model
{
    protected $table         = 'mt_penjualan_limbah';
    protected $primaryKey    = 'mt_penjualan_limbah_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'tg_penjualan',
        'gudang_id',
        'jenis_limbah',
        'berat_limbah',
        'pendapatan_limbah',
        'status',
        'created_by',
        'updated_by',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataPenjualanLimbah(array $filters = []): array
    {
        $penjualanLimbah = $this->select('
                    mt_penjualan_limbah.mt_penjualan_limbah_id,
                    mt_penjualan_limbah.tg_penjualan,
                    mt_penjualan_limbah.gudang_id,
                    mt_penjualan_limbah.jenis_limbah,
                    mt_penjualan_limbah.berat_limbah,
                    mt_penjualan_limbah.pendapatan_limbah,
                    mt_penjualan_limbah.status,
                    m_gudang.nama AS nama_gudang,
                    mt_penjualan_limbah.created_at,
                ')
            ->join('m_gudang', 'm_gudang.m_gudang_id = mt_penjualan_limbah.gudang_id', 'left')
            ->orderby('mt_penjualan_limbah.tg_penjualan DESC');

        if (isset($filters['mt_penjualan_limbah_id']) && is_numeric($filters['mt_penjualan_limbah_id'])) {
            $penjualanLimbah->where('mt_penjualan_limbah.mt_penjualan_limbah_id', (int)$filters['mt_penjualan_limbah_id']);
        }

        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $penjualanLimbah->where('mt_penjualan_limbah.gudang_id', (int)$filters['gudang_id']);
        }

        if (!empty($filters['start_date'])) {
            $penjualanLimbah->where('mt_penjualan_limbah.tg_penjualan >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $penjualanLimbah->where('mt_penjualan_limbah.tg_penjualan <=', $filters['end_date']);
        }

        return $penjualanLimbah->findAll();
    }
    
    public function saveDataPenjualanLimbah(array $data, $penjualanLimbahLimbahId = null): bool
    {
        $data['mt_penjualan_limbah_id'] = $penjualanLimbahLimbahId;

        $this->db->transStart();

        $exists = $this->where('mt_penjualan_limbah_id', $penjualanLimbahLimbahId)->countAllResults() > 0;

        if ($exists) {
            $ok = $this->update($penjualanLimbahLimbahId, $data);
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

    public function deleteDataPenjualanLimbah($penjualanLimbahId): bool
    {
        $data['mt_penjualan_limbah_id'] = $penjualanLimbahId;

        $this->db->transStart();

        $exists = $this->where('mt_penjualan_limbah_id', $penjualanLimbahId)->countAllResults() > 0;

        if ($exists) {
            $ok = $this->delete($penjualanLimbahId);
        }

        if (!$ok) {
            $this->db->transRollback();
            return false;
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }
}
