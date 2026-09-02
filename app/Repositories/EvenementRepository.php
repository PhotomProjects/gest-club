<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class EvenementRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findUpcoming(): array
    {
        $statement = $this->pdo->query(
            'SELECT
            id_evenement,
            nom_evenement,
            image_evenement,
            date_heure_debut_evenement,
            date_heure_fin_evenement,
            statut_evenement,
            (
                SELECT COUNT(*)
                FROM place_evenement pe
                WHERE pe.id_evenement = e.id_evenement
                AND pe.statut_place = \'DISPONIBLE\'
            ) AS places_disponibles
        FROM evenement e
        WHERE e.date_heure_fin_evenement >= NOW()
        ORDER BY e.date_heure_debut_evenement ASC'
        );

        return $statement->fetchAll();
    }

    public function findById(int $id): ?array
    {
        // Récupération de l'événement.
        $statement = $this->pdo->prepare(
            'SELECT
            id_evenement,
            nom_evenement,
            description_evenement,
            image_evenement,
            date_heure_debut_evenement,
            date_heure_fin_evenement,
            statut_evenement
        FROM evenement
        WHERE id_evenement = :id'
        );

        $statement->execute([
            'id' => $id,
        ]);

        $evenement = $statement->fetch();

        if (!$evenement) {
            return null;
        }

        // Comptage des places disponibles.
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*)
        FROM place_evenement
        WHERE id_evenement = :id
        AND statut_place = \'DISPONIBLE\''
        );

        $statement->execute([
            'id' => $id,
        ]);

        $evenement['places_disponibles'] = (int) $statement->fetchColumn();

        return $evenement;
    }

    public function findByIdForUpdate(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT
            id_evenement,
            date_heure_debut_evenement,
            date_heure_fin_evenement,
            statut_evenement
        FROM evenement
        WHERE id_evenement = :id
        FOR UPDATE'
        );

        $statement->execute([
            'id' => $id,
        ]);

        $evenement = $statement->fetch();

        return $evenement ?: null;
    }
}