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
		$data = [
			'title_meta' => view('partials/title-meta', [
				'title' => 'Data_Pembelian'
			]),
			'page_title' => view('partials/page-title', [
				'title' => 'Data_Pembelian',
				'li_1'  => lang('Files.Supply_Chain'),
				'li_2'  => lang('Files.Data_Pembelian')
			])
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

	public function showDetailPembelian()
    {
		$gudang = $this->gudangModel->getDataGudang();

		$data = [
            'title_meta' => view('partials/title-meta', ['title' => 'Detail_Pembelian']),
            'page_title' => view('partials/page-title', [
                'title' => 'Detail_Pembelian',
                'li_1'  => lang('Files.Supply_Chain'),
                'li_2'  => lang('Files.Detail_Pembelian'),
            ]),
			'gudang' => $gudang,
        ];

        return view('supply-detail-pembelian', $data);
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
