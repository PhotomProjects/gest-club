<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class BilletRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(
        int $reservationId,
        string $ticketCode
    ): int {
        $statement = $this->pdo->prepare(
            'INSERT INTO billet (
                code_billet,
                id_reservation
            ) VALUES (
                :code_billet,
                :id_reservation
            )'
        );

        $statement->execute([
            'code_billet' => $ticketCode,
            'id_reservation' => $reservationId,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function findByIdForUser(
        int $ticketId,
        int $userId
    ): ?array {
        $statement = $this->pdo->prepare(
            'SELECT
                b.id_billet,
                b.code_billet,
                r.id_reservation,
                r.date_reservation,
                r.statut_reservation,
                e.id_evenement,
                e.nom_evenement,
                e.image_evenement,
                e.date_heure_debut_evenement,
                e.date_heure_fin_evenement,
                e.statut_evenement,
                (
                    SELECT COUNT(*)
                    FROM reservation_place rp
                    WHERE rp.id_reservation = r.id_reservation
                ) AS nb_places,
                (
                    SELECT SUM(rp.prix_applique)
                    FROM reservation_place rp
                    WHERE rp.id_reservation = r.id_reservation
                ) AS prix_total,
                CASE
                    WHEN r.statut_reservation = \'ANNULEE\'
                        OR e.statut_evenement = \'ANNULE\'
                    THEN \'ANNULE\'
                    WHEN pr.id_presence IS NOT NULL
                    THEN \'UTILISE\'
                    ELSE \'ACTIF\'
                END AS statut_billet
            FROM billet b
            INNER JOIN reservation r
                ON r.id_reservation = b.id_reservation
            INNER JOIN evenement e
                ON e.id_evenement = r.id_evenement
            LEFT JOIN presence pr
                ON pr.id_billet = b.id_billet
            WHERE b.id_billet = :id_billet
            AND r.id_utilisateur = :id_utilisateur'
        );

        $statement->execute([
            'id_billet' => $ticketId,
            'id_utilisateur' => $userId,
        ]);

        $billet = $statement->fetch();

        return $billet ?: null;
    }

    public function findPlacesByIdForUser(
        int $ticketId,
        int $userId
    ): array {
        $statement = $this->pdo->prepare(
            'SELECT
                rp.position_place,
                rp.prix_applique,
                p.tribune_place,
                p.niveau_place,
                p.rangee_place,
                p.numero_place
            FROM billet b
            INNER JOIN reservation r
                ON r.id_reservation = b.id_reservation
            INNER JOIN reservation_place rp
                ON rp.id_reservation = r.id_reservation
            INNER JOIN place_evenement pe
                ON pe.id_place_evenement = rp.id_place_evenement
            INNER JOIN place p
                ON p.id_place = pe.id_place
            WHERE b.id_billet = :id_billet
            AND r.id_utilisateur = :id_utilisateur
            ORDER BY rp.position_place'
        );

        $statement->execute([
            'id_billet' => $ticketId,
            'id_utilisateur' => $userId,
        ]);

        return $statement->fetchAll();
    }
}