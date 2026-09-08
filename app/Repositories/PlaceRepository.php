<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;
use RuntimeException;

class PlaceRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAvailabilityByEventId(int $eventId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT
                p.tribune_place,
                p.niveau_place,
                p.prix_place,
                COUNT(*) AS places_total,
                SUM(
                    CASE
                        WHEN pe.statut_place = \'DISPONIBLE\' THEN 1
                        ELSE 0
                    END
                ) AS places_disponibles
            FROM place_evenement pe
            INNER JOIN place p
                ON p.id_place = pe.id_place
            WHERE pe.id_evenement = :id_evenement
            GROUP BY
                p.tribune_place,
                p.niveau_place,
                p.prix_place
            ORDER BY
                p.tribune_place,
                p.niveau_place'
        );

        $statement->execute([
            'id_evenement' => $eventId,
        ]);

        return $statement->fetchAll();
    }

    public function findAvailablePlacesForUpdate(
        int $eventId,
        string $tribune,
        string $level,
        int $quantity
    ): array {
        $statement = $this->pdo->prepare(
            'SELECT
                pe.id_place_evenement,
                p.numero_place,
                p.rangee_place,
                p.prix_place
            FROM place_evenement pe
            INNER JOIN place p
                ON p.id_place = pe.id_place
            WHERE pe.id_evenement = :id_evenement
            AND p.tribune_place = :tribune
            AND p.niveau_place = :niveau
            AND pe.statut_place = \'DISPONIBLE\'
            ORDER BY pe.id_place_evenement
            LIMIT :quantite
            FOR UPDATE'
        );

        $statement->bindValue(
            ':id_evenement',
            $eventId,
            PDO::PARAM_INT
        );

        $statement->bindValue(
            ':tribune',
            $tribune,
            PDO::PARAM_STR
        );

        $statement->bindValue(
            ':niveau',
            $level,
            PDO::PARAM_STR
        );

        $statement->bindValue(
            ':quantite',
            $quantity,
            PDO::PARAM_INT
        );

        $statement->execute();

        return $statement->fetchAll();
    }

    public function markPlacesAsReserved(array $placeEventIds): void
    {
        $statement = $this->pdo->prepare(
            'UPDATE place_evenement
            SET statut_place = \'RESERVEE\'
            WHERE id_place_evenement = :id_place_evenement
            AND statut_place = \'DISPONIBLE\''
        );

        foreach ($placeEventIds as $placeEventId) {
            $statement->execute([
                'id_place_evenement' => $placeEventId,
            ]);

            if ($statement->rowCount() !== 1) {
                throw new RuntimeException(
                    "Une place sélectionnée n'est plus disponible."
                );
            }
        }
    }

    public function findReservedPlaceIdsByReservationForUpdate(
        int $reservationId
    ): array {
        $statement = $this->pdo->prepare(
            'SELECT pe.id_place_evenement
        FROM reservation_place rp
        INNER JOIN place_evenement pe
            ON pe.id_place_evenement = rp.id_place_evenement
        WHERE rp.id_reservation = :id_reservation
        ORDER BY rp.position_place
        FOR UPDATE'
        );

        $statement->execute([
            'id_reservation' => $reservationId,
        ]);

        return array_map(
            static fn(array $place): int =>
            (int) $place['id_place_evenement'],
            $statement->fetchAll()
        );
    }

    public function markPlacesAsAvailable(
        array $placeEventIds
    ): void {
        $statement = $this->pdo->prepare(
            'UPDATE place_evenement
        SET statut_place = \'DISPONIBLE\'
        WHERE id_place_evenement = :id_place_evenement
        AND statut_place = \'RESERVEE\''
        );

        foreach ($placeEventIds as $placeEventId) {
            $statement->execute([
                'id_place_evenement' => $placeEventId,
            ]);

            if ($statement->rowCount() !== 1) {
                throw new RuntimeException(
                    "Une place réservée n'a pas pu être libérée."
                );
            }
        }
    }

    public function markPlacesAsAvailableByEventId(
        int $eventId
    ): void {
        $statement = $this->pdo->prepare(
            'UPDATE place_evenement
        SET statut_place = \'DISPONIBLE\'
        WHERE id_evenement = :id_evenement
        AND statut_place = \'RESERVEE\''
        );

        $statement->execute([
            'id_evenement' => $eventId,
        ]);
    }

    public function createForEvent(int $eventId): void
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO place_evenement (
            id_evenement,
            id_place
        )
        SELECT
            :id_evenement,
            id_place
        FROM place'
        );

        $statement->execute([
            'id_evenement' => $eventId,
        ]);
    }
}