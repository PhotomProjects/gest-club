<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\IntervenantRepository;
use DomainException;
use PDO;

class IntervenantService
{
    private IntervenantRepository $intervenantRepository;

    public function __construct(PDO $pdo)
    {
        $this->intervenantRepository = new IntervenantRepository($pdo);
    }

    public function create(
        string $name,
        string $status
    ): int {
        $name = trim($name);

        if ($name === '' || mb_strlen($name) > 100) {
            throw new DomainException(
                'Le nom de scène est invalide.'
            );
        }

        if (!in_array($status, ['ACTIF', 'INACTIF'], true)) {
            throw new DomainException(
                'Le statut est invalide.'
            );
        }

        if (
            $this->intervenantRepository->findByName($name) !== null
        ) {
            throw new DomainException(
                'Ce nom de scène existe déjà.'
            );
        }

        return $this->intervenantRepository->create($name, $status);
    }

    public function update(
        int $id,
        string $name,
        string $status
    ): void {
        $name = trim($name);

        if ($id <= 0) {
            throw new DomainException(
                "L'intervenant est invalide."
            );
        }

        if ($name === '' || mb_strlen($name) > 100) {
            throw new DomainException(
                'Le nom de scène est invalide.'
            );
        }

        if (!in_array($status, ['ACTIF', 'INACTIF'], true)) {
            throw new DomainException(
                'Le statut est invalide.'
            );
        }

        if (
            $this->intervenantRepository->findByNameExceptId($name, $id) !== null
        ) {
            throw new DomainException(
                'Ce nom de scène existe déjà.'
            );
        }

        $intervenant = $this->intervenantRepository->findById($id);

        if ($intervenant === null) {
            throw new DomainException(
                "L'intervenant est introuvable."
            );
        }

        $this->intervenantRepository->update($id, $name, $status);
    }
}