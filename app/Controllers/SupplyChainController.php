<?php

namespace App\Controllers;

use App\Models\PembelianModel;
use App\Models\PengolahanModel;
use App\Models\GudangModel;

class SupplyChainController extends AuthRequiredController
{
	protected $pembelianModel;
	protected $pengolahanModel;
	protected $gudangModel;

	public function __construct()
    {
        $this->pembelianModel = new PembelianModel();
        $this->pengolahanModel = new PengolahanModel();
		$this->gudangModel = new GudangModel();
    }

	public function showDataPembelian()
	{
		$roleScope = session()->get('role_scope');
		$gudang = $this->gudangModel->getDataGudang();

		$data = [
			'title_meta' => view('partials/title-meta', [
				'title' => 'Data_Pembelian'
			]),
			'page_title' => view('partials/page-title', [
				'title' => 'Data_Pembelian',
				'li_1'  => lang('Files.Supply_Chain'),
				'li_2'  => lang('Files.Data_Pembelian')
			]),
			'gudang' => $gudang,
			'roleScope' => $roleScope,
		];
		
		return view('supply-data-pembelian', $data);
	}

	public function getDataPembelian()
    {
		$user = session()->get('user');

		if ($user->role_scope == 'gudang') {
			$filters = [
				'gudang_id'   => $user->penempatan_id ?? '',
			];
		}
		
		$pembelian = $this->pembelianModel->getDataPembelian($filters ?? []);

        return $this->response->setJSON([
            'data' => $pembelian
        ]);
    }

	public function getDetailPembelian()
    {
        $filters = [
            'mt_pembelian_id'   => $this->request->getGet('id'),
        ];

		$detailKomponenGaji = $this->pembelianModel->getDataPembelian($filters);

        return $this->response->setJSON([
            'data' => $detailKomponenGaji
        ]);
    }

	public function updateDetailPembelian()
    {
        $user = session()->get('user');

        if (!$user) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false, 'message' => 'Tidak terautentik', 'code' => 401
            ]);
        }

        $input = $this->request->getPost();

        if (!$this->validate('supplyChainPembelian')) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $this->validator->getErrors(),
                'code'    => 422,
            ]);
        }

        $data = [
            'tg_pembelian'	=> $input['tg_pembelian'],
            'gudang_id'		=> $input['gudang_id'],
            'berat'       	=> $input['berat_kelapa'],
            'updated_by'	=> $user->email ?? null,
        ];

        $save = $this->pembelianModel->updateKomponenGaji($data, $data['mt_pembelian_id']);

        if ($save === false) {
            $errors = method_exists($this->pembelianModel, 'errors')
                ? $this->pembelianModel->errors()
                : (is_object($save) && property_exists($save, 'message') ? $save->message : 'Gagal menyimpan data');
    
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => $errors,
                'code'    => 500,
            ]);
        }
    
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Berhasil Update Data Pembelian',
            'code'    => 200,
        ]);
    }
	
	public function showDataPengolahan()
	{
		$data = [
			'title_meta' => view('partials/title-meta', [
				'title' => 'Data_Pengolahan'
			]),
			'page_title' => view('partials/page-title', [
				'title' => 'Data_Pengolahan',
				'li_1'  => lang('Files.Supply_Chain'),
				'li_2'  => lang('Files.Data_Pengolahan')
			])
		];
		
		return view('supply-data-pengolahan', $data);
	}
}
