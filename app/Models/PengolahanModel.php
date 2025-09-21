<?php

namespace App\Models;

use CodeIgniter\Model;

class PengolahanModel extends Model
{
    protected $table         = 'mt_pengolahan';
    protected $primaryKey    = 'mt_pengolahan_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'tg_pengolahan',
        'gudang_id',
        'hasil_olahan_daging',
        'hasil_olahan_kopra',
        'hasil_olahan_kulit',
        'rendemen',
        'created_by',
        'updated_by',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataPengolahan(array $filters = []): array
    {
        $pengolahan = $this->select('
                    mt_pengolahan.mt_pengolahan_id,
                    mt_pengolahan.tg_pengolahan,
                    mt_pengolahan.gudang_id,
                    mt_pengolahan.hasil_olahan_daging,
                    mt_pengolahan.hasil_olahan_kopra,
                    mt_pengolahan.hasil_olahan_kulit,
                    mt_pengolahan.rendemen,
                    m_gudang.nama AS nama_gudang,
                    mt_pengolahan.created_at,
                ')
            ->join('m_gudang', 'm_gudang.m_gudang_id = mt_pengolahan.gudang_id', 'left')
            ->orderby('mt_pengolahan.tg_pengolahan DESC');

        if (isset($filters['mt_pengolahan_id']) && is_numeric($filters['mt_pengolahan_id'])) {
            $pengolahan->where('mt_pengolahan.mt_pengolahan_id', (int)$filters['mt_pengolahan_id']);
        }

        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $pengolahan->where('mt_pengolahan.gudang_id', (int)$filters['gudang_id']);
        }

        if (!empty($filters['start_date'])) {
            $pengolahan->where('mt_pengolahan.tg_pengolahan >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $pengolahan->where('mt_pengolahan.tg_pengolahan <=', $filters['end_date']);
        }

        return $pengolahan->findAll();
    }
}