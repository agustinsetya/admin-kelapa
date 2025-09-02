<?php

namespace App\Controllers;

class HomeController extends AuthRequiredController
{
	public function index()
	{
		$data = [
			'title_meta' => view('partials/title-meta', ['title' => 'Dashboard']),
			'page_title' => view('partials/page-title', ['title' => 'Dashboard', 'li_1' => 'Dashboard', 'li_2' => 'Dashboard'])
		];
		
		return view('dashboard', $data);
	}

	public function show_pages_maintenance()
	{
		$data = [
			'title_meta' => view('partials/title-meta', ['title' => 'Maintenance'])
		];
		return view('pages-maintenance', $data);
	}

	public function show_pages_comingsoon()
	{
		$data = [
			'title_meta' => view('partials/title-meta', ['title' => 'Coming_Soon'])
		];
		return view('pages-comingsoon', $data);
	}
}
