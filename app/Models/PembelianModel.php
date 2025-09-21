<?php

namespace App\Models;

use CodeIgniter\Model;

class PembelianModel extends Model
{
    protected $table         = 'mt_pembelian';
    protected $primaryKey    = 'mt_pembelian_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'tg_pembelian',
        'gudang_id',
        'kode_container',
        'berat_kelapa',
        'hasil_olahan_daging',
        'hasil_olahan_kopra',
        'hasil_olahan_kulit',
        'is_proses',
        'created_by',
        'updated_by',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataPembelian(array $filters = []): array
    {
        $pembelian = $this->select('
                    mt_pembelian.mt_pembelian_id,
                    mt_pembelian.tg_pembelian,
                    mt_pembelian.gudang_id,
                    mt_pembelian.kode_container,
                    mt_pembelian.berat_kelapa,
                    mt_pembelian.hasil_olahan_daging,
                    mt_pembelian.hasil_olahan_kopra,
                    mt_pembelian.hasil_olahan_kulit,
                    mt_pembelian.is_proses,
                    m_gudang.nama AS nama_gudang,
                    mt_pembelian.created_at,
                ')
                ->join('m_gudang', 'm_gudang.m_gudang_id = mt_pembelian.gudang_id', 'left');
        
        if (isset($filters['mt_pembelian_id']) && is_numeric($filters['mt_pembelian_id'])) {
            $pembelian->where('mt_pembelian.mt_pembelian_id', (int)$filters['mt_pembelian_id']);
        }
        
        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $pembelian->where('mt_pembelian.gudang_id', (int)$filters['gudang_id']);
        }
        
        if (isset($filters['kode_container'])) {
            $pembelian->where('mt_pembelian.kode_container', $filters['kode_container']);
        }
        
		return $pembelian->findAll();
    }

    public function saveDataPembelian(array $data, $pembelianId = null): bool
    {
        $data['mt_pembelian_id'] = $pembelianId;

        $this->db->transStart();

        $exists = $this->where('mt_pembelian_id', $pembelianId)->countAllResults() > 0;

        if ($exists) {
            $ok = $this->update($pembelianId, $data);
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