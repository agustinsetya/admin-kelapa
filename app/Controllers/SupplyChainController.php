<?php

namespace App\Controllers;

class SupplyChainController extends BaseController
{
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
