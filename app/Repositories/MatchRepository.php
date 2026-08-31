<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class MatchRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEvenementId(int $idEvenement): array
    {
        $statement = $this->pdo->prepare(
            'SELECT
                m.nom_match,
                tm.libelle_type_match
            FROM match_evenement m
            INNER JOIN type_match tm
                ON tm.id_type_match = m.id_type_match
            WHERE m.id_evenement = :id_evenement
            ORDER BY m.ordre_match ASC'
        );

        $statement->execute([
            'id_evenement' => $idEvenement,
        ]);

        return $statement->fetchAll();
    }
}