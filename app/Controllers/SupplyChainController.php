<?php

namespace App\Controllers;

use App\Models\PembelianModel;
use App\Models\LogPengolahanModel;
use App\Models\PengirimanModel;
use App\Models\PenjualanModel;
use App\Models\GudangModel;
use App\Models\PegawaiModel;
use App\Controllers\Concerns\ApiResponse;

class SupplyChainController extends AuthRequiredController
{
    use ApiResponse;

    private const HARGA_JUAL_TERIMA = 16000;
    private const HARGA_JUAL_REJECT = 9000;

	protected $pembelianModel;
	protected $logPengolahanModel;
	protected $pengirimanModel;
	protected $penjualanModel;
	protected $gudangModel;
    protected $pegawaiModel;

	public function __construct()
    {
        $this->pembelianModel = new PembelianModel();
        $this->logPengolahanModel = new LogPengolahanModel();
        $this->pengirimanModel = new PengirimanModel();
        $this->penjualanModel = new PenjualanModel();
		$this->gudangModel = new GudangModel();
        $this->pegawaiModel = new PegawaiModel();
    }

    /* --------------------------------
     * View
     * -------------------------------- */

	public function showDataPembelian()
    {
        $roleScope = session()->get('role_scope');
        
        $data = [
            'title_meta' => view('partials/title-meta', ['title' => 'Data_Pembelian']),
            'page_title' => view('partials/page-title', [
                'title' => 'Data_Pembelian',
                'li_1'  => lang('Files.Supply_Chain'),
                'li_2'  => lang('Files.Data_Pembelian'),
            ]),
            'gudang'    => $this->gudangModel->getDataGudang(),
            'roleScope' => $roleScope,
            'penempatan' => $user->penempatan_id ?? '',
        ];

        return view('supply-data-pembelian', $data);
    }

    public function showDataLogPengolahan()
    {
        $roleScope = session()->get('role_scope');
        $filters = array_merge(
            $this->filtersFromUser(),
            ['role_id_not' => 6]
        );

        $data = [
            'title_meta' => view('partials/title-meta', ['title' => 'Data_Pengolahan']),
            'page_title' => view('partials/page-title', [
                'title' => 'Data_Pengolahan',
                'li_1'  => lang('Files.Supply_Chain'),
                'li_2'  => lang('Files.Data_Pengolahan'),
            ]),
            'gudang'    => $this->gudangModel->getDataGudang(),
            'pegawai'   => $this->pegawaiModel->getDataPegawai($filters),
            'roleScope' => $roleScope,
        ];

        return view('supply-data-pengolahan', $data);
    }
    
    public function showDataPengiriman()
    {
        $roleScope = session()->get('role_scope');
        $filters = array_merge(
            $this->filtersFromUser(),
            ['role_id' => 6]
        );

        $data = [
            'title_meta' => view('partials/title-meta', ['title' => 'Data_Pengiriman']),
            'page_title' => view('partials/page-title', [
                'title' => 'Data_Pengiriman',
                'li_1'  => lang('Files.Supply_Chain'),
                'li_2'  => lang('Files.Data_Pengiriman'),
            ]),
            'gudang'    => $this->gudangModel->getDataGudang(),
            'pegawai'   => $this->pegawaiModel->getDataPegawai($filters),
            'roleScope' => $roleScope,
        ];

        return view('supply-data-pengiriman', $data);
    }
    
    public function showDataPenjualan()
    {
        $roleScope = session()->get('role_scope');

        $data = [
            'title_meta' => view('partials/title-meta', ['title' => 'Data_Penjualan']),
            'page_title' => view('partials/page-title', [
                'title' => 'Data_Penjualan',
                'li_1'  => lang('Files.Supply_Chain'),
                'li_2'  => lang('Files.Data_Penjualan'),
            ]),
            'roleScope' => $roleScope,
        ];

        return view('supply-data-penjualan', $data);
    }

    /* --------------------------------
     * API (JSON)
     * -------------------------------- */

	public function getDataPembelian()
    {
        $filters = $this->filtersFromUser();

        $gudangId = $this->request->getGet('gudang');
        if (!empty($gudangId)) {
            $filters['gudang_id'] = $gudangId;
        }
        
        $pembelian = $this->pembelianModel->getDataPembelian($filters);

        return $this->jsonSuccess(['data' => $pembelian]);
    }

	public function getDetailPembelian()
    {
        $id = $this->request->getGet('id');
        if (!$id) {
            return $this->jsonError('ID pembelian tidak ditemukan', 400);
        }

        $detail = $this->pembelianModel->getDataPembelian(['mt_pembelian_id' => $id]);
        return $this->jsonSuccess(['data' => $detail]);
    }

	public function addDetailPembelian()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('supplyChainPembelian')) {
            return $this->jsonError('Validasi gagal', 422, [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $input = $this->request->getPost();

        $data = [
            'tg_pembelian'      => $input['tg_pembelian'],
            'gudang_id'         => $input['pem_gudang_id'],
            'kode_container'    => $input['kode_container'],
            'berat_kelapa'      => $input['berat_kelapa'],
            'created_by'	    => $user->email ?? null,
        ];

        $saved = $this->pembelianModel->saveDataPembelian($data);

        if ($saved === false) {
            $errors = $this->pembelianModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        // 201 Created
        return $this->jsonSuccess([
            'message' => 'Berhasil Tambah Data Pembelian',
        ], 201);

    }
	
    public function updateDetailPembelian()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('supplyChainPembelian')) {
            return $this->jsonError('Validasi gagal', 422, [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $input = $this->request->getPost();
        $id = $input['id'] ?? null;

        if (!$id) {
            return $this->jsonError('ID pembelian tidak ditemukan', 400);
        }

        $data = [
            'tg_pembelian'	    => $input['tg_pembelian'],
            'gudang_id'	        => $input['pem_gudang_id'],
            'kode_container'    => $input['kode_container'],
            'berat_kelapa'      => $input['berat_kelapa'],
            'updated_by'	    => $user->email ?? null,
        ];

        $saved = $this->pembelianModel->saveDataPembelian($data, $id);

        if ($saved === false) {
            $errors = $this->pembelianModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        return $this->jsonSuccess([
            'message' => 'Berhasil Update Data Pembelian',
        ], 200);
    }

    public function getDataLogPengolahan()
    {
        $filters   = $this->filtersFromUser();
        $pengolahan = $this->logPengolahanModel->getDataLogPengolahan($filters);

        return $this->jsonSuccess(['data' => $pengolahan]);
    }

    public function getDetailLogPengolahan()
    {
        $id = $this->request->getGet('id');
        if (!$id) {
            return $this->jsonError('ID pengolahan tidak ditemukan', 400);
        }

        $detail = $this->logPengolahanModel->getDataLogPengolahan(['mt_log_pengolahan_id' => $id]);
        return $this->jsonSuccess(['data' => $detail]);
    }

    public function addDetailLogPengolahan()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('supplyChainPengolahan')) {
            return $this->jsonError('Validasi gagal', 422, [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $input = $this->request->getPost();

        $data = [
            'tg_pengolahan'	    => $input['tg_pengolahan'],
            'gudang_id'		    => $input['peng_gudang_id'],
            'kode_container'    => $input['peng_kode_container'],
            'kd_pegawai'	    => $input['peng_pegawai_id'],
            'berat_daging'      => $input['berat_daging'],
            'berat_kopra'       => $input['berat_kopra'],
            'berat_kulit'       => $input['berat_kulit'],
            'bonus'             => $input['bonus_produksi'],
            'created_by'	    => $user->email ?? null,
        ];

        $saved = $this->logPengolahanModel->saveDataLogPengolahan($data);

        if ($saved === false) {
            $errors = $this->logPengolahanModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        // 201 Created
        return $this->jsonSuccess([
            'message' => 'Berhasil Tambah Data Pengolahan',
        ], 201);
    }
	
    public function updateDetailLogPengolahan()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        $input = $this->request->getPost();
        $id = $input['id'] ?? null;

        if (!$id) {
            return $this->jsonError('ID log pengolahan tidak ditemukan', 400);
        }

        $data = [
            'tg_pengolahan'	    => $input['tg_pengolahan'],
            'gudang_id'		    => $input['peng_gudang_id'],
            'kode_container'    => $input['peng_kode_container'],
            'kd_pegawai'	    => $input['peng_pegawai_id'],
            'berat_daging'      => $input['berat_daging'],
            'berat_kopra'       => $input['berat_kopra'],
            'berat_kulit'       => $input['berat_kulit'],
            'bonus'             => $input['bonus_produksi'],
            'updated_by'	    => $user->email ?? null,
        ];

        $saved = $this->logPengolahanModel->saveDataLogPengolahan($data, $id);

        if ($saved === false) {
            $errors = $this->logPengolahanModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        return $this->jsonSuccess([
            'message' => 'Berhasil Update Data Pengolahan',
        ], 200);
    }
    
    public function getDataPengiriman()
    {
        $filters   = $this->filtersFromUser();
        $pengiriman = $this->pengirimanModel->getDataPengiriman($filters);

        return $this->jsonSuccess(['data' => $pengiriman]);
    }

    public function getDetailPengiriman()
    {
        $id = $this->request->getGet('id');
        if (!$id) {
            return $this->jsonError('ID pengiriman tidak ditemukan', 400);
        }

        $detail = $this->pengirimanModel->getDataPengiriman(['mt_log_pengiriman_id' => $id]);
        return $this->jsonSuccess(['data' => $detail]);
    }

    public function addDetailPengiriman()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('supplyChainPengiriman')) {
            return $this->jsonError('Validasi gagal', 422, [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $input = $this->request->getPost();

        $data = [
            'tg_pengiriman'	    => $input['tg_pengiriman'],
            'gudang_id'		    => $input['peng_gudang_id'],
            'jenis_kirim'	    => $input['jenis_kirim'],
            'armada'	        => $input['armada'],
            'nomor_resi'	    => $input['nomor_resi'],
            'kd_pegawai'	    => $input['peng_pegawai_id'],
            'jumlah_perjalanan' => $input['jumlah_perjalanan'],
            'berat_daging'      => $input['berat_daging'],
            'bonus'             => $input['bonus_pengiriman'],
            'created_by'	    => $user->email ?? null,
        ];

        $saved = $this->pengirimanModel->saveDataPengiriman($data);

        if ($saved === false) {
            $errors = $this->pengirimanModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        // 201 Created
        return $this->jsonSuccess([
            'message' => 'Berhasil Tambah Data Pengiriman',
        ], 201);

    }
	
    public function updateDetailPengiriman()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('supplyChainPengiriman')) {
            return $this->jsonError('Validasi gagal', 422, [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $input = $this->request->getPost();
        $id = $input['id'] ?? null;

        if (!$id) {
            return $this->jsonError('ID pengiriman tidak ditemukan', 400);
        }

        $data = [
            'tg_pengiriman'	    => $input['tg_pengiriman'],
            'gudang_id'		    => $input['peng_gudang_id'],
            'jenis_kirim'	    => $input['jenis_kirim'],
            'armada'	        => $input['armada'],
            'nomor_resi'	    => $input['nomor_resi'],
            'kd_pegawai'	    => $input['peng_pegawai_id'],
            'jumlah_perjalanan' => $input['jumlah_perjalanan'],
            'berat_daging'      => $input['berat_daging'],
            'bonus'             => $input['bonus_pengiriman'],
            'updated_by'	    => $user->email ?? null,
        ];

        $saved = $this->pengirimanModel->saveDataPengiriman($data, $id);

        if ($saved === false) {
            $errors = $this->pengirimanModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        return $this->jsonSuccess([
            'message' => 'Berhasil Update Data Pengiriman',
        ], 200);
    }
    
    public function getDataPenjualan()
    {
        $filters   = $this->filtersFromUser();
        $penjualan = $this->penjualanModel->getDataPenjualan($filters);

        return $this->jsonSuccess(['data' => $penjualan]);
    }

    public function getDetailPenjualan()
    {
        $id = $this->request->getGet('id');
        if (!$id) {
            return $this->jsonError('ID penjualan tidak ditemukan', 400);
        }

        $detail = $this->penjualanModel->getDataPenjualan(['mt_penjualan_id' => $id]);
        return $this->jsonSuccess(['data' => $detail]);
    }

    public function addDetailPenjualan()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('supplyChainPenjualan')) {
            return $this->jsonError('Validasi gagal', 422, [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $input = $this->request->getPost();

        $data = [
            'tg_penjualan'	        => $input['tg_penjualan'],
            'log_pengiriman_id'	    => $input['log_pengiriman_id'],
            'daging_kelapa_terima'	=> $input['daging_kelapa_terima'],
            'pendapatan_terima'	    => $input['daging_kelapa_terima'] * self::HARGA_JUAL_TERIMA,
            'daging_kelapa_reject'	=> $input['daging_kelapa_reject'],
            'pendapatan_reject'	    => $input['daging_kelapa_reject'] * self::HARGA_JUAL_REJECT,
            'status'                => $input['penj_status'],
            'created_by'	        => $user->email ?? null,
        ];

        $saved = $this->penjualanModel->saveDataPenjualan($data);

        if ($saved === false) {
            $errors = $this->penjualanModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        // 201 Created
        return $this->jsonSuccess([
            'message' => 'Berhasil Tambah Data Penjualan',
        ], 201);

    }
	
    public function updateDetailPenjualan()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('supplyChainPenjualan')) {
            return $this->jsonError('Validasi gagal', 422, [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $input = $this->request->getPost();
        $id = $input['id'] ?? null;

        if (!$id) {
            return $this->jsonError('ID penjualan tidak ditemukan', 400);
        }

        $data = [
            'tg_penjualan'	        => $input['tg_penjualan'],
            'log_pengiriman_id'	    => $input['log_pengiriman_id'],
            'daging_kelapa_terima'	=> $input['daging_kelapa_terima'],
            'pendapatan_terima'	    => $input['daging_kelapa_terima'] * self::HARGA_JUAL_TERIMA,
            'daging_kelapa_reject'	=> $input['daging_kelapa_reject'],
            'pendapatan_reject'	    => $input['daging_kelapa_reject'] * self::HARGA_JUAL_REJECT,
            'status'                => $input['penj_status'],
            'updated_by'	        => $user->email ?? null,
        ];

        $saved = $this->penjualanModel->saveDataPenjualan($data, $id);

        if ($saved === false) {
            $errors = $this->penjualanModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        return $this->jsonSuccess([
            'message' => 'Berhasil Update Data Penjualan',
        ], 200);
    }

    private function filtersFromUser(): array
    {
        $user = session()->get('user');
        if (!$user) {
            return [];
        }

        if (($user->role_scope ?? null) === 'gudang') {
            return ['gudang_id' => $user->penempatan_id ?? ''];
        }

        return [];
    }
}
