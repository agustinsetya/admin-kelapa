<?php

namespace App\Models;

use CodeIgniter\Model;

class PengirimanModel extends Model
{
    protected $table         = 'mt_pengiriman';
    protected $primaryKey    = 'mt_pengiriman_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'tg_pengiriman',
        'gudang_id',
        'kd_pegawai',
        'berat_daging',
        'berat_kopra',
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
                    mt_pengiriman.mt_pengiriman_id,
                    mt_pengiriman.tg_pengiriman,
                    mt_pengiriman.gudang_id,
                    mt_pengiriman.kd_pegawai,
                    mt_pengiriman.berat_daging,
                    mt_pengiriman.berat_kopra,
                    mt_pengiriman.jumlah_perjalanan,
                    mt_pengiriman.bonus,
                    mt_pengiriman.tg_proses_gaji,
                    mt_pengiriman.is_stat_gaji,
                    m_gudang.nama AS nama_gudang,
                    mt_pegawai.nama AS nama_pegawai,
                    mt_pengiriman.created_at,
                ')
            ->join('m_gudang', 'm_gudang.m_gudang_id = mt_pengiriman.gudang_id', 'left')
            ->join('mt_pegawai', 'mt_pegawai.kd_pegawai = mt_pengiriman.kd_pegawai', 'left')
            ->orderby('mt_pengiriman.tg_pengiriman DESC');

        if (isset($filters['mt_pengiriman_id']) && is_numeric($filters['mt_pengiriman_id'])) {
            $pengiriman->where('mt_pengiriman.mt_pengiriman_id', (int)$filters['mt_pengiriman_id']);
        }

        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $pengiriman->where('mt_pengiriman.gudang_id', (int)$filters['gudang_id']);
        }

        if (isset($filters['kd_pegawai']) && is_numeric($filters['kd_pegawai'])) {
            $pengiriman->where('mt_pengiriman.kd_pegawai', (int)$filters['kd_pegawai']);
        }

        if (!empty($filters['start_date'])) {
            $pengiriman->where('mt_pengiriman.tg_pengiriman >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $pengiriman->where('mt_pengiriman.tg_pengiriman <=', $filters['end_date']);
        }

        return $pengiriman->findAll();
    }
    
    public function getDataUpahPengiriman(array $filters = []): array
    {
        $start = $filters['start_date'] ?? null;
        $end   = $filters['end_date'] ?? null;

        $upah = $this->select(
                    "p.kd_pegawai, pg.nama AS nama_pegawai, p.gudang_id, g.nama AS nama_gudang,
                        COALESCE(SUM( (COALESCE(p.berat_daging,0) / NULLIF(g.takaran_daging,0)) * COALESCE(g.upah_takaran_daging,0) ), 0)
                            AS upah_total_daging,
                        COALESCE(SUM( (COALESCE(p.berat_kopra,0) / NULLIF(g.takaran_kopra,0)) * COALESCE(g.upah_takaran_kopra,0) ), 0)
                            AS upah_total_kopra,
                        (
                            COALESCE(SUM( (COALESCE(p.berat_daging,0) / NULLIF(g.takaran_daging,0)) * COALESCE(g.upah_takaran_daging,0) ), 0) +
                            COALESCE(SUM( (COALESCE(p.berat_kopra,0) / NULLIF(g.takaran_kopra,0)) * COALESCE(g.upah_takaran_kopra,0) ), 0)
                        ) AS upah_perjalanan,
                        COALESCE(SUM(COALESCE(p.bonus,0)), 0) AS bonus_total,
                        (
                            (
                                COALESCE(SUM( (COALESCE(p.berat_daging,0) / NULLIF(g.takaran_daging,0)) * COALESCE(g.upah_takaran_daging,0) ), 0) +
                                COALESCE(SUM( (COALESCE(p.berat_kopra,0) / NULLIF(g.takaran_kopra,0)) * COALESCE(g.upah_takaran_kopra,0) ), 0)
                            )
                            + COALESCE(SUM(COALESCE(p.bonus,0)), 0)
                        ) AS total_gaji_bersih
                    ", false)
                ->from('mt_pengiriman p')
                ->join('m_gudang g', 'g.m_gudang_id = p.gudang_id', 'left')
                ->join('mt_pegawai pg', 'pg.kd_pegawai = p.kd_pegawai', 'left')
                ->where('p.is_stat_gaji', 0);

        if (!empty($filters['kd_pegawai'])) {
            $kdPegawai = is_array($filters['kd_pegawai']) ? $filters['kd_pegawai'] : [$filters['kd_pegawai']];
            $upah->whereIn('p.kd_pegawai', $kdPegawai);
        }

        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $upah->where('p.gudang_id', (int)$filters['gudang_id']);
        }

        if (!empty($start)) {
            $upah->where('p.tg_pengiriman >=', $start);
        }
        if (!empty($end)) {
            $upah->where('p.tg_pengiriman <=', $end);
        }

        return $upah
                ->groupBy('p.kd_pegawai, pg.nama, p.gudang_id, g.nama')
                ->findAll();
    }

    public function saveDataPengiriman(array $data, $pengirimanId = null): bool
    {
        $data['mt_pengiriman_id'] = $pengirimanId;

        $this->db->transStart();

        $exists = $this->where('mt_pengiriman_id', $pengirimanId)->countAllResults() > 0;

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
