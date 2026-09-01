<?php

declare(strict_types=1);

namespace App\Core;

final class Auth
{
    private ?array $utilisateur;

    public function __construct(?array $utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }

    public function isAuthenticated(): bool
    {
        return isset(
            $this->utilisateur['id'],
            $this->utilisateur['role']
        );
    }

    public function requireLogin(): void
    {
        if (!$this->isAuthenticated()) {
            header('Location: /connexion.php');
            exit;
        }
    }

    public function requireRole(string $role): void
    {
        $this->requireLogin();

        if (($this->utilisateur['role'] ?? '') !== $role) {
            header('Location: /403.php');
            exit;
        }
    }
}