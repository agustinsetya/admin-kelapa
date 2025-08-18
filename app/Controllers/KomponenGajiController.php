<?php

namespace App\Controllers;

class KomponenGajiController extends BaseController
{
	public function index()
	{
		$data = [
			'title_meta' => view('partials/title-meta', [
				'title' => 'Komponen_Gaji'
			]),
			'page_title' => view('partials/page-title', [
				'title' => 'Komponen_Gaji',
				'li_1'  => lang('Files.Data_Utama'),
				'li_2'  => lang('Files.Komponen_Gaji')
			])
		];

		return view('master-komponen-gaji', $data);
	}
}
