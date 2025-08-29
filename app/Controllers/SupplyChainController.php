<?php

namespace App\Controllers;

use App\Models\PembelianModel;
use App\Models\PengolahanModel;
use App\Models\GudangModel;
use App\Controllers\Concerns\ApiResponse;

class SupplyChainController extends AuthRequiredController
{
    use ApiResponse;

	protected $pembelianModel;
	protected $pengolahanModel;
	protected $gudangModel;

	public function __construct()
    {
        $this->pembelianModel = new PembelianModel();
        $this->pengolahanModel = new PengolahanModel();
		$this->gudangModel = new GudangModel();
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
        ];

        return view('supply-data-pembelian', $data);
    }

    public function showDataPengolahan()
    {
        $data = [
            'title_meta' => view('partials/title-meta', ['title' => 'Data_Pengolahan']),
            'page_title' => view('partials/page-title', [
                'title' => 'Data_Pengolahan',
                'li_1'  => lang('Files.Supply_Chain'),
                'li_2'  => lang('Files.Data_Pengolahan'),
            ]),
        ];

        return view('supply-data-pengolahan', $data);
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
            'tg_pembelian'	=> $input['tg_pembelian'],
            'gudang_id'		=> $input['gudang_id'],
            'berat_kelapa'  => $input['berat_kelapa'],
            'created_by'	=> $user->email ?? null,
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
            'tg_pembelian'	=> $input['tg_pembelian'],
            'gudang_id'		=> $input['gudang_id'],
            'berat_kelapa'  => $input['berat_kelapa'],
            'updated_by'	=> $user->email ?? null,
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
