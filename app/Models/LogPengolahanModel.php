<?php

namespace App\Models;

use CodeIgniter\Model;

class LogPengolahanModel extends Model
{
    protected $table         = 'mt_log_pengolahan';
    protected $primaryKey    = 'mt_log_pengolahan_id';
    protected $returnType    = 'object';
    protected $allowedFields = [
        'tg_pengolahan',
        'gudang_id',
        'kode_container',
        'kd_pegawai',
        'berat_daging',
        'berat_kopra',
        'berat_kulit',
        'bonus',
        'tg_proses_gaji',
        'is_stat_gaji',
        'created_by',
        'updated_by',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getDataLogPengolahan(array $filters = []): array
    {
        $pengolahan = $this->select('
                    mt_log_pengolahan.mt_log_pengolahan_id,
                    mt_log_pengolahan.tg_pengolahan,
                    mt_log_pengolahan.gudang_id,
                    mt_log_pengolahan.kode_container,
                    mt_log_pengolahan.kd_pegawai,
                    mt_log_pengolahan.berat_daging,
                    mt_log_pengolahan.berat_kopra,
                    mt_log_pengolahan.berat_kulit,
                    mt_log_pengolahan.bonus,
                    mt_log_pengolahan.tg_proses_gaji,
                    mt_log_pengolahan.is_stat_gaji,
                    m_gudang.nama AS nama_gudang,
                    mt_pegawai.nama AS nama_pegawai,
                    mt_log_pengolahan.created_at,
                ')
            ->join('m_gudang', 'm_gudang.m_gudang_id = mt_log_pengolahan.gudang_id', 'left')
            ->join('mt_pegawai', 'mt_pegawai.kd_pegawai = mt_log_pengolahan.kd_pegawai', 'left')
            ->orderby('mt_log_pengolahan.tg_pengolahan DESC');

        if (isset($filters['mt_log_pengolahan_id']) && is_numeric($filters['mt_log_pengolahan_id'])) {
            $pengolahan->where('mt_log_pengolahan.mt_log_pengolahan_id', (int)$filters['mt_log_pengolahan_id']);
        }

        if (isset($filters['gudang_id']) && is_numeric($filters['gudang_id'])) {
            $pengolahan->where('mt_log_pengolahan.gudang_id', (int)$filters['gudang_id']);
        }

        if (isset($filters['kd_pegawai'])) {
            $pengolahan->where('mt_log_pengolahan.kd_pegawai', (int)$filters['kd_pegawai']);
        }

        if (!empty($filters['start_date'])) {
            $pengolahan->where('mt_log_pengolahan.tg_pengolahan >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $pengolahan->where('mt_log_pengolahan.tg_pengolahan <=', $filters['end_date']);
        }

        return $pengolahan->findAll();
    }
    
    public function getDataUpahProduksi(array $filters = []): array
    {
        $start = $filters['start_date'] ?? null;
        $end   = $filters['end_date'] ?? null;

        $upah = $this->db->table('mt_log_pengolahan p');
        $upah->select("
            p.kd_pegawai, pg.nama AS nama_pegawai, p.gudang_id, g.nama AS nama_gudang,
            SUM(ROUND((COALESCE(p.berat_daging, 0) / NULLIF(g.takaran_daging, 0)) * COALESCE(g.upah_takaran_daging, 0), 0)) AS total_upah_daging,
            SUM(ROUND((COALESCE(p.berat_kopra, 0) / NULLIF(g.takaran_kopra, 0)) * COALESCE(g.upah_takaran_kopra, 0), 0)) AS total_upah_kopra,
            SUM(ROUND((COALESCE(p.berat_kulit, 0) / NULLIF(g.takaran_kulit, 0)) * COALESCE(g.upah_takaran_kulit, 0), 0)) AS total_upah_kulit,
            (
                SUM(ROUND((COALESCE(p.berat_daging, 0) / NULLIF(g.takaran_daging, 0)) * COALESCE(g.upah_takaran_daging, 0), 0)) +
                SUM(ROUND((COALESCE(p.berat_kopra, 0) / NULLIF(g.takaran_kopra, 0)) * COALESCE(g.upah_takaran_kopra, 0), 0)) +
                SUM(ROUND((COALESCE(p.berat_kulit, 0) / NULLIF(g.takaran_kulit, 0)) * COALESCE(g.upah_takaran_kulit, 0), 0))
            ) AS total_upah_produksi,
            SUM(COALESCE(p.bonus, 0)) AS total_bonus,
            (
                SUM(ROUND((COALESCE(p.berat_daging, 0) / NULLIF(g.takaran_daging, 0)) * COALESCE(g.upah_takaran_daging, 0), 0)) +
                SUM(ROUND((COALESCE(p.berat_kopra, 0) / NULLIF(g.takaran_kopra, 0)) * COALESCE(g.upah_takaran_kopra, 0), 0)) +
                SUM(ROUND((COALESCE(p.berat_kulit, 0) / NULLIF(g.takaran_kulit, 0)) * COALESCE(g.upah_takaran_kulit, 0), 0)) +
                SUM(COALESCE(p.bonus, 0))
            ) AS total_gaji_bersih
        ", false);

        $upah->join('m_gudang g', 'g.m_gudang_id = p.gudang_id', 'left');
        $upah->join('mt_pegawai pg', 'pg.kd_pegawai = p.kd_pegawai', 'left');
        $upah->where('p.is_stat_gaji', 0);

        // Filters
        if (!empty($filters['kd_pegawai'])) {
            $upah->whereIn('p.kd_pegawai', (array) $filters['kd_pegawai']);
        }

        if (!empty($filters['gudang_id'])) {
            $upah->where('p.gudang_id', $filters['gudang_id']);
        }

        if (!empty($start)) {
            $upah->where('p.tg_pengolahan >=', $start);
        }

        if (!empty($end)) {
            $upah->where('p.tg_pengolahan <=', $end);
        }

        $upah->groupBy('p.kd_pegawai, p.gudang_id');

        return $upah->get()->getResultArray();
    }

    public function saveDataLogPengolahan(array $data, $pengolahanId = null): bool
    {
        $this->db->transStart();

        $pembelianModel  = new \App\Models\PembelianModel();
        $pengolahanModel = new \App\Models\PengolahanModel();

        $oldData = null;
        if ($pengolahanId !== null) {
            $oldData = $this->asArray()
                ->where('mt_log_pengolahan_id', $pengolahanId)
                ->first();

            if (!$oldData) {
                $this->db->transRollback();
                return false;
            }

            $data['mt_log_pengolahan_id'] = $pengolahanId;
        }

        // Normalisasi input
        $fieldsInput = ['berat_daging', 'berat_kopra', 'berat_kulit'];
        foreach ($fieldsInput as $f) {
            $data[$f] = isset($data[$f]) ? (float) $data[$f] : 0;
        }

        // Insert / Update log pengolahan
        $ok = $pengolahanId !== null
            ? $this->update($pengolahanId, $data)
            : $this->insert($data) !== false;

        if (!$ok) {
            $this->db->transRollback();
            return false;
        }

        // Ambil pembelian
        $pembelian = $pembelianModel->asArray()
            ->where('kode_container', $data['kode_container'])
            ->where('gudang_id', $data['gudang_id'])
            ->first();

        if (!$pembelian) {
            $this->db->transRollback();
            return false;
        }

        // Ambil pengolahan harian
        $existingPengolahan = $pengolahanModel->asArray()
            ->where('tg_pengolahan', $data['tg_pengolahan'])
            ->where('gudang_id', $data['gudang_id'])
            ->first();

        $actor = $data['updated_by'] ?? $data['created_by'] ?? 'admin';

        // Daftar field yang dihitung
        $mapFields = [
            'hasil_olahan_daging' => 'berat_daging',
            'hasil_olahan_kopra'  => 'berat_kopra',
            'hasil_olahan_kulit'  => 'berat_kulit',
        ];

        // Update pembelian
        $updatePembelian = [];
        foreach ($mapFields as $target => $source) {
            $lama   = (float) $pembelian[$target];
            $oldVal = (float) ($oldData[$source] ?? 0);
            $baru   = (float) $data[$source];

            $updatePembelian[$target] = ($lama - $oldVal) + $baru;
        }
        $updatePembelian['is_proses']   = 1;
        $updatePembelian['updated_by']  = $actor;

        $pembelianModel
            ->where('kode_container', $data['kode_container'])
            ->where('gudang_id', $data['gudang_id'])
            ->set($updatePembelian)
            ->update();
        
        // Update / Insert pengolahan harian
        if ($existingPengolahan) {
            $updatePengolahan = [
                'updated_by' => $actor,
            ];
            foreach ($mapFields as $target => $source) {
                $lama   = (float) $existingPengolahan[$target];
                $oldVal = (float) ($oldData[$source] ?? 0);
                $baru   = (float) $data[$source];

                $updatePengolahan[$target] = ($lama - $oldVal) + $baru;
            }

            // Hitung rendemen (kulit / daging * 100)
            $updatePengolahan['rendemen'] = $updatePengolahan['hasil_olahan_daging'] > 0
                ? ($updatePengolahan['hasil_olahan_kulit'] / $updatePengolahan['hasil_olahan_daging']) * 100
                : 0;

            $pengolahanModel
                ->where('tg_pengolahan', $data['tg_pengolahan'])
                ->where('gudang_id', $data['gudang_id'])
                ->set($updatePengolahan)
                ->update();
        } else {
            $insertPengolahan = [
                'tg_pengolahan' => $data['tg_pengolahan'],
                'gudang_id'     => $data['gudang_id'],
                'created_by'    => $actor,
            ];
            foreach ($mapFields as $target => $source) {
                $insertPengolahan[$target] = (int) $data[$source];
            }

            // Hitung rendemen untuk insert
            $insertPengolahan['rendemen'] = $insertPengolahan['hasil_olahan_daging'] > 0
                ? ($insertPengolahan['hasil_olahan_kulit'] / $insertPengolahan['hasil_olahan_daging']) * 100
                : 0;

            $pengolahanModel->insert($insertPengolahan);
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function deleteDataLogPengolahan($pengolahanId, ?string $actor = null, bool $deleteEmptyPengolahan = false): bool
    {
        $this->db->transStart();

        $pembelianModel  = new \App\Models\PembelianModel();
        $pengolahanModel = new \App\Models\PengolahanModel();

        // 1) Ambil data log yang mau dihapus (oldData)
        $oldData = $this->asArray()
            ->where('mt_log_pengolahan_id', $pengolahanId)
            ->first();

        if (!$oldData) {
            $this->db->transRollback();
            return false; // log tidak ditemukan
        }

        $mapFields = [
            'hasil_olahan_daging' => 'berat_daging',
            'hasil_olahan_kopra'  => 'berat_kopra',
            'hasil_olahan_kulit'  => 'berat_kulit',
        ];
        foreach ($mapFields as $target => $source) {
            $oldData[$source] = isset($oldData[$source]) ? (float) $oldData[$source] : 0.0;
        }

        // 2) Ambil pembelian terkait (kunci: kode_container + gudang_id)
        $pembelian = $pembelianModel->asArray()
            ->where('kode_container', $oldData['kode_container'])
            ->where('gudang_id', $oldData['gudang_id'])
            ->first();

        if (!$pembelian) {
            $this->db->transRollback();
            return false;
        }

        // 3) Ambil pengolahan harian terkait (kunci: tg_pengolahan + gudang_id)
        $existingPengolahan = $pengolahanModel->asArray()
            ->where('tg_pengolahan', $oldData['tg_pengolahan'])
            ->where('gudang_id', $oldData['gudang_id'])
            ->first();

        if (!$existingPengolahan) {
            $this->db->transRollback();
            return false;
        }

        // 4) Update PEBELIAN: kurangi agregat sesuai log yang dihapus (clamp minimal 0)
        $updatePembelian = [
            'updated_by' => $actor,
        ];
        foreach ($mapFields as $target => $source) {
            $lama = isset($pembelian[$target]) ? (float) $pembelian[$target] : 0.0;
            $dec  = (float) $oldData[$source];
            $baru = $lama - $dec;
            if ($baru < 0) $baru = 0; // clamp
            $updatePembelian[$target] = $baru;
        }

        // is_proses jadi 0 kalau semua hasil_olahan* = 0 setelah pengurangan
        $allZeroPembelian = (
            ($updatePembelian['hasil_olahan_daging'] ?? 0) <= 0 &&
            ($updatePembelian['hasil_olahan_kopra']  ?? 0) <= 0 &&
            ($updatePembelian['hasil_olahan_kulit']  ?? 0) <= 0
        );
        $updatePembelian['is_proses'] = $allZeroPembelian ? 0 : 1;

        $pembelianModel
            ->where('kode_container', $oldData['kode_container'])
            ->where('gudang_id', $oldData['gudang_id'])
            ->set($updatePembelian)
            ->update();

        // 5) Update PENGOLAHAN HARIAN: kurangi agregat & hitung ulang rendemen
        $updatePengolahan = [
            'updated_by' => $actor,
        ];
        foreach ($mapFields as $target => $source) {
            $lama = isset($existingPengolahan[$target]) ? (float) $existingPengolahan[$target] : 0.0;
            $dec  = (float) $oldData[$source];
            $baru = $lama - $dec;
            if ($baru < 0) $baru = 0; // clamp
            $updatePengolahan[$target] = $baru;
        }

        // Recompute rendemen = kulit / daging * 100 (hindari div zero)
        $daging = (float) ($updatePengolahan['hasil_olahan_daging'] ?? 0);
        $kulit  = (float) ($updatePengolahan['hasil_olahan_kulit']  ?? 0);
        $updatePengolahan['rendemen'] = $daging > 0 ? ($kulit / $daging) * 100 : 0;

        if ($deleteEmptyPengolahan) {
            $allZeroPengolahan = (
                ($updatePengolahan['hasil_olahan_daging'] ?? 0) <= 0 &&
                ($updatePengolahan['hasil_olahan_kopra']  ?? 0) <= 0 &&
                ($updatePengolahan['hasil_olahan_kulit']  ?? 0) <= 0
            );

            if ($allZeroPengolahan) {
                $pengolahanModel
                    ->where('tg_pengolahan', $oldData['tg_pengolahan'])
                    ->where('gudang_id', $oldData['gudang_id'])
                    ->delete();
            } else {
                $pengolahanModel
                    ->where('tg_pengolahan', $oldData['tg_pengolahan'])
                    ->where('gudang_id', $oldData['gudang_id'])
                    ->set($updatePengolahan)
                    ->update();
            }
        } else {
            $pengolahanModel
                ->where('tg_pengolahan', $oldData['tg_pengolahan'])
                ->where('gudang_id', $oldData['gudang_id'])
                ->set($updatePengolahan)
                ->update();
        }

        // 6) Hapus LOG pengolahan
        $ok = $this->delete($pengolahanId);
        if ($ok === false) {
            $this->db->transRollback();
            return false;
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }
}