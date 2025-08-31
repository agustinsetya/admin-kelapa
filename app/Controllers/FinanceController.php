<?php

namespace App\Controllers;

use App\Models\PengeluaranModel;
use App\Models\KategoriPengeluaranModel;
use App\Models\GudangModel;
use App\Models\PegawaiModel;
use App\Controllers\Concerns\ApiResponse;

class FinanceController extends AuthRequiredController
{
	use ApiResponse;

	protected $pengeluaranModel;
	protected $kategoriPengeluaranModel;
	protected $gudangModel;
    protected $pegawaiModel;

	public function __construct()
    {
        $this->pengeluaranModel = new PengeluaranModel();
        $this->kategoriPengeluaranModel = new KategoriPengeluaranModel();
		$this->gudangModel = new GudangModel();
        $this->pegawaiModel = new PegawaiModel();
    }

	/* --------------------------------
     * View
     * -------------------------------- */

	public function showDataPengeluaran()
	{
		$user = session()->get('user');
		$roleScope = session()->get('role_scope');

        $filtersPegawai = [];
		$filtersGudang  = [];

		if ($roleScope === 'gudang' && !empty($user) && !empty($user->penempatan_id)) {
			$filtersPegawai['penempatan_id'] = $user->penempatan_id;
			$filtersGudang['id']             = $user->penempatan_id;
		}

		$data = [
			'title_meta' => view('partials/title-meta', [
				'title' => 'Pengeluaran'
			]),
			'page_title' => view('partials/page-title', [
				'title' => 'Pengeluaran',
				'li_1'  => lang('Files.Finance'),
				'li_2'  => lang('Files.Pengeluaran')
			]),
			'gudang'    => $this->gudangModel->getDataGudang($filtersGudang ?: null),
			'pegawai'   => $this->pegawaiModel->getDataPegawai($filtersPegawai ?: null),
			'kategori'  => $this->kategoriPengeluaranModel->getDataKategoriPengeluaran(),
			'roleScope' => $roleScope,
		];

		return view('finance-pengeluaran', $data);
	}
	
	public function showDataGajiDriver()
	{
		$data = [
			'title_meta' => view('partials/title-meta', [
				'title' => 'Gaji_Driver'
			]),
			'page_title' => view('partials/page-title', [
				'title' => 'Gaji_Driver',
				'li_1'  => lang('Files.Finance'),
				'li_2'  => lang('Files.Gaji_Driver')
			])
		];
		
		return view('finance-gaji-driver', $data);
	}
	
	public function showDataGajiPegawai()
	{
		$data = [
			'title_meta' => view('partials/title-meta', [
				'title' => 'Gaji_Pegawai'
			]),
			'page_title' => view('partials/page-title', [
				'title' => 'Gaji_Pegawai',
				'li_1'  => lang('Files.Finance'),
				'li_2'  => lang('Files.Gaji_Pegawai')
			])
		];
		
		return view('finance-gaji-pegawai', $data);
	}

	/* --------------------------------
     * API (JSON)
     * -------------------------------- */

	public function getDataPengeluaran()
    {
        $roleFilters	= $this->filtersFromUser();

		$gudangId 		= $this->request->getGet('gudang_id') ?? null;
		$start			= $this->request->getGet('start_date') ?? null;
		$end			= $this->request->getGet('end_date') ?? null;

		$queryFilters = [];
		
		if (($user->role_scope ?? null) !== 'gudang' && !empty($gudangId)) {
			$queryFilters['gudang_id'] = $gudangId;
		}
		if (!empty($start)) $queryFilters['tg_pengeluaran_start'] = $start;
		if (!empty($end))   $queryFilters['tg_pengeluaran_end']   = $end;

		$filters = array_merge($queryFilters, $roleFilters);

        $pengeluaran = $this->pengeluaranModel->getDataPengeluaran($filters);

        return $this->jsonSuccess(['data' => $pengeluaran]);
    }

	public function getDetailPengeluaran()
    {
        $id = $this->request->getGet('id');
        if (!$id) {
            return $this->jsonError('ID pengeluaran tidak ditemukan', 400);
        }

        $detail = $this->pengeluaranModel->getDataPengeluaran(['mt_pengeluaran_id' => $id]);
        return $this->jsonSuccess(['data' => $detail]);
    }

	public function addDetailPengeluaran()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('financePengeluaran')) {
            return $this->jsonError('Validasi gagal', 422, [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $input = $this->request->getPost();

        $data = [
            'tg_pengeluaran'		=> $input['tg_pengeluaran'],
            'ktg_pengeluaran_id'	=> $input['peng_ktg_pengeluaran_id'],
            'gudang_id'				=> $input['peng_gudang_id'],
            'kd_pegawai'			=> $input['peng_pegawai_id'],
            'biaya'  				=> $input['biaya'],
            'jumlah'  				=> $input['jumlah'],
            'status'  				=> $input['peng_status'],
            'created_by'			=> $user->email ?? null,
        ];

        $saved = $this->pengeluaranModel->saveDataPengeluaran($data);

        if ($saved === false) {
            $errors = $this->pengeluaranModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        // 201 Created
        return $this->jsonSuccess([
            'message' => 'Berhasil Tambah Data Pengeluaran',
        ], 201);

    }
	
    public function updateDetailPengeluaran()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('financePengeluaran')) {
            return $this->jsonError('Validasi gagal', 422, [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $input = $this->request->getPost();
        $id = $input['id'] ?? null;

        if (!$id) {
            return $this->jsonError('ID pengeluaran tidak ditemukan', 400);
        }

        $data = [
            'tg_pengeluaran'		=> $input['tg_pengeluaran'],
            'ktg_pengeluaran_id'	=> $input['peng_ktg_pengeluaran_id'],
            'gudang_id'				=> $input['peng_gudang_id'],
            'kd_pegawai'			=> $input['peng_pegawai_id'],
            'biaya'  				=> $input['biaya'],
            'jumlah'  				=> $input['jumlah'],
            'status'  				=> $input['peng_status'],
            'updated_by'			=> $user->email ?? null,
        ];

        $saved = $this->pengeluaranModel->saveDataPengeluaran($data, $id);

        if ($saved === false) {
            $errors = $this->pengeluaranModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        return $this->jsonSuccess([
            'message' => 'Berhasil Update Data Pengeluaran',
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
