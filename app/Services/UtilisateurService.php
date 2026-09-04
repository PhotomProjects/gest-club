<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UtilisateurRepository;
use DomainException;
use PDO;

class UtilisateurService
{
    private UtilisateurRepository $utilisateurRepository;

    public function __construct(PDO $pdo)
    {
        $this->utilisateurRepository = new UtilisateurRepository($pdo);
    }

    public function updateRole(
        int $idUtilisateur,
        string $role
    ): void {
        if ($idUtilisateur <= 0) {
            throw new DomainException(
                "L'utilisateur est invalide."
            );
        }

        if (!in_array($role, ['MEMBRE', 'ADMIN'], true)) {
            throw new DomainException(
                'Le rôle sélectionné est invalide.'
            );
        }

        $utilisateur = $this->utilisateurRepository->findById($idUtilisateur);

        if ($utilisateur === null) {
            throw new DomainException(
                "L'utilisateur est introuvable."
            );
        }

        // Il doit toujours rester au moins un administrateur.
        if (
            $utilisateur['role_utilisateur'] === 'ADMIN'
            && $role === 'MEMBRE'
            && $this->utilisateurRepository->countAdmins() <= 1
        ) {
            throw new DomainException(
                'Le dernier administrateur ne peut pas devenir membre.'
            );
        }

        $this->utilisateurRepository->updateRole(
            $idUtilisateur,
            $role
        );
    }
}