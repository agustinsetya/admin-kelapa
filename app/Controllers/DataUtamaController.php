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

    public function showDataKaryawan()
    {
		$karyawan = $this->pegawaiModel->getDataPegawai();
		$gudang = $this->gudangModel->getDataGudang();
		$userRole = $this->userRoleModel->getDataUserRole();

        $data = [
            'title_meta' => view('partials/title-meta', ['title' => 'Karyawan']),
            'page_title' => view('partials/page-title', [
                'title' => 'Karyawan',
                'li_1'  => lang('Files.Data_Utama'),
                'li_2'  => lang('Files.Karyawan'),
            ]),
			'karyawan' => $karyawan,
			'gudang' => $gudang,
			'userRole' => $userRole,
        ];
        return view('master-karyawan', $data);
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
