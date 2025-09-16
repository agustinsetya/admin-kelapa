<?php

namespace App\Models;

use CodeIgniter\Model;

class KomponenGajiModel extends Model
{
    protected $table         = 'mt_komponen_gaji';
    protected $primaryKey    = 'mt_komponen_gaji_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'gudang_id',
        'takaran_daging',
        'upah_takaran_daging',
        'takaran_kopra',
        'upah_takaran_kopra',
        'takaran_kulit',
        'upah_takaran_kulit',
        'gaji_driver',
        'created_by',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
	protected $updatedField  = '';

    public function getDataKomponenGaji(array $filters = []): array
    {
        $komponenGaji = $this->select('
                    mt_komponen_gaji.mt_komponen_gaji_id,
                    mt_komponen_gaji.gudang_id,
                    mt_komponen_gaji.takaran_daging,
                    mt_komponen_gaji.upah_takaran_daging,
                    mt_komponen_gaji.takaran_kopra,
                    mt_komponen_gaji.upah_takaran_kopra,
                    mt_komponen_gaji.takaran_kulit,
                    mt_komponen_gaji.upah_takaran_kulit,
                    mt_komponen_gaji.gaji_driver,
                    m_gudang.nama AS nama_gudang,
                    mt_komponen_gaji.created_at
                ')
                ->join('m_gudang', 'm_gudang.m_gudang_id = mt_komponen_gaji.gudang_id', 'left');

        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $komponenGaji->where('mt_komponen_gaji.gudang_id', (int)$filters['gudang_id']);
        }
        
		return $komponenGaji
            ->orderBy('mt_komponen_gaji.created_at', 'DESC')    
            ->findAll();
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