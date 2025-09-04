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
        'upah_total_daging',
        'upah_total_kopra',
        'upah_produksi',
        'bonus',
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
                    mt_gaji_pegawai.upah_total_daging,
                    mt_gaji_pegawai.upah_total_kopra,
                    mt_gaji_pegawai.bonus,
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
        array $upahPegawai = [],
    ): bool
    {
        $this->db->transBegin();

        if ($periodeStart) $periodeStart = date('Y-m-d', strtotime($periodeStart));
        if ($periodeEnd)   $periodeEnd   = date('Y-m-d', strtotime($periodeEnd));

        foreach ($upahPegawai as $row) {
            if (empty($row->kd_pegawai) || empty($row->gudang_id)) {
                log_message('error', 'GAJI FAIL: kd_pegawai/gudang_id kosong');
                $this->db->transRollback();
                return false;
            }

            $data = [
                'tg_proses_gaji'    => date('Y-m-d'),
                'kd_pegawai'        => (int)$row->kd_pegawai,
                'gudang_id'         => (int)$row->gudang_id,
                'upah_total_daging' => (float)$row->upah_total_daging,
                'upah_total_kopra'  => (float)$row->upah_total_kopra,
                'upah_produksi'     => (float)$row->upah_produksi,
                'bonus'             => (float)$row->bonus_total,
                'total_gaji_bersih' => (float)$row->total_gaji_bersih,
                'created_by'        => $createdBy,
            ];

            $saved = $this->insert($data);
            if ($saved === false) {
                log_message('error', 'GAJI FAIL INSERT data: '. json_encode($data));
                $lastQuery = $this->db->getLastQuery();
                if ($lastQuery) {
                    log_message('error', 'GAJI FAIL INSERT SQL: ' . $lastQuery->getQuery());
                }
                log_message('error', 'GAJI DB-SCHEMA: ' . $this->db->getDatabase());
                $this->db->transRollback();
                return false;
            }
        }

        foreach ($upahPegawai as $row) {
            $tb = $this->db->table('mt_pengolahan')
                ->where('kd_pegawai', (int)$row->kd_pegawai)
                ->where('gudang_id',  (int)$row->gudang_id)
                ->where('is_stat_gaji', 0);

            if ($periodeStart && $periodeEnd) {
                $tb->where('tg_pengolahan >=', $periodeStart)
                ->where('tg_pengolahan <=', $periodeEnd);
            }

            $ok = $tb->set([
                'is_stat_gaji'      => 1,
                'tg_proses_gaji'    => $createdBy,
                'updated_by'        => $createdBy,
            ])->update();

            if ($ok === false) {
                log_message('error', '[UPDATE pengolahan FAIL] ' . $this->db->getLastQuery()->getQuery());
                $this->db->transRollback();
                return false;
            }
        }

        log_message(
            'debug',
            '[DEBUG] Upah Produksi SQL: ' . json_encode(
                $this->db->getLastQuery()->getQuery(),
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            )
        );

        $this->db->transCommit();
        return true;
    }
}
