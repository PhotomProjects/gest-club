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
            m.id_match,
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

    public function findAllTypes(): array
    {
        $statement = $this->pdo->query(
            'SELECT
            id_type_match,
            libelle_type_match,
            nombre_catcheurs_min,
            nombre_catcheurs_max,
            nombre_camps
        FROM type_match
        ORDER BY id_type_match ASC'
        );

        return $statement->fetchAll();
    }

    public function findTypeById(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT
            id_type_match,
            libelle_type_match,
            nombre_catcheurs_min,
            nombre_catcheurs_max,
            nombre_camps
        FROM type_match
        WHERE id_type_match = :id'
        );

        $statement->execute([
            'id' => $id,
        ]);

        $typeMatch = $statement->fetch();

        return $typeMatch ?: null;
    }

    public function orderExists(
        int $eventId,
        int $order
    ): bool {
        $statement = $this->pdo->prepare(
            'SELECT 1
        FROM match_evenement
        WHERE id_evenement = :id_evenement
        AND ordre_match = :ordre_match
        LIMIT 1'
        );

        $statement->execute([
            'id_evenement' => $eventId,
            'ordre_match' => $order,
        ]);

        return $statement->fetchColumn() !== false;
    }

    public function create(
        int $eventId,
        int $typeId,
        string $name,
        int $order
    ): int {
        $statement = $this->pdo->prepare(
            'INSERT INTO match_evenement (
            nom_match,
            ordre_match,
            id_evenement,
            id_type_match
        ) VALUES (
            :nom_match,
            :ordre_match,
            :id_evenement,
            :id_type_match
        )'
        );

        $statement->execute([
            'nom_match' => $name,
            'ordre_match' => $order,
            'id_evenement' => $eventId,
            'id_type_match' => $typeId,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function addParticipation(
        int $matchId,
        int $intervenantId,
        string $role,
        ?int $camp,
        int $order
    ): void {
        $statement = $this->pdo->prepare(
            'INSERT INTO participation_match (
            role_participation,
            camp_participation,
            ordre_participation,
            id_match,
            id_intervenant
        ) VALUES (
            :role,
            :camp,
            :ordre,
            :id_match,
            :id_intervenant
        )'
        );

        $statement->execute([
            'role' => $role,
            'camp' => $camp,
            'ordre' => $order,
            'id_match' => $matchId,
            'id_intervenant' => $intervenantId,
        ]);
    }

    public function findById(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT
            me.id_match,
            me.nom_match,
            me.ordre_match,
            me.id_evenement,
            me.id_type_match,
            tm.libelle_type_match
        FROM match_evenement me
        INNER JOIN type_match tm
            ON tm.id_type_match = me.id_type_match
        WHERE me.id_match = :id'
        );

        $statement->execute([
            'id' => $id,
        ]);

        $match = $statement->fetch();

        return $match ?: null;
    }

    public function findParticipationsByMatchId(int $matchId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT
            pm.id_intervenant,
            pm.role_participation,
            pm.camp_participation,
            pm.ordre_participation,
            i.nom_scene
        FROM participation_match pm
        INNER JOIN intervenant i
            ON i.id_intervenant = pm.id_intervenant
        WHERE pm.id_match = :id_match
        ORDER BY pm.ordre_participation ASC'
        );

        $statement->execute([
            'id_match' => $matchId,
        ]);

        return $statement->fetchAll();
    }

    public function orderExistsForOtherMatch(
        int $eventId,
        int $order,
        int $matchId
    ): bool {
        $statement = $this->pdo->prepare(
            'SELECT 1
        FROM match_evenement
        WHERE id_evenement = :id_evenement
        AND ordre_match = :ordre_match
        AND id_match != :id_match
        LIMIT 1'
        );

        $statement->execute([
            'id_evenement' => $eventId,
            'ordre_match' => $order,
            'id_match' => $matchId,
        ]);

        return $statement->fetchColumn() !== false;
    }

    public function findByIdForUpdate(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT
            id_match,
            nom_match,
            ordre_match,
            id_evenement,
            id_type_match
        FROM match_evenement
        WHERE id_match = :id
        FOR UPDATE'
        );

        $statement->execute([
            'id' => $id,
        ]);

        $match = $statement->fetch();

        return $match ?: null;
    }

    public function update(
        int $matchId,
        int $typeId,
        string $name,
        int $order
    ): void {
        $statement = $this->pdo->prepare(
            'UPDATE match_evenement
        SET
            nom_match = :nom_match,
            ordre_match = :ordre_match,
            id_type_match = :id_type_match
        WHERE id_match = :id_match'
        );

        $statement->execute([
            'nom_match' => $name,
            'ordre_match' => $order,
            'id_type_match' => $typeId,
            'id_match' => $matchId,
        ]);
    }

    public function deleteParticipations(int $matchId): void
    {
        $statement = $this->pdo->prepare(
            'DELETE FROM participation_match
        WHERE id_match = :id_match'
        );

        $statement->execute([
            'id_match' => $matchId,
        ]);
    }

    public function delete(int $matchId): void
    {
        $statement = $this->pdo->prepare(
            'DELETE FROM match_evenement
        WHERE id_match = :id_match'
        );

        $statement->execute([
            'id_match' => $matchId,
        ]);
    }
}