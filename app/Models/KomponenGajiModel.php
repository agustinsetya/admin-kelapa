<?php

namespace App\Models;

use CodeIgniter\Model;

class KomponenGajiModel extends Model
{
    protected $table         = 'm_komponen_gaji';
    protected $primaryKey    = 'm_komponen_gaji_id';
    protected $returnType    = 'object';
    protected $allowedFields = ['gudang_id','takaran_daging','upah_takaran_daging','takaran_kopra','upah_takaran_kopra'];
    protected $useTimestamps = true;

    public function getDataKomponenGaji()
    {
        $komponenGaji = $this->select('
                    m_komponen_gaji.m_komponen_gaji_id,
                    m_komponen_gaji.gudang_id,
                    m_komponen_gaji.takaran_daging,
                    m_komponen_gaji.upah_takaran_daging,
                    m_komponen_gaji.takaran_kopra,
                    m_komponen_gaji.upah_takaran_kopra,
                    m_gudang.nama AS nama_gudang
                ')
                ->join('m_gudang', 'm_gudang.m_gudang_id = m_komponen_gaji.gudang_id', 'left')
                ->findAll();
        
		return $komponenGaji;
    }
}