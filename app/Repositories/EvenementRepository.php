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
    public function findAll(): array
    {
        $statement = $this->pdo->query(
            'SELECT
            e.id_evenement,
            e.nom_evenement,
            e.date_heure_debut_evenement,
            e.date_heure_fin_evenement,
            e.statut_evenement,
            SUM(
                CASE
                    WHEN pe.statut_place = \'DISPONIBLE\' THEN 1
                    ELSE 0
                END
            ) AS places_disponibles
        FROM evenement e
        LEFT JOIN place_evenement pe
            ON pe.id_evenement = e.id_evenement
        GROUP BY
            e.id_evenement,
            e.nom_evenement,
            e.date_heure_debut_evenement,
            e.date_heure_fin_evenement,
            e.statut_evenement
        ORDER BY e.date_heure_debut_evenement DESC'
        );

        return $statement->fetchAll();
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

        // Comptage des places de l'événement.
        $statement = $this->pdo->prepare(
            'SELECT
        COUNT(*) AS places_total,
        SUM(
            CASE
                WHEN statut_place = \'DISPONIBLE\' THEN 1
                ELSE 0
            END
        ) AS places_disponibles,
        SUM(
            CASE
                WHEN statut_place = \'RESERVEE\' THEN 1
                ELSE 0
            END
        ) AS places_reservees
        FROM place_evenement
        WHERE id_evenement = :id'
        );

        $statement->execute([
            'id' => $id,
        ]);

        $places = $statement->fetch();

        $evenement['places_total'] = (int) $places['places_total'];
        $evenement['places_disponibles'] = (int) $places['places_disponibles'];
        $evenement['places_reservees'] = (int) $places['places_reservees'];

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

    public function create(
        string $nom,
        string $description,
        ?string $image,
        string $dateDebut,
        string $dateFin
    ): int {
        $statement = $this->pdo->prepare(
            'INSERT INTO evenement (
            nom_evenement,
            description_evenement,
            image_evenement,
            date_heure_debut_evenement,
            date_heure_fin_evenement
        ) VALUES (
            :nom,
            :description,
            :image,
            :date_debut,
            :date_fin
        )'
        );

        $statement->execute([
            'nom' => $nom,
            'description' => $description,
            'image' => $image,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(
        int $id,
        string $nom,
        string $description,
        ?string $image,
        string $dateDebut,
        string $dateFin
    ): void {
        $statement = $this->pdo->prepare(
            'UPDATE evenement
        SET
            nom_evenement = :nom,
            description_evenement = :description,
            image_evenement = :image,
            date_heure_debut_evenement = :date_debut,
            date_heure_fin_evenement = :date_fin
        WHERE id_evenement = :id'
        );

        $statement->execute([
            'id' => $id,
            'nom' => $nom,
            'description' => $description,
            'image' => $image,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
        ]);
    }

    public function cancel(int $id): void
    {
        $statement = $this->pdo->prepare(
            'UPDATE evenement
        SET statut_evenement = \'ANNULE\'
        WHERE id_evenement = :id'
        );

        $statement->execute([
            'id' => $id,
        ]);
    }
}