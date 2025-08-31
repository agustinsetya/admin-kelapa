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

    public function getDataKategoriPengeluaran()
    {
        $ktgPengeluaran = $this->select('
                    m_ktg_pengeluaran.m_ktg_pengeluaran_id,
                    m_ktg_pengeluaran.nama,
                    m_ktg_pengeluaran.keterangan
                ')
                ->findAll();
        
		return $ktgPengeluaran;
    }
}