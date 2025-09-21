<?php

namespace App\Models;

use CodeIgniter\Model;

class GudangModel extends Model
{
    protected $table         = 'm_gudang';
    protected $primaryKey    = 'm_gudang_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'nama',
        'takaran_daging',
        'upah_takaran_daging',
        'takaran_kopra',
        'upah_takaran_kopra',
        'takaran_kulit',
        'upah_takaran_kulit',
        'gaji_driver_distribusi',
        'gaji_driver_ngepok_truk',
        'gaji_driver_ngepok_pickup',
        'created_by',
        'updated_by',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataGudang(?array $filters = null): array
    {
        $filters = $filters ?? [];

        $gudang = $this->select('
                    m_gudang.m_gudang_id,
                    m_gudang.nama,
                    m_gudang.takaran_daging,
                    m_gudang.upah_takaran_daging,
                    m_gudang.takaran_kopra,
                    m_gudang.upah_takaran_kopra,
                    m_gudang.takaran_kulit,
                    m_gudang.upah_takaran_kulit,
                    m_gudang.gaji_driver_distribusi,
                    m_gudang.gaji_driver_ngepok_truk,
                    m_gudang.gaji_driver_ngepok_pickup,
                ');

        if (isset($filters['m_gudang_id']) && is_numeric($filters['m_gudang_id'])) {
            $gudang->where('m_gudang.m_gudang_id', (int)$filters['m_gudang_id']);
        }

        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $gudang->where('m_gudang.m_gudang_id', (int)$filters['gudang_id']);
        }
        
		return $gudang->findAll();
    }

    public function saveDataGudang(array $data, $gudangId = null): bool
    {
        $this->db->transStart();

        if ($gudangId) {
            $ok = $this->update($gudangId, $data);
            $finalGudangId = $gudangId;
        } else {
            $ok = $this->insert($data, false);
            $finalGudangId = $this->getInsertID();
        }

        if (!$ok || !$finalGudangId) {
            $this->db->transRollback();
            return false;
        }

        $komponenData = [
            'gudang_id'                 => $finalGudangId,
            'takaran_daging'            => $data['takaran_daging'] ?? 0,
            'upah_takaran_daging'       => $data['upah_takaran_daging'] ?? 0,
            'takaran_kopra'             => $data['takaran_kopra'] ?? 0,
            'upah_takaran_kopra'        => $data['upah_takaran_kopra'] ?? 0,
            'takaran_kulit'             => $data['takaran_kulit'] ?? 0,
            'upah_takaran_kulit'        => $data['upah_takaran_kulit'] ?? 0,
            'gaji_driver_distribusi'    => $data['gaji_driver_distribusi'] ?? 0,
            'gaji_driver_ngepok_truk'   => $data['gaji_driver_ngepok_truk'] ?? 0,
            'gaji_driver_ngepok_pickup' => $data['gaji_driver_ngepok_pickup'] ?? 0,
            'created_by'                => $data['created_by'] ?? $data['updated_by'],
        ];

        $komponenModel = new \App\Models\KomponenGajiModel();
        if (!$komponenModel->insert($komponenData)) {
            $this->db->transRollback();
            return false;
        }

        $this->db->transComplete();
        return $this->db->transStatus() ? $finalGudangId : false;
    }
}