<?php

namespace App\Models;

use CodeIgniter\Model;

class PengolahanModel extends Model
{
    protected $table         = 'mt_pembelian';
    protected $primaryKey    = 'mt_pembelian_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'tg_pembelian',
        'gudang_id',
        'berat_kelapa',
        'created_at',
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
                    mt_pembelian.berat_kelapa,
                    m_gudang.nama AS nama_gudang,
                    mt_pembelian.created_at,
                ')
                ->join('m_gudang', 'm_gudang.m_gudang_id = mt_pembelian.gudang_id', 'left');
        
        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $pembelian->where('mt_pembelian.gudang_id', (int)$filters['gudang_id']);
        }
        
		return $pembelian->findAll();
    }

    public function updatePembelian(array $data, $gudangId): bool
    {
        $data['gudang_id'] = $gudangId;

        $this->db->transStart();

        $exists = $this->where('gudang_id', $gudangId)->countAllResults() > 0;

        if ($exists) {
            $ok = $this->update($gudangId, $data);
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