<?php

namespace App\Controllers;

use App\Models\AutentikasiModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class AutentikasiController extends BaseController
{
	protected $session;
    protected $authModel;

	public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger);

        $this->session   = session();
        $this->authModel = new AutentikasiModel();
    }

	public function index(): string
	{
		$data = [
			'title_meta' => view('partials/title-meta', ['title' => 'Login'])
		];
		return view('auth-login', $data);
	}

	public function login(): RedirectResponse|ResponseInterface|string
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return view('auth-login', [
                'title_meta' => view('partials/title-meta', ['title' => 'Login']),
                'validation' => $this->validator,
            ]);
        }

        $kd_pegawai = (string) $this->request->getPost('username');
        $password = (string) $this->request->getPost('password');

        $user = $this->authModel->check_login($kd_pegawai, $password);

        if ($user) {
			$this->session->regenerate();

            $this->session->set([
                'user'          => $user,
                'last_activity' => time(),
				'isLoggedIn'    => true,
				'role'       	=> $user->role,
            ]);
            
			return redirect()->to(route_to('dashboard'));
        }

		$this->session->setFlashdata('message', lang('Files.Login_Invalid'));
		$this->session->setFlashdata('old', ['kd_pegawai' => $kd_pegawai]);
        return redirect()->to(route_to('auth-login'));
    }

	public function showAuthLogout(): string
	{
		$data = [
			'title_meta' => view('partials/title-meta', ['title' => 'Logout'])
		];
		return view('auth-logout', $data);
	}

    public function logout(): RedirectResponse
    {
        $this->session->destroy();
        return redirect()->to(route_to('auth-logout'));
    }

	public function showAuthLockScreen(): string|RedirectResponse
	{
		if (! $this->session->get('isLoggedIn')) {
			return redirect()->to(route_to('auth-login'));
		}

		$this->session->set('isLocked', true);
		
		$user = $this->session->get('user');
		$data = [
			'title_meta' => view('partials/title-meta', ['title' => 'Lock_Screen']),
			'username'   => is_object($user) ? ($user->kd_pegawai ?? '') : ($user['kd_pegawai'] ?? '')
		];

		return view('auth-lock-screen', $data);
	}

	public function lock(): RedirectResponse
	{
		if (! $this->session->get('isLoggedIn')) {
			return redirect()->to(route_to('auth-login'));
		}

		$this->session->set('isLocked', true);
		return redirect()->to(route_to('auth-lock'));
	}

	public function unlock(): RedirectResponse
	{
		if (! $this->session->get('isLoggedIn')) {
			return redirect()->to(route_to('auth-login'));
		}

		$user = $this->session->get('user');
		$username = is_object($user) ? ($user->kd_pegawai ?? '') : ($user['kd_pegawai'] ?? '');
		$password = (string) $this->request->getPost('password');

		if ($username === '' || $password === '') {
			$this->session->setFlashdata('message', lang('Files.Password_Required'));
			return redirect()->to(route_to('auth-lock'));
		}

		$validUser = $this->authModel->check_login($username, $password);
		if (! $validUser) {
			$this->session->setFlashdata('message', lang('Files.Password_Wrong'));
			return redirect()->to(route_to('auth-lock'));
		}

		$this->session->regenerate();
		$this->session->set('isLocked', false);
		$this->session->set('last_activity', time());
		$this->session->set('user', $validUser);

		return redirect()->to(route_to('dashboard'));
	}
}
