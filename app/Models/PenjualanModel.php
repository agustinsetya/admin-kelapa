<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table         = 'mt_penjualan';
    protected $primaryKey    = 'mt_penjualan_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'tg_penjualan',
        'log_pengiriman_id',
        'daging_kelapa_terima',
        'pendapatan_terima',
        'daging_kelapa_reject',
        'pendapatan_reject',
        'status',
        'created_by',
        'updated_by',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataPenjualan(array $filters = []): array
    {
        $penjualan = $this->select('
                    mt_penjualan.mt_penjualan_id,
                    mt_penjualan.tg_penjualan,
                    mt_log_pengiriman.nomor_resi,
                    mt_penjualan.log_pengiriman_id,
                    mt_penjualan.daging_kelapa_terima,
                    mt_penjualan.pendapatan_terima,
                    mt_penjualan.daging_kelapa_reject,
                    mt_penjualan.pendapatan_reject,
                    mt_penjualan.status,
                    m_gudang.nama AS nama_gudang,
                    mt_penjualan.created_at,
                ')
            ->join('mt_log_pengiriman', 'mt_log_pengiriman.mt_log_pengiriman_id = mt_penjualan.log_pengiriman_id', 'left')
            ->join('m_gudang', 'm_gudang.m_gudang_id = mt_log_pengiriman.gudang_id', 'left')
            ->orderby('mt_penjualan.tg_penjualan DESC');

        if (isset($filters['mt_penjualan_id']) && is_numeric($filters['mt_penjualan_id'])) {
            $penjualan->where('mt_penjualan.mt_penjualan_id', (int)$filters['mt_penjualan_id']);
        }

        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $penjualan->where('mt_log_pengiriman.gudang_id', (int)$filters['gudang_id']);
        }

        if (!empty($filters['start_date'])) {
            $penjualan->where('mt_penjualan.tg_penjualan >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $penjualan->where('mt_penjualan.tg_penjualan <=', $filters['end_date']);
        }

        return $penjualan->findAll();
    }
    
    public function saveDataPenjualan(array $data, $penjualanId = null): bool
    {
        $data['mt_penjualan_id'] = $penjualanId;

        $pengirimanModel = new \App\Models\PengirimanModel();

        $this->db->transStart();

        $exists = $this->where('mt_penjualan_id', $penjualanId)->countAllResults() > 0;

        if ($exists) {
            $ok = $this->update($penjualanId, $data);
        } else {
            $ok = $this->insert($data, false) !== false;
        }

        if (!$ok) {
            $this->db->transRollback();
            return false;
        }

        $logPengirimanId = $data['log_pengiriman_id'] ?? null;

        if ($logPengirimanId) {
            $updatePengiriman = [
                'is_stat_penjualan' => 1
            ];

            $updateStatus = $pengirimanModel
                ->where('mt_log_pengiriman_id', $logPengirimanId)
                ->set($updatePengiriman)
                ->update();

            if (!$updateStatus) {
                $this->db->transRollback();
                return false;
            }
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function deleteDataPenjualan($penjualanId): bool
    {
        $this->db->transStart();

        $row = $this->asArray()
            ->where('mt_penjualan_id', $penjualanId)
            ->first();

        if (!$row) {
            $this->db->transRollback();
            return false;
        }

        $logPengirimanId = $row['log_pengiriman_id'] ?? null;

        // 1) Hapus data penjualan
        $ok = $this->delete($penjualanId);
        if (!$ok) {
            $this->db->transRollback();
            return false;
        }

        // 2) Jika ada log_pengiriman_id, cek apakah masih ada penjualan lain
        if ($logPengirimanId) {
            $sisa = $this->builder()
                ->where('log_pengiriman_id', $logPengirimanId)
                ->countAllResults();

            // 3) Jika tidak ada sisa penjualan dg log_pengiriman_id tsb, reset status di mt_log_pengiriman
            if ($sisa === 0) {
                $upd = $this->db->table('mt_log_pengiriman')
                    ->where('mt_log_pengiriman_id', $logPengirimanId)
                    ->update(['is_stat_penjualan' => 0]);

                if (!$upd) {
                    $this->db->transRollback();
                    return false;
                }
            }
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }
}
