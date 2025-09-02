<?php

namespace App\Models;

use CodeIgniter\Model;

class KomponenGajiModel extends Model
{
    protected $table         = 'm_komponen_gaji';
    protected $primaryKey    = 'm_komponen_gaji_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'gudang_id',
        'takaran_daging',
        'upah_takaran_daging',
        'takaran_kopra',
        'upah_takaran_kopra',
        'gaji_driver',
        'created_by',
        'updated_by',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataKomponenGaji(array $filters = []): array
    {
        $komponenGaji = $this->select('
                    m_komponen_gaji.m_komponen_gaji_id,
                    m_komponen_gaji.gudang_id,
                    m_komponen_gaji.takaran_daging,
                    m_komponen_gaji.upah_takaran_daging,
                    m_komponen_gaji.takaran_kopra,
                    m_komponen_gaji.upah_takaran_kopra,
                    m_gudang.nama AS nama_gudang,
                    m_komponen_gaji.updated_at
                ')
                ->join('m_gudang', 'm_gudang.m_gudang_id = m_komponen_gaji.gudang_id', 'left');

        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $komponenGaji->where('m_komponen_gaji.gudang_id', (int)$filters['gudang_id']);
        }
        
		return $komponenGaji->findAll();
    }

    public function updateKomponenGaji(array $data, $gudangId): bool
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