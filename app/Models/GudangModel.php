<?php

namespace App\Models;

use CodeIgniter\Model;

class GudangModel extends Model
{
    protected $table         = 'm_gudang';
    protected $primaryKey    = 'm_gudang_id';
    protected $returnType    = 'object';
    protected $allowedFields = ['nama','status'];
    protected $useTimestamps = true;

    public function getDataGudang()
    {
        $gudang = $this->select('
                    m_gudang.m_gudang_id,
                    m_gudang.nama,
                    m_gudang.status
                ')
                ->findAll();
        
		return $gudang;
    }
}