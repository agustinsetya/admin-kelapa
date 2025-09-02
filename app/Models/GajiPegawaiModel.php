<?php

namespace App\Models;

use CodeIgniter\Model;

class GajiPegawaiModel extends Model
{
    protected $table         = 'mt_gaji';
    protected $primaryKey    = 'mt_gaji_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'periode',
        'kd_pegawai',
        'upah_produksi',
        'lembur',
        'bonus',
        'potongan',
        'total_bruto',
        'total_netto',
        'tg_pembayaran',
        'status_pembayaran',
        'created_at',
        'updated_by',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataGajiPegawai(array $filters = []): array
    {
        $pengolahan = $this->select('
                    mt_gaji.mt_gaji_id,
                    mt_gaji.tg_pengolahan,
                    mt_gaji.gudang_id,
                    mt_gaji.kd_pegawai,
                    mt_gaji.berat_daging,
                    mt_gaji.berat_kopra,
                    m_gudang.nama AS nama_gudang,
                    mt_pegawai.nama AS nama_pegawai,
                    mt_gaji.created_at,
                ')
            ->join('m_gudang', 'm_gudang.m_gudang_id = mt_gaji.gudang_id', 'left')
            ->join('mt_pegawai', 'mt_pegawai.kd_pegawai = mt_gaji.kd_pegawai', 'left')
            ->orderby('mt_gaji.tg_pengolahan DESC');

        if (isset($filters['mt_gaji_id']) && is_numeric($filters['mt_gaji_id'])) {
            $pengolahan->where('mt_gaji.mt_gaji_id', (int)$filters['mt_gaji_id']);
        }

        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $pengolahan->where('mt_gaji.gudang_id', (int)$filters['gudang_id']);
        }

        if (isset($filters['kd_pegawai']) && is_numeric($filters['kd_pegawai'])) {
            $pengolahan->where('mt_gaji.kd_pegawai', (int)$filters['kd_pegawai']);
        }

        if (!empty($filters['start_date'])) {
            $pengolahan->where('mt_gaji.tg_pengolahan >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $pengolahan->where('mt_gaji.tg_pengolahan <=', $filters['end_date']);
        }

        return $pengolahan->findAll();
    }

    public function prosesGajiPegawai(
        array $upahPegawai = [],
        string $createdBy,
        ?string $periodeStart,
        ?string $periodeEnd
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
                'periode'           => $periodeStart,
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

            $ok = $tb->set('is_stat_gaji', 1)->update();

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
