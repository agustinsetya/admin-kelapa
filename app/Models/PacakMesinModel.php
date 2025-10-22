<?php

namespace App\Models;

use CodeIgniter\Model;

class PacakMesinModel extends Model
{
    protected $table         = 'mt_pacak_mesin';
    protected $primaryKey    = 'mt_pacak_mesin_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'tg_pacak_mesin',
        'gudang_id',
        'kd_pegawai',
        'jumlah_kelapa',
        'bonus',
        'tg_proses_gaji',
        'is_stat_gaji',
        'created_by',
        'updated_by',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataPacakMesin(array $filters = []): array
    {
        $pacak_mesin = $this->select('
                    mt_pacak_mesin.mt_pacak_mesin_id,
                    mt_pacak_mesin.tg_pacak_mesin,
                    mt_pacak_mesin.gudang_id,
                    mt_pacak_mesin.kd_pegawai,
                    mt_pacak_mesin.jumlah_kelapa,
                    mt_pacak_mesin.bonus,
                    mt_pacak_mesin.tg_proses_gaji,
                    mt_pacak_mesin.is_stat_gaji,
                    m_gudang.nama AS nama_gudang,
                    mt_pegawai.nama AS nama_pegawai,
                    mt_pacak_mesin.created_at,
                ')
                ->join('m_gudang', 'm_gudang.m_gudang_id = mt_pacak_mesin.gudang_id', 'left')
                ->join('mt_pegawai', 'mt_pegawai.kd_pegawai = mt_pacak_mesin.kd_pegawai', 'left')
                ->orderby('mt_pacak_mesin.tg_pacak_mesin DESC');
        
        if (isset($filters['mt_pacak_mesin_id']) && is_numeric($filters['mt_pacak_mesin_id'])) {
            $pacak_mesin->where('mt_pacak_mesin.mt_pacak_mesin_id', (int)$filters['mt_pacak_mesin_id']);
        }
        
        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $pacak_mesin->where('mt_pacak_mesin.gudang_id', (int)$filters['gudang_id']);
        }

        if (isset($filters['kd_pegawai'])) {
            $pacak_mesin->where('mt_pacak_mesin.kd_pegawai', (int)$filters['kd_pegawai']);
        }

        if (!empty($filters['start_date'])) {
            $pacak_mesin->where('mt_pacak_mesin.tg_pacak_mesin >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $pacak_mesin->where('mt_pacak_mesin.tg_pacak_mesin <=', $filters['end_date']);
        }
        
		return $pacak_mesin->findAll();
    }

    public function getDataUpahPacakMesin(array $filters = []): array
    {
        $start = $filters['start_date'] ?? null;
        $end   = $filters['end_date'] ?? null;

        $upah = $this->db->table('mt_pacak_mesin p');
        $upah->select("
            p.kd_pegawai, pg.nama AS nama_pacak_mesin, p.gudang_id, g.nama AS nama_gudang,
            SUM(COALESCE(p.jumlah_kelapa, 0) * COALESCE(g.gaji_pacak_mesin, 0)) AS total_upah_pacak_mesin,
            SUM(COALESCE(p.bonus, 0)) AS total_bonus,
            (
                SUM(COALESCE(p.jumlah_kelapa, 0) * COALESCE(g.gaji_pacak_mesin, 0)) +
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
            $upah->where('p.tg_pacak_mesin >=', $start);
        }

        if (!empty($end)) {
            $upah->where('p.tg_pacak_mesin <=', $end);
        }

        $upah->groupBy('p.kd_pegawai, p.gudang_id');

        return $upah->get()->getResultArray();
    }

    public function saveDataPacakMesin(array $data, $pacakMesinId = null): bool
    {
        $data['mt_pacak_mesin_id'] = $pacakMesinId;

        $this->db->transStart();

        $exists = $this->where('mt_pacak_mesin_id', $pacakMesinId)->countAllResults() > 0;

        if ($exists) {
            $ok = $this->update($pacakMesinId, $data);
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
    
    public function deleteDataPacak_mesin($pacakMesinId): bool
    {
        $data['mt_pacak_mesin_id'] = $pacakMesinId;

        $this->db->transStart();

        $exists = $this->where('mt_pacak_mesin_id', $pacakMesinId)->countAllResults() > 0;

        if ($exists) {
            $ok = $this->delete($pacakMesinId);
        }

        if (!$ok) {
            $this->db->transRollback();
            return false;
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }
}