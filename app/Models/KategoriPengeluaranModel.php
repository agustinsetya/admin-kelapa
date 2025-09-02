<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriPengeluaranModel extends Model
{
    protected $table         = 'm_ktg_pengeluaran';
    protected $primaryKey    = 'm_ktg_pengeluaran_id';
    protected $returnType    = 'object';
    protected $allowedFields = ['nama','keterangan'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataKategoriPengeluaran(array $filters = []): array
    {
        $ktgPengeluaran = $this->select('
                    m_ktg_pengeluaran.m_ktg_pengeluaran_id,
                    m_ktg_pengeluaran.nama,
                    m_ktg_pengeluaran.keterangan
                ');
        
        if (isset($filters['m_ktg_pengeluaran_id']) && is_numeric($filters['m_ktg_pengeluaran_id'])) {
            $ktgPengeluaran->where('m_ktg_pengeluaran.m_ktg_pengeluaran_id', (int)$filters['m_ktg_pengeluaran_id']);
        }
        
        return $ktgPengeluaran->findAll();
    }

    public function saveDataKategoriPengeluaran(array $data, $kategoriId = null): bool
    {
        $data['m_ktg_pengeluaran_id'] = $kategoriId;

        $this->db->transStart();

        $exists = $this->where('m_ktg_pengeluaran_id', $kategoriId)->countAllResults() > 0;

        if ($exists) {
            $ok = $this->update($kategoriId, $data);
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