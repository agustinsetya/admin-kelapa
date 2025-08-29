<?php
namespace App\Controllers\Concerns;

trait ApiResponse
{
    protected function newCsrf(): array
    {
        $name = function_exists('csrf_token') ? csrf_token() : 'csrf_token';
        $hash = function_exists('csrf_hash')  ? csrf_hash()  : '';
        return ['name' => $name, 'hash' => $hash];
    }

    protected function jsonSuccess(array $payload = [], int $status = 200)
    {
        $payload['success'] = true;
        $payload['code']    = $status;
        $payload['csrf']    = $this->newCsrf();
        return $this->response->setStatusCode($status)->setJSON($payload);
    }

    protected function jsonError(string $message, int $status = 400, array $extra = [])
    {
        $payload = array_merge([
            'success' => false,
            'message' => $message,
            'code'    => $status,
            'csrf'    => $this->newCsrf(),
        ], $extra);

        return $this->response->setStatusCode($status)->setJSON($payload);
    }
}