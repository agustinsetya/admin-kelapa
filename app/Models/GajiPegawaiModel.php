<?php

namespace App\Models;

use CodeIgniter\Model;

class GajiPegawaiModel extends Model
{
    protected $table         = 'mt_gaji_pegawai';
    protected $primaryKey    = 'mt_gaji_pegawai_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'tg_proses_gaji',
        'kd_pegawai',
        'gudang_id',
        'total_upah_daging',
        'total_upah_kopra',
        'total_upah_produksi',
        'total_bonus',
        'total_gaji_bersih',
        'created_at',
        'updated_by',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataGajiPegawai(array $filters = []): array
    {
        $gajiPegawai = $this->select('
                    mt_gaji_pegawai.mt_gaji_pegawai_id,
                    mt_gaji_pegawai.tg_proses_gaji,
                    mt_gaji_pegawai.gudang_id,
                    mt_gaji_pegawai.kd_pegawai,
                    mt_gaji_pegawai.total_upah_daging,
                    mt_gaji_pegawai.total_upah_kopra,
                    mt_gaji_pegawai.total_bonus,
                    mt_gaji_pegawai.total_gaji_bersih,
                    m_gudang.nama AS nama_gudang,
                    mt_pegawai.nama AS nama_pegawai,
                    mt_gaji_pegawai.created_at,
                ')
            ->join('m_gudang', 'm_gudang.m_gudang_id = mt_gaji_pegawai.gudang_id', 'left')
            ->join('mt_pegawai', 'mt_pegawai.kd_pegawai = mt_gaji_pegawai.kd_pegawai', 'left')
            ->orderby('mt_gaji_pegawai.tg_proses_gaji DESC');

        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $gajiPegawai->where('mt_gaji_pegawai.gudang_id', (int)$filters['gudang_id']);
        }

        if (isset($filters['kd_pegawai']) && is_numeric($filters['kd_pegawai'])) {
            $gajiPegawai->where('mt_gaji_pegawai.kd_pegawai', (int)$filters['kd_pegawai']);
        }

        if (!empty($filters['start_date'])) {
            $gajiPegawai->where('mt_gaji_pegawai.tg_proses_gaji >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $gajiPegawai->where('mt_gaji_pegawai.tg_proses_gaji <=', $filters['end_date']);
        }

        return $gajiPegawai->findAll();
    }

    public function prosesGajiPegawai(
        string $createdBy,
        ?string $periodeStart,
        ?string $periodeEnd,
        array $upahPegawai = []
    ): bool {
        $this->db->transBegin();
    
        if ($periodeStart) $periodeStart = date('Y-m-d', strtotime($periodeStart));
        if ($periodeEnd)   $periodeEnd   = date('Y-m-d', strtotime($periodeEnd));
    
        foreach ($upahPegawai as $row) {
            if (empty($row['kd_pegawai']) || empty($row['gudang_id'])) {
                log_message('error', 'GAJI FAIL: kd_pegawai/gudang_id kosong');
                $this->db->transRollback();
                return false;
            }
    
            $data = [
                'tg_proses_gaji'        => date('Y-m-d'),
                'kd_pegawai'            => $row['kd_pegawai'],
                'gudang_id'             => (int)$row['gudang_id'],
                'total_upah_daging'     => $row['total_upah_daging'] ?? 0,
                'total_upah_kopra'      => $row['total_upah_kopra'] ?? 0,
                'total_upah_produksi'   => $row['total_upah_produksi'] ?? 0,
                'total_bonus'           => $row['total_bonus'] ?? 0,
                'total_gaji_bersih'     => $row['total_gaji_bersih'] ?? 0,
                'created_by'            => $createdBy,
            ];
    
            $saved = $this->insert($data);
            if ($saved === false) {
                log_message('error', 'GAJI FAIL INSERT data: ' . json_encode($data));
                $lastQuery = $this->db->getLastQuery();
                if ($lastQuery) {
                    log_message('error', 'GAJI FAIL INSERT SQL: ' . $lastQuery->getQuery());
                }
                $this->db->transRollback();
                return false;
            }
        }
    
        foreach ($upahPegawai as $row) {
            $tb = $this->db->table('mt_pengolahan')
                ->where('kd_pegawai', (int)$row['kd_pegawai'])
                ->where('gudang_id',  (int)$row['gudang_id'])
                ->where('is_stat_gaji', 0);
    
            if ($periodeStart && $periodeEnd) {
                $tb->where('tg_pengolahan >=', $periodeStart)
                   ->where('tg_pengolahan <=', $periodeEnd);
            }
    
            $ok = $tb->set([
                'is_stat_gaji'   => 1,
                'tg_proses_gaji' => date('Y-m-d'),
                'updated_by'     => $createdBy,
            ])->update();
    
            if ($ok === false) {
                $lastQuery = $this->db->getLastQuery();
                log_message('error', '[UPDATE pengolahan FAIL] ' . ($lastQuery ? $lastQuery->getQuery() : 'No query'));
                $this->db->transRollback();
                return false;
            }
        }
    
        $this->db->transCommit();
        return true;
    }
}