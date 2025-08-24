<?php

namespace App\Controllers;

use App\Models\PegawaiModel;
use App\Models\GudangModel;
use App\Models\UserRoleModel;

class DataUtamaController extends AuthRequiredController
{
    protected $pegawaiModel;
    protected $gudangModel;
    protected $userRoleModel;

    public function __construct()
    {
        $this->pegawaiModel = new PegawaiModel();
        $this->gudangModel = new GudangModel();
        $this->userRoleModel = new UserRoleModel();
    }

    public function showDataPegawai()
    {
		$gudang = $this->gudangModel->getDataGudang();
		$userRole = $this->userRoleModel->getDataUserRole();

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

    public function showDataKomponenGaji()
    {
        $data = [
            'title_meta' => view('partials/title-meta', ['title' => 'Komponen_Gaji']),
            'page_title' => view('partials/page-title', [
                'title' => 'Komponen_Gaji',
                'li_1'  => lang('Files.Data_Utama'),
                'li_2'  => lang('Files.Komponen_Gaji'),
            ]),
        ];
        return view('master-komponen-gaji', $data);
    }
}
