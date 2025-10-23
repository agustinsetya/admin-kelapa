<?php

namespace App\Models;

use CodeIgniter\Model;

class KasbonModel extends Model
{
    protected $table         = 'mt_kasbon';
    protected $primaryKey    = 'mt_kasbon_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'kd_pegawai',
        'total_pinjaman',
        'sisa_pinjaman',
        'status',
        'created_by',
        'updated_by',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataKasbon(array $filters = []): array
    {
        $kasbon = $this->select('
                    mt_kasbon.mt_kasbon_id,
                    mt_kasbon.kd_pegawai,
                    mt_kasbon.total_pinjaman,
                    mt_kasbon.sisa_pinjaman,
                    mt_kasbon.status,
                    mt_pegawai.penempatan_id AS gudang_id,
                    m_gudang.nama AS nama_gudang,
                    mt_pegawai.nama AS nama_pegawai,
                    mt_kasbon.updated_at,
                ')
                ->join('mt_pegawai', 'mt_pegawai.kd_pegawai = mt_kasbon.kd_pegawai', 'left')
                ->join('m_gudang', 'm_gudang.m_gudang_id = mt_pegawai.penempatan_id', 'left');
        
        if (isset($filters['mt_kasbon_id']) && is_numeric($filters['mt_kasbon_id'])) {
            $kasbon->where('mt_kasbon.mt_kasbon_id', (int)$filters['mt_kasbon_id']);
        }
        
        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $kasbon->where('mt_pegawai.penempatan_id', (int)$filters['gudang_id']);
        }
        
		return $kasbon->orderBy('mt_kasbon.updated_at', 'DESC')
                            ->findAll();
    }

    public function saveDataKasbon(array $data): bool
    {
        $this->db->transStart();

        $logKasbonModel  = new \App\Models\LogKasbonModel();

        $insertLogKasbon = $logKasbonModel->insert($data, false);

        if (!$insertLogKasbon) {
            $this->db->transRollback();
            return false;
        }

        // 1. Periksa data di mt_kasbon
        $kasbonData = $this->where('kd_pegawai', $data['kd_pegawai'])->first();

        log_message('debug', 'Kasbon Data: ' . json_encode($kasbonData));

        if ($data['tipe'] == 'PEMBAYARAN' && !$kasbonData) {
            $this->db->transRollback();
            throw new \Exception('Belum ada data kasbon, lakukan PEMINJAMAN terlebih dahulu.');
        }

        // 2. Insert atau Update Data Kasbon
        if (!$kasbonData) {
            $newKasbonData = [
                'kd_pegawai'   => $data['kd_pegawai'],
                'total_pinjaman' => $data['jumlah'],
                'status'       => 'BELUM_LUNAS',
                'created_by'   => $data['created_by'],
            ];

            $kasbonSaved = $this->insert($newKasbonData);
            if ($kasbonSaved === false) {
                $this->db->transRollback();
                return false;
            }
        } else {
            $totalPinjaman = $kasbonData->total_pinjaman;
            $sisaPinjaman = $kasbonData->sisa_pinjaman;

            if ($data['tipe'] == 'PEMINJAMAN') {
                $totalPinjaman += $data['jumlah'];
                $sisaPinjaman += $data['jumlah'];
            } elseif ($data['tipe'] == 'PEMBAYARAN') {
                $sisaPinjaman -= $data['jumlah'];
            }

            if ($sisaPinjaman < 0) {
                $this->db->transRollback();
                throw new \Exception('Nominal Pelunasan Tidak Boleh Lebih Dari SISA PINJAMAN');
            }

            $status = ($sisaPinjaman == 0) ? 'LUNAS' : 'BELUM_LUNAS';

            $updateKasbonData = [
                'total_pinjaman' => $totalPinjaman,
                'sisa_pinjaman' => $sisaPinjaman,
                'status' => $status,
                'updated_by' => $data['created_by'],
            ];

            $kasbonUpdated = $this->update($kasbonData->mt_kasbon_id, $updateKasbonData);
            if ($kasbonUpdated === false) {
                log_message('error', 'Gagal mengupdate data kasbon');
                $this->db->transRollback();
                return false;
            }
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }
}