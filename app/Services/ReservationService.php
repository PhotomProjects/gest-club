<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\BilletRepository;
use App\Repositories\EvenementRepository;
use App\Repositories\PlaceRepository;
use App\Repositories\ReservationRepository;
use DomainException;
use PDO;
use Throwable;
use RuntimeException;

class ReservationService
{
    private PDO $pdo;
    private EvenementRepository $evenementRepository;
    private PlaceRepository $placeRepository;
    private ReservationRepository $reservationRepository;
    private BilletRepository $billetRepository;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->evenementRepository = new EvenementRepository($pdo);
        $this->placeRepository = new PlaceRepository($pdo);
        $this->reservationRepository = new ReservationRepository($pdo);
        $this->billetRepository = new BilletRepository($pdo);
    }

    public function confirm(
        int $userId,
        int $eventId,
        int $quantity,
        string $tribune,
        string $level
    ): int {
        $tribunesAutorisees = ['NORD', 'SUD', 'EST', 'OUEST',];
        $niveauxAutorises = ['BAS', 'MILIEU', 'HAUT',];

        if (
            $userId < 1 || $eventId < 1 || !in_array($quantity, [1, 2], true) || !in_array($tribune, $tribunesAutorisees, true) || !in_array($level, $niveauxAutorises, true)
        ) {
            throw new DomainException(
                'Données de réservation invalides.'
            );
        }

        $this->pdo->beginTransaction();

        try {
            // Le verrouillage de l'événement sérialise les confirmations concurrentes pour cet événement.
            $evenement = $this->evenementRepository->findByIdForUpdate($eventId);

            if ($evenement === null) {
                throw new DomainException(
                    'Événement introuvable.'
                );
            }

            $dateDebut = new \DateTimeImmutable($evenement['date_heure_debut_evenement']);

            if (
                $evenement['statut_evenement'] !== 'OUVERT' || $dateDebut <= new \DateTimeImmutable()
            ) {
                throw new DomainException(
                    "Cet évènement n'est plus disponible à la réservation."
                );
            }

            // Le quota est revérifié dans la transaction. Le contrôle effectué au récapitulatif ne suffit pas.
            $nombrePlacesDejaReservees = $this->reservationRepository->countConfirmedPlacesByUserAndEvent($userId, $eventId);

            if (
                $nombrePlacesDejaReservees + $quantity > 2
            ) {
                throw new DomainException(
                    'Vous pouvez réserver au maximum deux places pour cet évènement.'
                );
            }

            // Sélection et verrouillage des places encore disponibles.
            $places = $this->placeRepository->findAvailablePlacesForUpdate($eventId, $tribune, $level, $quantity);

            if (count($places) !== $quantity) {
                throw new DomainException(
                    'Les places sélectionnées ne sont plus disponibles.'
                );
            }

            // Création de la réservation confirmée.
            $reservationId = $this->reservationRepository->create($userId, $eventId);

            $placeEventIds = [];

            foreach ($places as $index => $place) {
                $placeEventId = (int) $place['id_place_evenement'];

                $placeEventIds[] = $placeEventId;

                $this->reservationRepository->addPlace(
                    $reservationId,
                    $placeEventId,
                    (string) $place['prix_place'],
                    $index + 1
                );
            }

            // Les places attribuées deviennent indisponibles.
            $this->placeRepository->markPlacesAsReserved($placeEventIds);

            // Un seul billet est créé pour toute la réservation.
            $ticketCode = bin2hex(random_bytes(32));

            $this->billetRepository->create($reservationId, $ticketCode);

            $this->pdo->commit();

            return $reservationId;
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }

    public function cancel(
        int $userId,
        int $reservationId
    ): void {
        if ($userId < 1 || $reservationId < 1) {
            throw new DomainException(
                "Données d'annulation invalides."
            );
        }

        $this->pdo->beginTransaction();

        try {
            // La réservation et son événement sont verrouillés pendant toute l'annulation.
            $reservation = $this->reservationRepository->findForCancellationForUpdate($reservationId, $userId);

            if ($reservation === null) {
                throw new DomainException(
                    'Réservation introuvable.'
                );
            }

            if (
                $reservation['statut_reservation'] !== 'CONFIRMEE'
            ) {
                throw new DomainException(
                    'Cette réservation est déjà annulée.'
                );
            }

            if (
                $reservation['statut_evenement'] !== 'OUVERT'
            ) {
                throw new DomainException(
                    "L'évènement est annulé."
                );
            }

            $dateDebut = new \DateTimeImmutable($reservation['date_heure_debut_evenement']);

            if ($dateDebut <= new \DateTimeImmutable()) {
                throw new DomainException(
                    "Cette réservation ne peut plus être annulée après le début de l'évènement."
                );
            }

            // Récupération et verrouillage des places appartenant réellement à cette réservation.
            $placeEventIds = $this->placeRepository->findReservedPlaceIdsByReservationForUpdate($reservationId);

            if ($placeEventIds === []) {
                throw new RuntimeException(
                    'Aucune place associée à la réservation.'
                );
            }

            // Annulation de la réservation.
            $this->reservationRepository->cancel($reservationId);

            // Libération des places.
            $this->placeRepository->markPlacesAsAvailable($placeEventIds);

            $this->pdo->commit();
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }
}