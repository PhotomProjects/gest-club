<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class IntervenantRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findActive(): array
    {
        $statement = $this->pdo->query(
            'SELECT
                id_intervenant,
                nom_scene
            FROM intervenant
            WHERE statut_intervenant = \'ACTIF\'
            ORDER BY nom_scene ASC'
        );

        return $statement->fetchAll();
    }
}