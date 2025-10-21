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