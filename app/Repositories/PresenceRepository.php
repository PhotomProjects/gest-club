<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;
use RuntimeException;

class PresenceRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Recherche d'un billet à partir du contenu brut du QR Code.
    public function findTicketByCode(string $ticketCode): ?array
    {
        return $this->findTicketForControl(
            'b.code_billet = :code_billet',
            [
                'code_billet' => $ticketCode,
            ]
        );
    }

    // Recherche d'un billet à partir d'une référence comme R-2026-000014.
    public function findTicketByReservationReference(
        int $reservationId,
        int $year
    ): ?array {
        return $this->findTicketForControl(
            'r.id_reservation = :id_reservation
            AND YEAR(r.date_reservation) = :annee',
            [
                'id_reservation' => $reservationId,
                'annee' => $year,
            ]
        );
    }

    // Verrouille le billet pendant la validation de l'entrée.
    public function findTicketForValidationForUpdate(
        int $ticketId
    ): ?array {
        $statement = $this->pdo->prepare(
            'SELECT
                b.id_billet,
                r.id_reservation,
                r.statut_reservation,
                e.id_evenement,
                e.statut_evenement,
                pr.id_presence
            FROM billet b
            INNER JOIN reservation r
                ON r.id_reservation = b.id_reservation
            INNER JOIN evenement e
                ON e.id_evenement = r.id_evenement
            LEFT JOIN presence pr
                ON pr.id_billet = b.id_billet
            WHERE b.id_billet = :id_billet
            FOR UPDATE'
        );

        $statement->execute([
            'id_billet' => $ticketId,
        ]);

        $ticket = $statement->fetch();

        return $ticket ?: null;
    }

    // Enregistre l'entrée correspondant au billet.
    public function create(int $ticketId): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO presence (
                id_billet
            ) VALUES (
                :id_billet
            )'
        );

        $statement->execute([
            'id_billet' => $ticketId,
        ]);

        if ($statement->rowCount() !== 1) {
            throw new RuntimeException(
                "La présence n'a pas pu être enregistrée."
            );
        }

        return (int) $this->pdo->lastInsertId();
    }

    // Requête commune utilisée par les deux modes de recherche. La condition SQL est exclusivement définie par les méthodes internes du repository et ne provient jamais directement de l'utilisateur.
    private function findTicketForControl(
        string $condition,
        array $parameters
    ): ?array {
        $statement = $this->pdo->prepare(
            'SELECT
                b.id_billet,
                b.code_billet,

                r.id_reservation,
                r.date_reservation,
                r.statut_reservation,

                u.id_utilisateur,
                u.prenom,
                u.nom,
                u.email,

                e.id_evenement,
                e.nom_evenement,
                e.date_heure_debut_evenement,
                e.date_heure_fin_evenement,
                e.statut_evenement,

                pr.id_presence,
                pr.date_heure_controle,

                COUNT(rp.id_reservation_place) AS nb_places,
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
                ) AS places_attribuees,

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

            INNER JOIN utilisateur u
                ON u.id_utilisateur = r.id_utilisateur

            INNER JOIN evenement e
                ON e.id_evenement = r.id_evenement

            INNER JOIN reservation_place rp
                ON rp.id_reservation = r.id_reservation

            INNER JOIN place_evenement pe
                ON pe.id_place_evenement = rp.id_place_evenement

            INNER JOIN place p
                ON p.id_place = pe.id_place

            LEFT JOIN presence pr
                ON pr.id_billet = b.id_billet

            WHERE ' . $condition . '

            GROUP BY
                b.id_billet,
                b.code_billet,
                r.id_reservation,
                r.date_reservation,
                r.statut_reservation,
                u.id_utilisateur,
                u.prenom,
                u.nom,
                u.email,
                e.id_evenement,
                e.nom_evenement,
                e.date_heure_debut_evenement,
                e.date_heure_fin_evenement,
                e.statut_evenement,
                pr.id_presence,
                pr.date_heure_controle'
        );

        $statement->execute($parameters);

        $ticket = $statement->fetch();

        return $ticket ?: null;
    }

    // Retourne les 50 dernières présences enregistrées.
    public function findLatest(): array
    {
        $statement = $this->pdo->query(
            'SELECT
            pr.id_presence,
            pr.date_heure_controle,

            b.id_billet,

            u.prenom,
            u.nom,

            e.id_evenement,
            e.nom_evenement,
            e.date_heure_debut_evenement,

            COUNT(rp.id_reservation_place) AS nb_places,
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

        FROM presence pr

        INNER JOIN billet b
            ON b.id_billet = pr.id_billet

        INNER JOIN reservation r
            ON r.id_reservation = b.id_reservation

        INNER JOIN utilisateur u
            ON u.id_utilisateur = r.id_utilisateur

        INNER JOIN evenement e
            ON e.id_evenement = r.id_evenement

        INNER JOIN reservation_place rp
            ON rp.id_reservation = r.id_reservation

        INNER JOIN place_evenement pe
            ON pe.id_place_evenement = rp.id_place_evenement

        INNER JOIN place p
            ON p.id_place = pe.id_place

        GROUP BY
            pr.id_presence,
            pr.date_heure_controle,
            b.id_billet,
            u.id_utilisateur,
            u.prenom,
            u.nom,
            e.id_evenement,
            e.nom_evenement,
            e.date_heure_debut_evenement

        ORDER BY
            pr.date_heure_controle DESC,
            pr.id_presence DESC

        LIMIT 50'
        );

        return $statement->fetchAll();
    }

    // Retourne les statistiques globales de contrôle.
    public function getStatistics(): array
    {
        $statement = $this->pdo->query(
            'SELECT
            COUNT(DISTINCT pr.id_presence)
                AS billets_controles,

            COUNT(rp.id_reservation_place)
                AS places_admises,

            MAX(pr.date_heure_controle)
                AS dernier_controle

        FROM presence pr

        INNER JOIN billet b
            ON b.id_billet = pr.id_billet

        INNER JOIN reservation r
            ON r.id_reservation = b.id_reservation

        INNER JOIN reservation_place rp
            ON rp.id_reservation = r.id_reservation'
        );

        $statistics = $statement->fetch();

        return $statistics ?: [
            'billets_controles' => 0,
            'places_admises' => 0,
            'dernier_controle' => null,
        ];
    }
}