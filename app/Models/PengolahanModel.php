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
        'berat_kulit',
        'bonus',
        'tg_proses_gaji',
        'is_stat_gaji',
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
                    mt_pengolahan.berat_kulit,
                    mt_pengolahan.bonus,
                    mt_pengolahan.tg_proses_gaji,
                    mt_pengolahan.is_stat_gaji,
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

        if (isset($filters['kd_pegawai'])) {
            $pengolahan->where('mt_pengolahan.kd_pegawai', (int)$filters['kd_pegawai']);
        }

        if (!empty($filters['start_date'])) {
            $pengolahan->where('mt_pengolahan.tg_pengolahan >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $pengolahan->where('mt_pengolahan.tg_pengolahan <=', $filters['end_date']);
        }

        return $pengolahan->findAll();
    }
    
    public function getDataUpahProduksi(array $filters = []): array
    {
        $start = $filters['start_date'] ?? null;
        $end   = $filters['end_date'] ?? null;

        $upah = $this->db->table('mt_pengolahan p');
        $upah->select("
            p.kd_pegawai, pg.nama AS nama_pegawai, p.gudang_id, g.nama AS nama_gudang,
            SUM(ROUND((COALESCE(p.berat_daging, 0) / NULLIF(g.takaran_daging, 0)) * COALESCE(g.upah_takaran_daging, 0), 0)) AS total_upah_daging,
            SUM(ROUND((COALESCE(p.berat_kopra, 0) / NULLIF(g.takaran_kopra, 0)) * COALESCE(g.upah_takaran_kopra, 0), 0)) AS total_upah_kopra,
            SUM(ROUND((COALESCE(p.berat_kulit, 0) / NULLIF(g.takaran_kulit, 0)) * COALESCE(g.upah_takaran_kulit, 0), 0)) AS total_upah_kulit,
            (
                SUM(ROUND((COALESCE(p.berat_daging, 0) / NULLIF(g.takaran_daging, 0)) * COALESCE(g.upah_takaran_daging, 0), 0)) +
                SUM(ROUND((COALESCE(p.berat_kopra, 0) / NULLIF(g.takaran_kopra, 0)) * COALESCE(g.upah_takaran_kopra, 0), 0)) +
                SUM(ROUND((COALESCE(p.berat_kulit, 0) / NULLIF(g.takaran_kulit, 0)) * COALESCE(g.upah_takaran_kulit, 0), 0))
            ) AS total_upah_produksi,
            SUM(COALESCE(p.bonus, 0)) AS total_bonus,
            (
                SUM(ROUND((COALESCE(p.berat_daging, 0) / NULLIF(g.takaran_daging, 0)) * COALESCE(g.upah_takaran_daging, 0), 0)) +
                SUM(ROUND((COALESCE(p.berat_kopra, 0) / NULLIF(g.takaran_kopra, 0)) * COALESCE(g.upah_takaran_kopra, 0), 0)) +
                SUM(ROUND((COALESCE(p.berat_kulit, 0) / NULLIF(g.takaran_kulit, 0)) * COALESCE(g.upah_takaran_kulit, 0), 0)) +
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
            $upah->where('p.tg_pengolahan >=', $start);
        }

        if (!empty($end)) {
            $upah->where('p.tg_pengolahan <=', $end);
        }

        $upah->groupBy('p.kd_pegawai, p.gudang_id');

        return $upah->get()->getResultArray();
    }

    public function saveDataPengolahan(array $data, $pengolahanId = null): bool
    {
        if ($pengolahanId !== null) {
            $data['mt_pengolahan_id'] = $pengolahanId;
        }

        $this->db->transStart();

        $exists = $pengolahanId !== null && $this->where('mt_pengolahan_id', $pengolahanId)->countAllResults() > 0;

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