<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PresenceRepository;
use DomainException;
use PDO;
use Throwable;

class PresenceService
{
    private PDO $pdo;
    private PresenceRepository $presenceRepository;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->presenceRepository = new PresenceRepository($pdo);
    }

    // Recherche un billet à partir d'un QR Code ou d'une référence de réservation.
    public function findTicket(string $identifier): array
    {
        $identifier = trim($identifier);

        if ($identifier === '' || mb_strlen($identifier) > 100) {
            throw new DomainException(
                'Le code du billet est invalide.'
            );
        }

        $ticket = null;

        // Code brut contenu dans le QR Code.
        if (
            preg_match('/^[0-9a-f]{64}$/i', $identifier) === 1
        ) {
            $ticket = $this->presenceRepository->findTicketByCode(
                mb_strtolower($identifier)
            );
        }

        // Référence de réservation : R-2026-000014.
        if (
            $ticket === null
            && preg_match('/^R-(\d{4})-(\d{6,})$/i', $identifier, $matches) === 1
        ) {
            $year = (int) $matches[1];
            $reservationId = (int) $matches[2];

            $ticket = $this->presenceRepository->findTicketByReservationReference($reservationId, $year);
        }

        if ($ticket === null) {
            throw new DomainException(
                'Aucun billet ne correspond à ce code.'
            );
        }

        return $ticket;
    }

    // Valide définitivement l'entrée et enregistre la présence.
    public function validateEntry(int $ticketId): int
    {
        if ($ticketId < 1) {
            throw new DomainException(
                'Identifiant du billet invalide.'
            );
        }

        $this->pdo->beginTransaction();

        try {
            // Le billet est verrouillé pour empêcher deux
            // validations simultanées.
            $ticket = $this->presenceRepository->findTicketForValidationForUpdate($ticketId);

            if ($ticket === null) {
                throw new DomainException(
                    'Billet introuvable.'
                );
            }

            if (
                $ticket['statut_reservation'] !== 'CONFIRMEE'
            ) {
                throw new DomainException(
                    'Cette réservation est annulée.'
                );
            }

            if (
                $ticket['statut_evenement'] === 'ANNULE'
            ) {
                throw new DomainException(
                    "L'évènement associé à ce billet est annulé."
                );
            }

            if ($ticket['id_presence'] !== null) {
                throw new DomainException(
                    'Ce billet a déjà été utilisé.'
                );
            }

            $presenceId = $this->presenceRepository->create($ticketId);

            $this->pdo->commit();

            return $presenceId;
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }
}