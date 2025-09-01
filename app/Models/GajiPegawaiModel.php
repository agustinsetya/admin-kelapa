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

    public function prosesBatchGaji(array $upahPegawai = [], string $createdBy): bool
    {
        $this->db->transBegin();

        foreach ($upahPegawai as $row) {
            $data = [
                'periode'      => date('Y-m-d'),
                'kd_pegawai'   => $row->kd_pegawai,
                'biaya'        => $row->total_gaji_bersih,
                'jumlah'       => 1,
                'status'              => 'lunas',
                'created_by'          => $createdBy,
            ];

            $saved = $this->insert($data); // atau `save($data)` jika kamu pakai `save()` CI4
            if ($saved === false) {
                $this->db->transRollback();
                return false;
            }
        }

        $this->db->transCommit();
        return true;
    }
}
