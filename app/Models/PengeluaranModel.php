<?php

namespace App\Models;

use CodeIgniter\Model;

class PengeluaranModel extends Model
{
    protected $table         = 'mt_pengeluaran';
    protected $primaryKey    = 'mt_pengeluaran_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'tg_pengeluaran',
        'ktg_pengeluaran_id',
        'gudang_id',
        'jumlah',
        'biaya',
        'kd_pegawai',
        'status',
        'created_by',
        'updated_by',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataPengeluaran(array $filters = []): array
    {
        $pengeluaran = $this->select('
                    mt_pengeluaran.mt_pengeluaran_id,
                    mt_pengeluaran.tg_pengeluaran,
                    mt_pengeluaran.ktg_pengeluaran_id,
                    mt_pengeluaran.gudang_id,
                    mt_pengeluaran.jumlah,
                    mt_pengeluaran.biaya,
                    mt_pengeluaran.kd_pegawai,
                    mt_pengeluaran.status,
                    m_ktg_pengeluaran.nama AS nama_ktg_pengeluaran,
                    m_gudang.nama AS nama_gudang,
                    mt_pegawai.nama AS nama_pegawai,
                ')
                ->join('m_ktg_pengeluaran', 'm_ktg_pengeluaran.m_ktg_pengeluaran_id = mt_pengeluaran.ktg_pengeluaran_id', 'left')
                ->join('m_gudang', 'm_gudang.m_gudang_id = mt_pengeluaran.gudang_id', 'left')
                ->join('mt_pegawai', 'mt_pegawai.kd_pegawai = mt_pengeluaran.kd_pegawai', 'left');
        
        if (isset($filters['mt_pengeluaran_id']) && is_numeric($filters['mt_pengeluaran_id'])) {
            $pengeluaran->where('mt_pengeluaran.mt_pengeluaran_id', (int)$filters['mt_pengeluaran_id']);
        }
        
        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $pengeluaran->where('mt_pengeluaran.gudang_id', (int)$filters['gudang_id']);
        }

        if (!empty($filters['tg_pengeluaran_start'])) {
            $pengeluaran->where('mt_pengeluaran.tg_pengeluaran >=', $filters['tg_pengeluaran_start']);
        }

        if (!empty($filters['tg_pengeluaran_end'])) {
            $pengeluaran->where('mt_pengeluaran.tg_pengeluaran <=', $filters['tg_pengeluaran_end']);
        }
        
		return $pengeluaran->orderBy('mt_pengeluaran.tg_pengeluaran', 'DESC')
                            ->findAll();
    }

    public function saveDataPengeluaran(array $data, $pengeluaranId = null): bool
    {
        $data['mt_pengeluaran_id'] = $pengeluaranId;

        $this->db->transStart();

        $exists = $this->where('mt_pengeluaran_id', $pengeluaranId)->countAllResults() > 0;

        if ($exists) {
            $ok = $this->update($pengeluaranId, $data);
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