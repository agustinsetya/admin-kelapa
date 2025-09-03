<?php

namespace App\Models;

use CodeIgniter\Model;

class GajiDriverModel extends Model
{
    protected $table         = 'mt_gaji_driver';
    protected $primaryKey    = 'mt_gaji_driver_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'tg_proses_gaji',
        'kd_pegawai',
        'gudang_id',
        'upah_perjalanan',
        'bonus',
        'total_gaji_bersih',
        'created_at',
        'updated_by',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataGajiDriver(array $filters = []): array
    {
        $gajiDriver = $this->select('
                    mt_gaji_driver.mt_gaji_driver_id,
                    mt_gaji_driver.tg_proses_gaji,
                    mt_gaji_driver.gudang_id,
                    mt_gaji_driver.kd_pegawai,
                    mt_gaji_driver.bonus,
                    mt_gaji_driver.total_gaji_bersih,
                    m_gudang.nama AS nama_gudang,
                    mt_pegawai.nama AS nama_driver,
                    mt_gaji_driver.created_at,
                ')
            ->join('m_gudang', 'm_gudang.m_gudang_id = mt_gaji_driver.gudang_id', 'left')
            ->join('mt_pegawai', 'mt_pegawai.kd_pegawai = mt_gaji_driver.kd_pegawai', 'left')
            ->orderby('mt_gaji_driver.tg_proses_gaji DESC');

        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $gajiDriver->where('mt_gaji_driver.gudang_id', (int)$filters['gudang_id']);
        }

        if (isset($filters['kd_pegawai']) && is_numeric($filters['kd_pegawai'])) {
            $gajiDriver->where('mt_gaji_driver.kd_pegawai', (int)$filters['kd_pegawai']);
        }

        if (!empty($filters['start_date'])) {
            $gajiDriver->where('mt_gaji_driver.tg_proses_gaji >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $gajiDriver->where('mt_gaji_driver.tg_proses_gaji <=', $filters['end_date']);
        }

        return $gajiDriver->findAll();
    }

    public function prosesGajiDriver(
        array $upahDriver = [],
        string $createdBy,
        ?string $periodeStart,
        ?string $periodeEnd
    ): bool
    {
        $this->db->transBegin();

        if ($periodeStart) $periodeStart = date('Y-m-d', strtotime($periodeStart));
        if ($periodeEnd)   $periodeEnd   = date('Y-m-d', strtotime($periodeEnd));

        foreach ($upahDriver as $row) {
            if (empty($row->kd_pegawai) || empty($row->gudang_id)) {
                log_message('error', 'GAJI FAIL: kd_pegawai/gudang_id kosong');
                $this->db->transRollback();
                return false;
            }

            $data = [
                'tg_proses_gaji'    => date('Y-m-d'),
                'kd_pegawai'        => (int)$row->kd_pegawai,
                'gudang_id'         => (int)$row->gudang_id,
                'upah_perjalanan'     => (float)$row->upah_perjalanan,
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

        foreach ($upahDriver as $row) {
            $tb = $this->db->table('mt_pengiriman')
                ->where('kd_pegawai', (int)$row->kd_pegawai)
                ->where('gudang_id',  (int)$row->gudang_id)
                ->where('is_stat_gaji', 0);

            if ($periodeStart && $periodeEnd) {
                $tb->where('tg_pengiriman >=', $periodeStart)
                ->where('tg_pengiriman <=', $periodeEnd);
            }

            $ok = $tb->set([
                'is_stat_gaji'      => 1,
                'tg_proses_gaji'    => $createdBy,
                'updated_by'        => $createdBy,
            ])->update();

            if ($ok === false) {
                log_message('error', '[UPDATE pengiriman FAIL] ' . $this->db->getLastQuery()->getQuery());
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
