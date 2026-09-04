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

    public function findAll(): array
    {
        $statement = $this->pdo->query(
            'SELECT
            id_intervenant,
            nom_scene,
            statut_intervenant
        FROM intervenant
        ORDER BY nom_scene ASC'
        );

        return $statement->fetchAll();
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

    public function findByName(string $name): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT
            id_intervenant,
            nom_scene,
            statut_intervenant
        FROM intervenant
        WHERE nom_scene = :nom_scene'
        );

        $statement->execute([
            'nom_scene' => $name,
        ]);

        $intervenant = $statement->fetch();

        return $intervenant ?: null;
    }

    public function create(
        string $name,
        string $status
    ): int {
        $statement = $this->pdo->prepare(
            'INSERT INTO intervenant (
            nom_scene,
            statut_intervenant
        ) VALUES (
            :nom_scene,
            :statut_intervenant
        )'
        );

        $statement->execute([
            'nom_scene' => $name,
            'statut_intervenant' => $status,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT
            id_intervenant,
            nom_scene,
            statut_intervenant
        FROM intervenant
        WHERE id_intervenant = :id'
        );

        $statement->execute([
            'id' => $id,
        ]);

        $intervenant = $statement->fetch();

        return $intervenant ?: null;
    }

    public function findByNameExceptId(
        string $name,
        int $id
    ): ?array {
        $statement = $this->pdo->prepare(
            'SELECT
            id_intervenant,
            nom_scene,
            statut_intervenant
        FROM intervenant
        WHERE nom_scene = :nom_scene
        AND id_intervenant != :id'
        );

        $statement->execute([
            'nom_scene' => $name,
            'id' => $id,
        ]);

        $intervenant = $statement->fetch();

        return $intervenant ?: null;
    }

    public function update(
        int $id,
        string $name,
        string $status
    ): void {
        $statement = $this->pdo->prepare(
            'UPDATE intervenant
        SET
            nom_scene = :nom_scene,
            statut_intervenant = :statut_intervenant
        WHERE id_intervenant = :id'
        );

        $statement->execute([
            'nom_scene' => $name,
            'statut_intervenant' => $status,
            'id' => $id,
        ]);
    }
}