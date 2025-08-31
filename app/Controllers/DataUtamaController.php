<?php

namespace App\Controllers;

use App\Models\UserRolesModel;
use App\Models\UserModel;
use App\Models\PegawaiModel;
use App\Models\GudangModel;
use App\Models\KomponenGajiModel;
use App\Controllers\Concerns\ApiResponse;

class DataUtamaController extends AuthRequiredController
{
    use ApiResponse;
    
    protected $userRolesModel;
    protected $userModel;
    protected $pegawaiModel;
    protected $gudangModel;
    protected $komponenGajiModel;

    public function __construct()
    {
        $this->userRolesModel = new UserRolesModel();
        $this->userModel = new UserModel();
        $this->pegawaiModel = new PegawaiModel();
        $this->gudangModel = new GudangModel();
        $this->komponenGajiModel = new KomponenGajiModel();
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
    
    public function showDataUser()
    {
		$data = [
            'title_meta' => view('partials/title-meta', ['title' => 'User']),
            'page_title' => view('partials/page-title', [
                'title' => 'User',
                'li_1'  => lang('Files.Data_Utama'),
                'li_2'  => lang('Files.User'),
            ]),
            'pegawai'   => $this->pegawaiModel->getDataPegawai(),
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

        if (!$this->validate('masterUser')) {
            return $this->jsonError('Validasi gagal', 422, [
                'errors' => $this->validator->getErrors(),
            ]);
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

        if (!$this->validate('masterUser')) {
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
            'kd_pegawai'    => $input['us_pegawai_id'],
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
			'userRole' => $userRole,
        ];

        return view('master-pegawai', $data);
    }
    
	public function getDataPegawai()
    {
		$filters = [
            'role_id'   => $this->request->getGet('role'),
            'gudang_id' => $this->request->getGet('gudang'),
        ];

		$pegawai = $this->pegawaiModel->getDataPegawai($filters);

        return $this->response->setJSON([
            'data' => $pegawai
        ]);
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

        return view('master-gudang', $data);
    }
    
	public function getDataGudang()
    {
		$gudang = $this->gudangModel->getDataGudang();

        return $this->response->setJSON([
            'data' => $gudang
        ]);
    }

    public function showDataKomponenGaji()
    {
        $roleScope = session()->get('role_scope');

        $data = [
            'title_meta' => view('partials/title-meta', ['title' => 'Komponen_Gaji']),
            'page_title' => view('partials/page-title', [
                'title' => 'Komponen_Gaji',
                'li_1'  => lang('Files.Data_Utama'),
                'li_2'  => lang('Files.Komponen_Gaji'),
            ]),
            'roleScope' => $roleScope,
        ];

        if ($roleScope == 'all') {
            return view('master-komponen-gaji', $data);
        } else if ($roleScope == 'gudang') {
            return view('master-komponen-gaji-form', $data);
        } else {
            session()->setFlashdata('error', 'Anda tidak mempunyai akses ke halaman ini');
            return redirect()->to('/dashboard');
        }
    }

    public function getDataKomponenGaji()
    {
		$komponenGaji = $this->komponenGajiModel->getDataKomponenGaji();

        return $this->response->setJSON([
            'data' => $komponenGaji
        ]);
    }
    
    public function getDetailKomponenGaji()
    {
        $filters = [
            'gudang_id'   => session()->get('user')->penempatan_id ?? '',
        ];

		$detailKomponenGaji = $this->komponenGajiModel->getDataKomponenGaji($filters);

        return $this->response->setJSON([
            'data' => $detailKomponenGaji
        ]);
    }

    public function updateDetailKomponenGaji()
    {
        $user = session()->get('user');

        if (!$user || empty($user->penempatan_id)) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false, 'message' => 'Tidak terautentik', 'code' => 401
            ]);
        }

        $input = $this->request->getPost();

        if (!$this->validate('komponenGajiUpdate')) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $this->validator->getErrors(),
                'code'    => 422,
            ]);
        }

        $data = [
            'gudang_id'            => $user->penempatan_id,
            'takaran_daging'       => $input['takaran_daging_kelapa'],
            'upah_takaran_daging'  => $input['upah_takaran_daging'],
            'takaran_kopra'        => $input['takaran_kopra_kelapa'],
            'upah_takaran_kopra'   => $input['upah_takaran_kopra'],
            'updated_by'           => $user->email ?? null,
        ];

        $save = $this->komponenGajiModel->updateKomponenGaji($data, $data['gudang_id']);

        if ($save === false) {
            $errors = method_exists($this->komponenGajiModel, 'errors')
                ? $this->komponenGajiModel->errors()
                : (is_object($save) && property_exists($save, 'message') ? $save->message : 'Gagal menyimpan data');
    
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => $errors,
                'code'    => 500,
            ]);
        }
    
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Berhasil Update Data Komponen Gaji',
            'code'    => 200,
        ]);
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
}