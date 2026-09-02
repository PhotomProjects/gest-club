<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;
use InvalidArgumentException;
use RuntimeException;

class ReservationRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function countConfirmedPlacesByUserAndEvent(
        int $userId,
        int $eventId
    ): int {
        $statement = $this->pdo->prepare(
            'SELECT COUNT(rp.id_reservation_place)
            FROM reservation r
            INNER JOIN reservation_place rp
                ON rp.id_reservation = r.id_reservation
            WHERE r.id_utilisateur = :id_utilisateur
            AND r.id_evenement = :id_evenement
            AND r.statut_reservation = \'CONFIRMEE\''
        );

        $statement->execute([
            'id_utilisateur' => $userId,
            'id_evenement' => $eventId,
        ]);

        return (int) $statement->fetchColumn();
    }

    public function create(
        int $userId,
        int $eventId
    ): int {
        $statement = $this->pdo->prepare(
            'INSERT INTO reservation (
                statut_reservation,
                id_utilisateur,
                id_evenement
            ) VALUES (
                \'CONFIRMEE\',
                :id_utilisateur,
                :id_evenement
            )'
        );

        $statement->execute([
            'id_utilisateur' => $userId,
            'id_evenement' => $eventId,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function addPlace(
        int $reservationId,
        int $placeEventId,
        string $appliedPrice,
        int $position
    ): void {
        $statement = $this->pdo->prepare(
            'INSERT INTO reservation_place (
                prix_applique,
                position_place,
                id_place_evenement,
                id_reservation
            ) VALUES (
                :prix_applique,
                :position_place,
                :id_place_evenement,
                :id_reservation
            )'
        );

        $statement->execute([
            'prix_applique' => $appliedPrice,
            'position_place' => $position,
            'id_place_evenement' => $placeEventId,
            'id_reservation' => $reservationId,
        ]);
    }

    public function findByIdForUser(
        int $reservationId,
        int $userId
    ): ?array {
        $statement = $this->pdo->prepare(
            'SELECT
            r.id_reservation,
            r.date_reservation,
            r.statut_reservation,
            e.id_evenement,
            e.nom_evenement,
            e.date_heure_debut_evenement,
            e.date_heure_fin_evenement,
            e.image_evenement,
            b.id_billet,
            COUNT(rp.id_reservation_place) AS nb_places,
            SUM(rp.prix_applique) AS prix_total
        FROM reservation r
        INNER JOIN evenement e
            ON e.id_evenement = r.id_evenement
        INNER JOIN reservation_place rp
            ON rp.id_reservation = r.id_reservation
        INNER JOIN billet b
            ON b.id_reservation = r.id_reservation
        WHERE r.id_reservation = :id_reservation
        AND r.id_utilisateur = :id_utilisateur
        GROUP BY
            r.id_reservation,
            r.date_reservation,
            r.statut_reservation,
            e.id_evenement,
            e.nom_evenement,
            e.date_heure_debut_evenement,
            e.date_heure_fin_evenement,
            e.image_evenement,
            b.id_billet'
        );

        $statement->execute([
            'id_reservation' => $reservationId,
            'id_utilisateur' => $userId,
        ]);

        $reservation = $statement->fetch();

        return $reservation ?: null;
    }

    public function findByUserAndFilter(
        int $userId,
        string $filter
    ): array {
        $condition = match ($filter) {
            'a-venir' => '
            r.statut_reservation = \'CONFIRMEE\'
            AND e.statut_evenement <> \'ANNULE\'
            AND e.date_heure_debut_evenement > NOW()
        ',

            'passees' => '
            r.statut_reservation = \'CONFIRMEE\'
            AND e.statut_evenement <> \'ANNULE\'
            AND e.date_heure_debut_evenement <= NOW()
        ',

            'annulees' => '
            (
                r.statut_reservation = \'ANNULEE\'
                OR e.statut_evenement = \'ANNULE\'
            )
        ',

            default => throw new InvalidArgumentException(
                'Filtre de réservation invalide.'
            ),
        };

        $order = $filter === 'a-venir'
            ? 'e.date_heure_debut_evenement ASC'
            : 'e.date_heure_debut_evenement DESC';

        $statement = $this->pdo->prepare(
            'SELECT
            r.id_reservation,
            r.date_reservation,
            r.statut_reservation,
            e.id_evenement,
            e.nom_evenement,
            e.image_evenement,
            e.date_heure_debut_evenement,
            e.date_heure_fin_evenement,
            e.statut_evenement,
            b.id_billet,
            COUNT(rp.id_reservation_place) AS nb_places,
            SUM(rp.prix_applique) AS prix_total,
            MIN(p.tribune_place) AS tribune_place,
            MIN(p.niveau_place) AS niveau_place,
            GROUP_CONCAT(
                CONCAT(
                    p.rangee_place,
                    \'-\',
                    p.numero_place
                )
                ORDER BY rp.position_place
                SEPARATOR \', \'
            ) AS places_attribuees
        FROM reservation r
        INNER JOIN evenement e
            ON e.id_evenement = r.id_evenement
        INNER JOIN billet b
            ON b.id_reservation = r.id_reservation
        INNER JOIN reservation_place rp
            ON rp.id_reservation = r.id_reservation
        INNER JOIN place_evenement pe
            ON pe.id_place_evenement = rp.id_place_evenement
        INNER JOIN place p
            ON p.id_place = pe.id_place
        WHERE r.id_utilisateur = :id_utilisateur
        AND ' . $condition . '
        GROUP BY
            r.id_reservation,
            r.date_reservation,
            r.statut_reservation,
            e.id_evenement,
            e.nom_evenement,
            e.image_evenement,
            e.date_heure_debut_evenement,
            e.date_heure_fin_evenement,
            e.statut_evenement,
            b.id_billet
        ORDER BY ' . $order
        );

        $statement->execute([
            'id_utilisateur' => $userId,
        ]);

        return $statement->fetchAll();
    }

    public function findForCancellationForUpdate(
        int $reservationId,
        int $userId
    ): ?array {
        $statement = $this->pdo->prepare(
            'SELECT
            r.id_reservation,
            r.statut_reservation,
            e.id_evenement,
            e.date_heure_debut_evenement,
            e.statut_evenement,
            pr.id_presence
        FROM reservation r
        INNER JOIN evenement e
            ON e.id_evenement = r.id_evenement
        INNER JOIN billet b
            ON b.id_reservation = r.id_reservation
        LEFT JOIN presence pr
            ON pr.id_billet = b.id_billet
        WHERE r.id_reservation = :id_reservation
        AND r.id_utilisateur = :id_utilisateur
        FOR UPDATE'
        );

        $statement->execute([
            'id_reservation' => $reservationId,
            'id_utilisateur' => $userId,
        ]);

        $reservation = $statement->fetch();

        return $reservation ?: null;
    }

    public function cancel(int $reservationId): void
    {
        $statement = $this->pdo->prepare(
            'UPDATE reservation
        SET statut_reservation = \'ANNULEE\'
        WHERE id_reservation = :id_reservation
        AND statut_reservation = \'CONFIRMEE\''
        );

        $statement->execute([
            'id_reservation' => $reservationId,
        ]);

        if ($statement->rowCount() !== 1) {
            throw new RuntimeException(
                "La réservation n'a pas pu être annulée."
            );
        }
    }
}