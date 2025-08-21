<?php

namespace App\Controllers;

class PayrollController extends AuthRequiredController
{
	public function showDataPenggajian()
	{
		$data = [
			'title_meta' => view('partials/title-meta', [
				'title' => 'Penggajian'
			]),
			'page_title' => view('partials/page-title', [
				'title' => 'Penggajian',
				'li_1'  => lang('Files.Payroll'),
				'li_2'  => lang('Files.Penggajian')
			])
		];

		return view('payroll-penggajian', $data);
	}
	
	public function showDataAbsensi()
	{
		$data = [
			'title_meta' => view('partials/title-meta', [
				'title' => 'Absensi'
			]),
			'page_title' => view('partials/page-title', [
				'title' => 'Absensi',
				'li_1'  => lang('Files.Payroll'),
				'li_2'  => lang('Files.Absensi')
			])
		];
		
		return view('payroll-absensi', $data);
	}
	
	public function showDataLembur()
	{
		$data = [
			'title_meta' => view('partials/title-meta', [
				'title' => 'Lembur'
			]),
			'page_title' => view('partials/page-title', [
				'title' => 'Lembur',
				'li_1'  => lang('Files.Payroll'),
				'li_2'  => lang('Files.Lembur')
			])
		];
		
		return view('payroll-lembur', $data);
	}
}
