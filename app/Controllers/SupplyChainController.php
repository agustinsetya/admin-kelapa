<?php

namespace App\Controllers;

use App\Models\PembelianModel;
use App\Models\PengolahanModel;
use App\Models\PengirimanModel;
use App\Models\GudangModel;
use App\Models\PegawaiModel;
use App\Controllers\Concerns\ApiResponse;

class SupplyChainController extends AuthRequiredController
{
    use ApiResponse;

	protected $pembelianModel;
	protected $pengolahanModel;
	protected $pengirimanModel;
	protected $gudangModel;
    protected $pegawaiModel;

	public function __construct()
    {
        $this->pembelianModel = new PembelianModel();
        $this->pengolahanModel = new PengolahanModel();
        $this->pengirimanModel = new PengirimanModel();
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

    public function showDataPengolahan()
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

    /* --------------------------------
     * API (JSON)
     * -------------------------------- */

	public function getDataPembelian()
    {
        $filters   = $this->filtersFromUser();
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

    public function getDataPengolahan()
    {
        $filters   = $this->filtersFromUser();
        $pengolahan = $this->pengolahanModel->getDataPengolahan($filters);

        return $this->jsonSuccess(['data' => $pengolahan]);
    }

    public function getDetailPengolahan()
    {
        $id = $this->request->getGet('id');
        if (!$id) {
            return $this->jsonError('ID pengolahan tidak ditemukan', 400);
        }

        $detail = $this->pengolahanModel->getDataPengolahan(['mt_pengolahan_id' => $id]);
        return $this->jsonSuccess(['data' => $detail]);
    }

    public function addDetailPengolahan()
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
            'tg_pengolahan'	=> $input['tg_pengolahan'],
            'gudang_id'		=> $input['peng_gudang_id'],
            'kd_pegawai'	=> $input['peng_pegawai_id'],
            'berat_daging'  => $input['berat_daging'],
            'berat_kopra'   => $input['berat_kopra'],
            'bonus'         => $input['bonus_produksi'],
            'created_by'	=> $user->email ?? null,
        ];

        $saved = $this->pengolahanModel->saveDataPengolahan($data);

        if ($saved === false) {
            $errors = $this->pengolahanModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        // 201 Created
        return $this->jsonSuccess([
            'message' => 'Berhasil Tambah Data Pengolahan',
        ], 201);

    }
	
    public function updateDetailPengolahan()
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
        $id = $input['id'] ?? null;

        if (!$id) {
            return $this->jsonError('ID pengolahan tidak ditemukan', 400);
        }

        $data = [
            'tg_pengolahan'	=> $input['tg_pengolahan'],
            'gudang_id'		=> $input['peng_gudang_id'],
            'kd_pegawai'	=> $input['peng_pegawai_id'],
            'berat_daging'  => $input['berat_daging'],
            'berat_kopra'   => $input['berat_kopra'],
            'bonus'         => $input['bonus_produksi'],
            'updated_by'	=> $user->email ?? null,
        ];

        $saved = $this->pengolahanModel->saveDataPengolahan($data, $id);

        if ($saved === false) {
            $errors = $this->pengolahanModel->errors() ?: 'Gagal menyimpan data';
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

        $detail = $this->pengirimanModel->getDataPengiriman(['mt_pengiriman_id' => $id]);
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
            'tg_pengiriman'	=> $input['tg_pengiriman'],
            'gudang_id'		=> $input['peng_gudang_id'],
            'kd_pegawai'	=> $input['peng_pegawai_id'],
            'berat_daging'  => $input['berat_daging'],
            'berat_kopra'   => $input['berat_kopra'],
            'bonus'         => $input['bonus_pengiriman'],
            'created_by'	=> $user->email ?? null,
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
            'tg_pengiriman'	=> $input['tg_pengiriman'],
            'gudang_id'		=> $input['peng_gudang_id'],
            'kd_pegawai'	=> $input['peng_pegawai_id'],
            'berat_daging'  => $input['berat_daging'],
            'berat_kopra'   => $input['berat_kopra'],
            'bonus'         => $input['bonus_pengiriman'],
            'updated_by'	=> $user->email ?? null,
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
