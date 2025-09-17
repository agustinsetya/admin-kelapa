<?php

namespace App\Models;

use CodeIgniter\Model;

class PengirimanModel extends Model
{
    protected $table         = 'mt_log_pengiriman';
    protected $primaryKey    = 'mt_log_pengiriman_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'tg_pengiriman',
        'gudang_id',
        'jenis_kirim',
        'armada',
        'kd_pegawai',
        'berat_daging',
        'jumlah_perjalanan',
        'bonus',
        'tg_proses_gaji',
        'is_stat_gaji',
        'created_at',
        'updated_by',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataPengiriman(array $filters = []): array
    {
        $pengiriman = $this->select('
                    mt_log_pengiriman.mt_log_pengiriman_id,
                    mt_log_pengiriman.tg_pengiriman,
                    mt_log_pengiriman.gudang_id,
                    mt_log_pengiriman.jenis_kirim,
                    mt_log_pengiriman.armada,
                    mt_log_pengiriman.kd_pegawai,
                    mt_log_pengiriman.berat_daging,
                    mt_log_pengiriman.jumlah_perjalanan,
                    mt_log_pengiriman.bonus,
                    mt_log_pengiriman.tg_proses_gaji,
                    mt_log_pengiriman.is_stat_gaji,
                    m_gudang.nama AS nama_gudang,
                    mt_pegawai.nama AS nama_pegawai,
                    mt_log_pengiriman.created_at,
                ')
            ->join('m_gudang', 'm_gudang.m_gudang_id = mt_log_pengiriman.gudang_id', 'left')
            ->join('mt_pegawai', 'mt_pegawai.kd_pegawai = mt_log_pengiriman.kd_pegawai', 'left')
            ->orderby('mt_log_pengiriman.tg_pengiriman DESC');

        if (isset($filters['mt_log_pengiriman_id']) && is_numeric($filters['mt_log_pengiriman_id'])) {
            $pengiriman->where('mt_log_pengiriman.mt_log_pengiriman_id', (int)$filters['mt_log_pengiriman_id']);
        }

        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $pengiriman->where('mt_log_pengiriman.gudang_id', (int)$filters['gudang_id']);
        }

        if (isset($filters['kd_pegawai']) && is_numeric($filters['kd_pegawai'])) {
            $pengiriman->where('mt_log_pengiriman.kd_pegawai', (int)$filters['kd_pegawai']);
        }

        if (!empty($filters['start_date'])) {
            $pengiriman->where('mt_log_pengiriman.tg_pengiriman >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $pengiriman->where('mt_log_pengiriman.tg_pengiriman <=', $filters['end_date']);
        }

        return $pengiriman->findAll();
    }
    
    public function getDataUpahPengiriman(array $filters = []): array
    {
        $start = $filters['start_date'] ?? null;
        $end   = $filters['end_date'] ?? null;

        $upah = $this->db->table('mt_log_pengiriman p');
        $upah->select("
            p.kd_pegawai, pg.nama AS nama_driver, p.gudang_id, g.nama AS nama_gudang,
            SUM(ROUND((COALESCE(p.jumlah_perjalanan, 0) * NULLIF(g.gaji_driver, 0)), 0)) AS total_upah_perjalanan,
            SUM(COALESCE(p.bonus, 0)) AS total_bonus,
            (
                SUM(ROUND((COALESCE(p.jumlah_perjalanan, 0) * NULLIF(g.gaji_driver, 0)), 0)) +
                SUM(COALESCE(p.bonus, 0))
            ) AS total_gaji_bersih
        ", false);

        $upah->join('m_gudang g', 'g.m_gudang_id = p.gudang_id', 'left');
        $upah->join('mt_pegawai pg', 'pg.kd_pegawai = p.kd_pegawai', 'left');
        $upah->where('p.is_stat_gaji', 0);

        // Filters
        if (!empty($filters['kd_pegawai'])) {
            $upah->whereIn('p.kd_pegawai', (array) $filters['kd_pegawai']);
        }

        if (!empty($filters['gudang_id'])) {
            $upah->where('p.gudang_id', $filters['gudang_id']);
        }

        if (!empty($start)) {
            $upah->where('p.tg_pengiriman >=', $start);
        }

        if (!empty($end)) {
            $upah->where('p.tg_pengiriman <=', $end);
        }

        $upah->groupBy('p.kd_pegawai, p.gudang_id');

        return $upah->get()->getResultArray();
    }

    public function saveDataPengiriman(array $data, $pengirimanId = null): bool
    {
        $data['mt_log_pengiriman_id'] = $pengirimanId;

        $this->db->transStart();

        $exists = $this->where('mt_log_pengiriman_id', $pengirimanId)->countAllResults() > 0;

        if ($exists) {
            $ok = $this->update($pengirimanId, $data);
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
