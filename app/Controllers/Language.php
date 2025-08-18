<?php namespace App\Controllers;

class Language extends BaseController
{
	public function index()
	{
        $session = session();
        $locale = service('request')->getLocale(); 
        $session->remove('lang');
        $session->set('lang',$locale);
        return redirect()->back();

        /*
        * if redirect to home page after every language change
        */
        $url = base_url();
        return redirect()->to($url);  
    }
}