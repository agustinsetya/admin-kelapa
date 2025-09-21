<?php

namespace App\Controllers;

use App\Models\PengeluaranModel;
use App\Models\KategoriPengeluaranModel;
use App\Models\KasbonModel;
use App\Models\GudangModel;
use App\Models\PegawaiModel;
use App\Models\LogPengolahanModel;
use App\Models\PengirimanModel;
use App\Models\GajiDriverModel;
use App\Models\GajiPegawaiModel;
use App\Controllers\Concerns\ApiResponse;

class FinanceController extends AuthRequiredController
{
	use ApiResponse;

	protected $pengeluaranModel;
	protected $kategoriPengeluaranModel;
	protected $kasbonModel;
	protected $gudangModel;
    protected $pegawaiModel;
    protected $logPengolahanModel;
    protected $pengirimanModel;
    protected $gajiDriverModel;
    protected $gajiPegawaiModel;

	public function __construct()
    {
        $this->pengeluaranModel = new PengeluaranModel();
        $this->kategoriPengeluaranModel = new KategoriPengeluaranModel();
		$this->kasbonModel = new KasbonModel();
		$this->gudangModel = new GudangModel();
        $this->pegawaiModel = new PegawaiModel();
        $this->logPengolahanModel = new LogPengolahanModel();
        $this->pengirimanModel = new PengirimanModel();
        $this->gajiDriverModel = new GajiDriverModel();
        $this->gajiPegawaiModel = new GajiPegawaiModel();
    }

	/* --------------------------------
     * View
     * -------------------------------- */

	public function showDataPengeluaran()
	{
		$user = session()->get('user');
		$roleScope = session()->get('role_scope');

        $data = [
			'title_meta' => view('partials/title-meta', [
				'title' => 'Pengeluaran'
			]),
			'page_title' => view('partials/page-title', [
				'title' => 'Pengeluaran',
				'li_1'  => lang('Files.Finance'),
				'li_2'  => lang('Files.Pengeluaran')
			]),
			'gudang'    => $this->gudangModel->getDataGudang(),
			'kategori'  => $this->kategoriPengeluaranModel->getDataKategoriPengeluaran(),
			'roleScope' => $roleScope,
            'penempatan' => $user->penempatan_id ?? '',
            'pegawaiId' => $user->kd_pegawai ?? '',
		];

		return view('finance-pengeluaran', $data);
	}
	
    public function showDataKasbon()
	{
		$user = session()->get('user');
		$roleScope = session()->get('role_scope');

        $data = [
			'title_meta' => view('partials/title-meta', [
				'title' => 'Kasbon'
			]),
			'page_title' => view('partials/page-title', [
				'title' => 'Kasbon',
				'li_1'  => lang('Files.Finance'),
				'li_2'  => lang('Files.Kasbon')
			]),
			'gudang'    => $this->gudangModel->getDataGudang(),
			'roleScope' => $roleScope,
            'penempatan' => $user->penempatan_id ?? '',
            'pegawaiId' => $user->kd_pegawai ?? '',
		];

		return view('finance-kasbon', $data);
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
            ]),
            'gudang'    => $this->gudangModel->getDataGudang(),
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
            ]),
            'gudang'    => $this->gudangModel->getDataGudang(),
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
	
    public function getDataKasbon()
    {
        $roleFilters	= $this->filtersFromUser();

		$gudangId 		= $this->request->getGet('gudang_id') ?? null;
		$start			= $this->request->getGet('start_date') ?? null;
		$end			= $this->request->getGet('end_date') ?? null;

		$queryFilters = [];
		
		if (($user->role_scope ?? null) !== 'gudang' && !empty($gudangId)) {
			$queryFilters['gudang_id'] = $gudangId;
		}
		if (!empty($start)) $queryFilters['tg_kasbon_start'] = $start;
		if (!empty($end))   $queryFilters['tg_kasbon_end']   = $end;

		$filters = array_merge($queryFilters, $roleFilters);

        $kasbon = $this->kasbonModel->getDataKasbon($filters);

        return $this->jsonSuccess(['data' => $kasbon]);
    }

	public function getDetailKasbon()
    {
        $id = $this->request->getGet('id');
        if (!$id) {
            return $this->jsonError('ID kasbon tidak ditemukan', 400);
        }

        $detail = $this->kasbonModel->getDataKasbon(['mt_kasbon_id' => $id]);
        return $this->jsonSuccess(['data' => $detail]);
    }

	public function addDetailKasbon()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('financeKasbon')) {
            return $this->jsonError('Validasi gagal', 422, [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $input = $this->request->getPost();

        $data = [
            'tg_kasbon'		=> $input['tg_kasbon'],
            'kd_pegawai'	=> $input['kb_pegawai_id'],
            'jumlah'  		=> $input['jumlah'],
            'status'  		=> $input['kb_status'],
            'created_by'	=> $user->email ?? null,
        ];

        $saved = $this->kasbonModel->saveDataKasbon($data);

        if ($saved === false) {
            $errors = $this->kasbonModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        // 201 Created
        return $this->jsonSuccess([
            'message' => 'Berhasil Tambah Data Kasbon',
        ], 201);

    }
	
    public function updateDetailKasbon()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        $input = $this->request->getPost();
        $id = $input['id'] ?? null;

        if (!$id) {
            return $this->jsonError('ID kasbon tidak ditemukan', 400);
        }

        $data = [
            'status'  		=> $input['kb_status'],
            'updated_by'    => $user->email ?? null,
        ];

        $saved = $this->kasbonModel->saveDataKasbon($data, $id);

        if ($saved === false) {
            $errors = $this->kasbonModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        return $this->jsonSuccess([
            'message' => 'Berhasil Update Data Kasbon',
        ], 200);
    }

    public function getDataUpahPegawai()
    {
        $roleFilters	= $this->filtersFromUser();

		$gudangId 		= $this->request->getGet('gudang_id') ?? null;
		$start			= $this->request->getGet('start_date') ?? null;
		$end			= $this->request->getGet('end_date') ?? null;

		$queryFilters = [];
		
		if (($user->role_scope ?? null) !== 'gudang' && !empty($gudangId)) {
			$queryFilters['gudang_id'] = $gudangId;
		}
		if (!empty($start)) $queryFilters['start_date'] = $start;
		if (!empty($end))   $queryFilters['end_date']   = $end;

		$filters = array_merge($queryFilters, $roleFilters);

        $upahProduksiPegawai = $this->logPengolahanModel->getDataUpahProduksi($filters);

        return $this->jsonSuccess(['data' => $upahProduksiPegawai]);
    }

    public function addDetailGajiPegawai()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        $input = $this->request->getJSON(true);
        if (!$input) $input = $this->request->getPost();

        $periodeStart = $input['start_date'] ?? null;
        $periodeEnd   = $input['end_date']   ?? null;

        if (!$this->validate('financeGajiPegawai')) {
            return $this->jsonError('Validasi gagal', 422, [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        if (empty($input['data']) || !is_array($input['data'])) {
            return $this->jsonError('Data pegawai tidak valid.', 422);
        }

        $dataPegawai = $input['data'];
        $resultUpah = [];

        foreach ($dataPegawai as $pegawai) {
            if (empty($pegawai['kdPegawai']) || empty($pegawai['gudangId'])) {
                return $this->jsonError('Data pegawai atau gudang tidak lengkap.', 422);
            }

            $filters = [
                'kd_pegawai' => $pegawai['kdPegawai'],
                'gudang_id'  => $pegawai['gudangId'],
                'start_date' => $periodeStart,
                'end_date'   => $periodeEnd,
            ];

            $upah = $this->logPengolahanModel->getDataUpahProduksi($filters);

            if (empty($upah)) {
                return $this->jsonError('Upah tidak ditemukan untuk pegawai: ' . $pegawai['kdPegawai'], 404);
            }

            $resultUpah[] = $upah;
        }

        $upahProduksiPegawai = !empty($resultUpah) ? array_merge(...$resultUpah) : [];

        foreach ($upahProduksiPegawai as &$row) {
            $row['total_upah_daging']   = $row['total_upah_daging'] ?? 0;
            $row['total_upah_kopra']    = $row['total_upah_kopra'] ?? 0;
            $row['total_upah_kulit']    = $row['total_upah_kulit'] ?? 0;
            $row['total_upah_produksi'] = $row['total_upah_produksi'] ?? 0;
            $row['total_bonus']         = $row['total_bonus'] ?? 0;
            $row['total_gaji_bersih']   = $row['total_gaji_bersih'] ?? 0;
        }
        unset($row); 

        $saved = $this->gajiPegawaiModel->prosesGajiPegawai(
            $user->email ?? null,
            $periodeStart,
            $periodeEnd,
            $upahProduksiPegawai,
        );

        if ($saved === false) {
            $errors = $this->gajiPegawaiModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        // 201 Created
        return $this->jsonSuccess([
            'message' => 'Gaji Pegawai Berhasil diproses.',
        ], 201);
    }

    public function getDataUpahDriver()
    {
        $roleFilters	= $this->filtersFromUser();

		$gudangId 		= $this->request->getGet('gudang_id') ?? null;
		$start			= $this->request->getGet('start_date') ?? null;
		$end			= $this->request->getGet('end_date') ?? null;

		$queryFilters = [];
		
		if (($user->role_scope ?? null) !== 'gudang' && !empty($gudangId)) {
			$queryFilters['gudang_id'] = $gudangId;
		}
		if (!empty($start)) $queryFilters['start_date'] = $start;
		if (!empty($end))   $queryFilters['end_date']   = $end;

		$filters = array_merge($queryFilters, $roleFilters);

        $upahPerjalananDriver = $this->pengirimanModel->getDataUpahPengiriman($filters);

        return $this->jsonSuccess(['data' => $upahPerjalananDriver]);
    }

    public function addDetailGajiDriver()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        $input = $this->request->getJSON(true);
        if (!$input) $input = $this->request->getPost();

        $periodeStart = $input['start_date'] ?? null;
        $periodeEnd   = $input['end_date']   ?? null;

        if (!$this->validate('financeGajiDriver')) {
            return $this->jsonError('Validasi gagal', 422, [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        if (empty($input['data']) || !is_array($input['data'])) {
            return $this->jsonError('Data Driver tidak valid.', 422);
        }

        $dataPegawai = $input['data'];
        $resultUpah = [];

        foreach ($dataPegawai as $pegawai) {
            if (empty($pegawai['kdPegawai']) || empty($pegawai['gudangId'])) {
                return $this->jsonError('Data pegawai atau gudang tidak lengkap.', 422);
            }

            $filters = [
                'kd_pegawai' => $pegawai['kdPegawai'],
                'gudang_id'  => $pegawai['gudangId'],
                'start_date' => $periodeStart,
                'end_date'   => $periodeEnd,
            ];

            $upah = $this->pengirimanModel->getDataUpahPengiriman($filters);

            if (empty($upah)) {
                return $this->jsonError('Upah tidak ditemukan untuk pegawai: ' . $pegawai['kdPegawai'], 404);
            }

            $resultUpah[] = $upah;
        }

        $upahPengiriman = !empty($resultUpah) ? array_merge(...$resultUpah) : [];

        foreach ($upahPengiriman as &$row) {
            $row['total_upah_perjalanan']   = $row['total_upah_perjalanan'] ?? 0;
            $row['total_bonus']             = $row['total_bonus'] ?? 0;
            $row['total_gaji_bersih']       = $row['total_gaji_bersih'] ?? 0;
        }
        unset($row); 

        $saved = $this->gajiDriverModel->prosesGajiDriver(
            $user->email ?? null,
            $periodeStart,
            $periodeEnd,
            $upahPengiriman,
        );

        if ($saved === false) {
            $errors = $this->gajiDriverModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        // 201 Created
        return $this->jsonSuccess([
            'message' => 'Gaji Driver Berhasil diproses.',
        ], 201);
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
