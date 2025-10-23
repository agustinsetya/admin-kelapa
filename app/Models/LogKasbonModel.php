<?php

namespace App\Models;

use CodeIgniter\Model;

class LogKasbonModel extends Model
{
    protected $table         = 'mt_log_kasbon';
    protected $primaryKey    = 'mt_log_kasbon_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'tg_kasbon',
        'kd_pegawai',
        'jumlah',
        'tipe',
        'created_by',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function getDataLogKasbon(array $filters = []): array
    {
        $kasbon = $this->select('
                    mt_log_kasbon.mt_log_kasbon_id,
                    mt_log_kasbon.tg_kasbon,
                    mt_log_kasbon.kd_pegawai,
                    mt_log_kasbon.jumlah,
                    mt_log_kasbon.tipe,
                    mt_pegawai.penempatan_id AS gudang_id,
                    m_gudang.nama AS nama_gudang,
                    mt_pegawai.nama AS nama_pegawai,
                ')
                ->join('mt_pegawai', 'mt_pegawai.kd_pegawai = mt_log_kasbon.kd_pegawai', 'left')
                ->join('m_gudang', 'm_gudang.m_gudang_id = mt_pegawai.penempatan_id', 'left');
        
        if (isset($filters['mt_log_kasbon_id']) && is_numeric($filters['mt_log_kasbon_id'])) {
            $kasbon->where('mt_log_kasbon.mt_log_kasbon_id', (int)$filters['mt_log_kasbon_id']);
        }
        
        if (isset($filters['kd_pegawai']) && is_numeric($filters['kd_pegawai'])) {
            $kasbon->where('mt_log_kasbon.kd_pegawai', (int)$filters['kd_pegawai']);
        }
        
        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $kasbon->where('mt_pegawai.penempatan_id', (int)$filters['gudang_id']);
        }
        
		if (!empty($filters['tg_kasbon_start'])) {
            $kasbon->where('mt_log_kasbon.tg_kasbon >=', $filters['tg_kasbon_start']);
        }

        if (!empty($filters['tg_kasbon_end'])) {
            $kasbon->where('mt_log_kasbon.tg_kasbon <=', $filters['tg_kasbon_end']);
        }
        
		return $kasbon->orderBy('mt_log_kasbon.tg_kasbon', 'DESC')
                            ->findAll();
    }
}