<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('isLoggedIn')) {
            return redirect()->to(route_to('auth-login'));
        }

        if (empty($arguments)) return null;

        $userRole = (string) $session->get('role'); // ex: 'admin'
        $allowed  = array_map('strtolower', $arguments);

        if (! in_array(strtolower($userRole), $allowed, true)) {
            $session->setFlashdata('message', lang('Files.Do_Not_Have_Access'));
            return redirect()->to(route_to('dashboard'));
        }
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}