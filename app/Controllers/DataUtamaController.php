<?php

namespace App\Controllers;

use App\Models\UserRolesModel;
use App\Models\UserModel;
use App\Models\PegawaiModel;
use App\Models\GudangModel;
use App\Models\KomponenGajiModel;
use App\Models\KategoriPengeluaranModel;
use App\Controllers\Concerns\ApiResponse;

class DataUtamaController extends AuthRequiredController
{
    use ApiResponse;
    
    protected $userRolesModel;
    protected $userModel;
    protected $pegawaiModel;
    protected $gudangModel;
    protected $komponenGajiModel;
    protected $kategoriPengeluaranModel;

    public function __construct()
    {
        $this->userRolesModel = new UserRolesModel();
        $this->userModel = new UserModel();
        $this->pegawaiModel = new PegawaiModel();
        $this->gudangModel = new GudangModel();
        $this->komponenGajiModel = new KomponenGajiModel();
        $this->kategoriPengeluaranModel = new KategoriPengeluaranModel();
    }

    public function showDataUserRoles()
    {
		$data = [
            'title_meta' => view('partials/title-meta', ['title' => 'User_Roles']),
            'page_title' => view('partials/page-title', [
                'title' => 'User_Roles',
                'li_1'  => lang('Files.Data_Utama'),
                'li_2'  => lang('Files.User_Roles'),
            ]),
        ];

        return view('master-user-roles', $data);
    }
    
	public function getDataUserRoles()
    {
		$userRoles = $this->userRolesModel->getDataUserRoles();

        return $this->response->setJSON([
            'data' => $userRoles
        ]);
    }

    public function getDetailUserRoles()
    {
        $id = $this->request->getGet('id');
        if (!$id) {
            return $this->jsonError('ID User Roles tidak ditemukan', 400);
        }

        $detail = $this->userRolesModel->getDataUserRoles(['m_role_id' => $id]);
        return $this->jsonSuccess(['data' => $detail]);
    }

	public function addDetailUserRoles()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('masterUserRoles')) {
            return $this->jsonError('Validasi gagal', 422, [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $input = $this->request->getPost();

        $data = [
            'nama'		    => $input['nama_peran'],
            'role_scope'	=> $input['lingkup_peran'],
            'created_by'	=> $user->email ?? null,
        ];

        $saved = $this->userRolesModel->saveDataUserRoles($data);

        if ($saved === false) {
            $errors = $this->userRolesModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        // 201 Created
        return $this->jsonSuccess([
            'message' => 'Berhasil Tambah Data User Roles',
        ], 201);

    }
	
    public function updateDetailUserRoles()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('masterUserRoles')) {
            return $this->jsonError('Validasi gagal', 422, [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $input = $this->request->getPost();
        $id = $input['id'] ?? null;

        if (!$id) {
            return $this->jsonError('ID User Roles tidak ditemukan', 400);
        }

        $data = [
            'nama'		    => $input['nama_peran'],
            'role_scope'	=> $input['lingkup_peran'],
            'updated_by'	=> $user->email ?? null,
        ];

        $saved = $this->userRolesModel->saveDataUserRoles($data, $id);

        if ($saved === false) {
            $errors = $this->userRolesModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        return $this->jsonSuccess([
            'message' => 'Berhasil Update Data User Roles',
        ], 200);
    }
    
    public function showDataUser()
    {
		$data = [
            'title_meta' => view('partials/title-meta', ['title' => 'User']),
            'page_title' => view('partials/page-title', [
                'title' => 'User',
                'li_1'  => lang('Files.Data_Utama'),
                'li_2'  => lang('Files.User'),
            ]),
            // 'pegawai'   => $this->pegawaiModel->getDataPegawai(['exclude_existing_user' => true]),
        ];

        return view('master-user', $data);
    }
    
	public function getDataUser()
    {
		$user = $this->userModel->getDataUser();

        return $this->response->setJSON([
            'data' => $user
        ]);
    }

    public function getDetailUser()
    {
        $id = $this->request->getGet('id');
        if (!$id) {
            return $this->jsonError('User ID tidak ditemukan', 400);
        }

        $detail = $this->userModel->getDataUser(['mt_user_id' => $id]);
        return $this->jsonSuccess(['data' => $detail]);
    }

    public function addDetailUser()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('masterUserAdd')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        $input = $this->request->getPost();

        $hashedPassword = password_hash($input['us_pegawai_id'], PASSWORD_DEFAULT);

        $data = [
            'kd_pegawai'    => $input['us_pegawai_id'],
            'email'         => $input['email'],
            'password'      => $hashedPassword,
            'created_by'	=> $user->email ?? null,
        ];

        $saved = $this->userModel->saveDataUser($data);

        if ($saved === false) {
            $errors = $this->userModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        // 201 Created
        return $this->jsonSuccess([
            'message' => 'Berhasil Tambah Data User',
        ], 201);
    }
	
    public function updateDetailUser()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('masterPegawai')) {
            return $this->jsonError('Validasi gagal', 422, [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $input = $this->request->getPost();
        $id = $input['id'] ?? null;

        if (!$id) {
            return $this->jsonError('User ID tidak ditemukan', 400);
        }

        $data = [
            'email'         => $input['email'],
            'updated_by'	=> $user->email ?? null,
        ];

        $saved = $this->userModel->saveDataUser($data, $id);

        if ($saved === false) {
            log_message('error', 'Gagal menyimpan data user: ' . print_r($this->userModel->errors(), true));
            $errors = $this->userModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        return $this->jsonSuccess([
            'message' => 'Berhasil Update Data User',
        ], 200);
    }
    
    public function showDataPegawai()
    {
		$gudang = $this->gudangModel->getDataGudang();
		$userRole = $this->userRolesModel->getDataUserRoles();

		$data = [
            'title_meta' => view('partials/title-meta', ['title' => 'Pegawai']),
            'page_title' => view('partials/page-title', [
                'title' => 'Pegawai',
                'li_1'  => lang('Files.Data_Utama'),
                'li_2'  => lang('Files.Pegawai'),
            ]),
			'gudang' => $gudang,
			'role' => $userRole,
        ];

        return view('master-pegawai', $data);
    }
    
	public function getDataPegawai()
    {
		$filters = [
            'role_id'   => $this->request->getGet('role'),
            'gudang_id' => $this->request->getGet('gudang'),
        ];

        $excludeUser = $this->request->getGet('exclude_existing_user');
        if ($excludeUser !== null) {
            $filters['exclude_existing_user'] = filter_var($excludeUser, FILTER_VALIDATE_BOOLEAN);
        }

		$pegawai = $this->pegawaiModel->getDataPegawai($filters);

        return $this->response->setJSON([
            'data' => $pegawai
        ]);
    }

    public function getDetailPegawai()
    {
        $id = $this->request->getGet('id');
        if (!$id) {
            return $this->jsonError('Pegawai ID tidak ditemukan', 400);
        }

        $detail = $this->pegawaiModel->getDataPegawai(['mt_pegawai_id' => $id]);
        return $this->jsonSuccess(['data' => $detail]);
    }

    public function addDetailPegawai()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('masterPegawai')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        $input = $this->request->getPost();

        $data = [
            'kd_pegawai'    => $input['kd_pegawai'],
            'nama'          => $input['nama_pegawai'],
            'jenis_kelamin' => $input['jenis_kelamin'],
            'role_id'       => $input['peg_role_id'],
            'penempatan_id' => $input['pg_gudang_id'],
            'created_by'	=> $user->email ?? null,
        ];

        $saved = $this->pegawaiModel->saveDataPegawai($data);

        if ($saved === false) {
            $errors = $this->pegawaiModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        // 201 Created
        return $this->jsonSuccess([
            'message' => 'Berhasil Tambah Data Pegawai',
        ], 201);
    }
	
    public function updateDetailPegawai()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('masterPegawai')) {
            return $this->jsonError('Validasi gagal', 422, [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $input = $this->request->getPost();
        $id = $input['id'] ?? null;

        if (!$id) {
            return $this->jsonError('User ID tidak ditemukan', 400);
        }

        $data = [
            'kd_pegawai'    => $input['kd_pegawai'],
            'nama'          => $input['nama_pegawai'],
            'jenis_kelamin' => $input['jenis_kelamin'],
            'role_id'       => $input['peg_role_id'],
            'penempatan_id' => $input['pg_gudang_id'],
            'updated_by'	=> $user->email ?? null,
        ];

        $saved = $this->pegawaiModel->saveDataPegawai($data, $id);

        if ($saved === false) {
            log_message('error', 'Gagal menyimpan data pegawai: ' . print_r($this->pegawaiModel->errors(), true));
            $errors = $this->pegawaiModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        return $this->jsonSuccess([
            'message' => 'Berhasil Update Data Pegawai',
        ], 200);
    }

    public function showDataGudang()
    {
		$data = [
            'title_meta' => view('partials/title-meta', ['title' => 'Gudang']),
            'page_title' => view('partials/page-title', [
                'title' => 'Gudang',
                'li_1'  => lang('Files.Data_Utama'),
                'li_2'  => lang('Files.Gudang'),
            ]),
        ];

        // if ($roleScope == 'all') {
        //     return view('report-komponen-gaji', $data);
        // } else if ($roleScope == 'gudang') {
        //     return view('report-komponen-gaji-form', $data);
        // } else {
        //     session()->setFlashdata('error', 'Anda tidak mempunyai akses ke halaman ini');
        //     return redirect()->to('/dashboard');
        // }

        return view('master-gudang', $data);
    }

    public function getDataGudang()
    {
		$gudang = $this->gudangModel->getDataGudang();

        return $this->response->setJSON([
            'data' => $gudang
        ]);
    }

    public function getDetailGudang()
    {
        $id = $this->request->getGet('id');
        if (!$id) {
            return $this->jsonError('Gudang ID tidak ditemukan', 400);
        }

        $detail = $this->gudangModel->getDataGudang(['m_gudang_id' => $id]);
        return $this->jsonSuccess(['data' => $detail]);
    }

    public function addDetailGudang()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('masterGudang')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        $input = $this->request->getPost();

        $data = [
            'nama'                  => $input['nama_gudang'],
            'takaran_daging'        => $input['takaran_daging_kelapa'],
            'upah_takaran_daging'   => $input['upah_takaran_daging'],
            'takaran_kopra'         => $input['takaran_kopra_kelapa'],
            'upah_takaran_kopra'    => $input['upah_takaran_kopra'],
            'takaran_kulit'         => $input['takaran_kulit_kelapa'],
            'upah_takaran_kulit'    => $input['upah_takaran_kulit'],
            'gaji_driver'           => $input['gaji_driver'],
            'created_by'	        => $user->email ?? null,
        ];

        $saved = $this->gudangModel->saveDataGudang($data);

        if ($saved === false) {
            $errors = $this->gudangModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        // 201 Created
        return $this->jsonSuccess([
            'message' => 'Berhasil Tambah Data Gudang',
        ], 201);
    }
	
    public function updateDetailGudang()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('masterGudang')) {
            return $this->jsonError('Validasi gagal', 422, [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $input = $this->request->getPost();
        $id = $input['id'] ?? null;

        if (!$id) {
            return $this->jsonError('Gudang ID tidak ditemukan', 400);
        }

        $data = [
            'nama'                  => $input['nama_gudang'],
            'takaran_daging'        => $input['takaran_daging_kelapa'],
            'upah_takaran_daging'   => $input['upah_takaran_daging'],
            'takaran_kopra'         => $input['takaran_kopra_kelapa'],
            'upah_takaran_kopra'    => $input['upah_takaran_kopra'],
            'takaran_kulit'         => $input['takaran_kulit_kelapa'],
            'upah_takaran_kulit'    => $input['upah_takaran_kulit'],
            'gaji_driver'           => $input['gaji_driver'],
            'updated_by'	        => $user->email ?? null,
        ];

        $saved = $this->gudangModel->saveDataGudang($data, $id);

        if ($saved === false) {
            log_message('error', 'Gagal menyimpan data gudang: ' . print_r($this->gudangModel->errors(), true));
            $errors = $this->gudangModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        return $this->jsonSuccess([
            'message' => 'Berhasil Update Data Gudang',
        ], 200);
    }
    
    public function showDataKategoriPengeluaran()
    {
        $data = [
            'title_meta' => view('partials/title-meta', ['title' => 'Kategori_Pengeluaran']),
            'page_title' => view('partials/page-title', [
                'title' => 'Kategori_Pengeluaran',
                'li_1'  => lang('Files.Data_Utama'),
                'li_2'  => lang('Files.Kategori_Pengeluaran'),
            ]),
        ];

        return view('master-kategori-pengeluaran', $data);
    }

    public function getDataKategoriPengeluaran()
    {
		$ktgPengeluaran = $this->kategoriPengeluaranModel->getDataKategoriPengeluaran();

        return $this->response->setJSON([
            'data' => $ktgPengeluaran
        ]);
    }

    public function getDetailKategoriPengeluaran()
    {
        $id = $this->request->getGet('id');
        if (!$id) {
            return $this->jsonError('Kategori Pengeluaran ID tidak ditemukan', 400);
        }

        $detail = $this->kategoriPengeluaranModel->getDataKategoriPengeluaran(['m_ktg_pengeluaran_id' => $id]);
        return $this->jsonSuccess(['data' => $detail]);
    }

    public function addDetailKategoriPengeluaran()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('masterKategoriPengeluaran')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        $input = $this->request->getPost();

        $data = [
            'nama'                  => $input['nama_kategori'],
            'keterangan'            => $input['ket_kategori'],
            'created_by'	        => $user->email ?? null,
        ];

        $saved = $this->kategoriPengeluaranModel->saveDataKategoriPengeluaran($data);

        if ($saved === false) {
            $errors = $this->kategoriPengeluaranModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        // 201 Created
        return $this->jsonSuccess([
            'message' => 'Berhasil Tambah Data Kategori Pengeluaran',
        ], 201);
    }
	
    public function updateDetailKategoriPengeluaran()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->jsonError('Tidak terautentik', 401);
        }

        if (!$this->validate('masterKategoriPengeluaran')) {
            return $this->jsonError('Validasi gagal', 422, [
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $input = $this->request->getPost();
        $id = $input['id'] ?? null;

        if (!$id) {
            return $this->jsonError('Kategori Pengeluaran ID tidak ditemukan', 400);
        }

        $data = [
            'nama'                  => $input['nama_kategori'],
            'keterangan'            => $input['ket_kategori'],
            'updated_by'	        => $user->email ?? null,
        ];

        $saved = $this->kategoriPengeluaranModel->saveDataKategoriPengeluaran($data, $id);

        if ($saved === false) {
            log_message('error', 'Gagal menyimpan data Kategori Pengeluaran: ' . print_r($this->kategoriPengeluaranModel->errors(), true));
            $errors = $this->kategoriPengeluaranModel->errors() ?: 'Gagal menyimpan data';
            return $this->jsonError($errors, 500);
        }

        return $this->jsonSuccess([
            'message' => 'Berhasil Update Data Kategori Pengeluaran',
        ], 200);
    }
}