<?php

namespace App\Controllers;

use App\Models\AutentikasiModel;
use CodeIgniter\HTTP\RedirectResponse;

class AutentikasiController extends BaseController
{
    protected AutentikasiModel $authModel;

    public function __construct()
    {
        $this->authModel = new AutentikasiModel();
    }

    public function index(): string
    {
        return view('auth-login', [
            'title_meta' => view('partials/title-meta', ['title' => 'Login']),
        ]);
    }

    public function login(): RedirectResponse|string
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return view('auth-login', [
                'title_meta'  => view('partials/title-meta', ['title' => 'Login']),
                'validation'  => $this->validator,
            ]);
        }

        $email = (string) $this->request->getPost('username');
        $password   = (string) $this->request->getPost('password');

        $user = $this->authModel->checkLogin($email, $password);

        if ($user) {
            $this->session->regenerate(true);

            $this->session->set([
                'user'          => $user,
                'last_activity' => time(),
                'isLoggedIn'    => true,
                'role'          => $user->role_id ?? null,
                'role_scope'    => $user->role_scope ?? null,
            ]);

            log_message('debug', 'Session data set: ' . json_encode($this->session->get()));
            return redirect()->to(route_to('dashboard'));
        }

        $this->session->setFlashdata('message', lang('Files.Login_Invalid'));
        $this->session->setFlashdata('old', ['username' => $email]);

        return redirect()->to(route_to('auth-login'))->withInput();
    }

    public function showAuthLogout(): string
    {
        return view('auth-logout', [
            'title_meta' => view('partials/title-meta', ['title' => 'Logout']),
        ]);
    }

    public function logout(): RedirectResponse
    {
        $this->session->destroy();
        return redirect()->to(route_to('auth-logout'));
    }
}