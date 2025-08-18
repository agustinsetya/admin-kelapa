<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AutentikasiFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Cek sudah login?
        if (! $session->get('isLoggedIn')) {
            return redirect()->to(route_to('auth-login'));
        }

        // Cek timeout session
        $last = $session->get('last_activity');
        $exp  = config('App')->sessionExpiration ?? 7200; // default 2 jam

        if ($last && (time() - $last > $exp)) {
            $session->destroy();
            return redirect()->to(route_to('auth-login'));
        }

        // update last_activity
        $session->set('last_activity', time());
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}