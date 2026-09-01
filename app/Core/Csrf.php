<?php

declare(strict_types=1);

namespace App\Core;

final class Csrf
{
    public function getToken(): string
    {
        if (
            !isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])
        ) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public function verify(mixed $receivedToken): void
    {
        if (
            !is_string($receivedToken) || !hash_equals($this->getToken(), $receivedToken)
        ) {
            http_response_code(403);
            exit('Requête invalide.');
        }
    }
}