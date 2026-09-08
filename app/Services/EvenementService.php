<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\EvenementRepository;
use App\Repositories\PlaceRepository;
use App\Repositories\ReservationRepository;
use DateTimeImmutable;
use DomainException;
use PDO;
use Throwable;

class EvenementService
{
    private PDO $pdo;
    private EvenementRepository $evenementRepository;
    private PlaceRepository $placeRepository;
    private ReservationRepository $reservationRepository;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->evenementRepository = new EvenementRepository($pdo);
        $this->placeRepository = new PlaceRepository($pdo);
        $this->reservationRepository = new ReservationRepository($pdo);
    }

    public function create(
        string $nom,
        string $description,
        ?string $image,
        string $dateDebut,
        string $dateFin
    ): int {
        $nom = trim($nom);
        $description = trim($description);

        if (
            $nom === '' || mb_strlen($nom) > 150
        ) {
            throw new DomainException(
                "Le nom de l'évènement est invalide."
            );
        }

        if (
            $description === '' || mb_strlen($description) > 2000
        ) {
            throw new DomainException(
                "La description de l'évènement est invalide."
            );
        }

        $debut = DateTimeImmutable::createFromFormat('!Y-m-d H:i', $dateDebut);
        $fin = DateTimeImmutable::createFromFormat('!Y-m-d H:i', $dateFin);

        if (
            $debut === false
            || $fin === false
            || $debut->format('Y-m-d H:i') !== $dateDebut
            || $fin->format('Y-m-d H:i') !== $dateFin
        ) {
            throw new DomainException(
                "Les dates de l'évènement sont invalides."
            );
        }

        if ($fin <= $debut) {
            throw new DomainException(
                "La date de fin doit être postérieure à la date de début."
            );
        }

        if ($debut <= new DateTimeImmutable()) {
            throw new DomainException(
                "La date de début doit être postérieure à la date actuelle."
            );
        }

        $this->pdo->beginTransaction();

        try {
            $eventId = $this->evenementRepository->create(
                $nom,
                $description,
                $image,
                $debut->format('Y-m-d H:i:s'),
                $fin->format('Y-m-d H:i:s')
            );

            $this->placeRepository->createForEvent($eventId);

            $this->pdo->commit();

            return $eventId;
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }

    public function update(
        int $id,
        string $nom,
        string $description,
        ?string $image,
        string $dateDebut,
        string $dateFin
    ): void {
        $nom = trim($nom);
        $description = trim($description);

        if ($id <= 0) {
            throw new DomainException(
                "L'évènement est invalide."
            );
        }

        if ($nom === '' || mb_strlen($nom) > 150) {
            throw new DomainException(
                "Le nom de l'évènement est invalide."
            );
        }

        if (
            $description === '' || mb_strlen($description) > 2000
        ) {
            throw new DomainException(
                "La description de l'évènement est invalide."
            );
        }

        $debut = DateTimeImmutable::createFromFormat('!Y-m-d H:i', $dateDebut);
        $fin = DateTimeImmutable::createFromFormat('!Y-m-d H:i', $dateFin);

        if (
            $debut === false
            || $fin === false
            || $debut->format('Y-m-d H:i') !== $dateDebut
            || $fin->format('Y-m-d H:i') !== $dateFin
        ) {
            throw new DomainException(
                "Les dates de l'évènement sont invalides."
            );
        }

        if ($fin <= $debut) {
            throw new DomainException(
                "La date de fin doit être postérieure à la date de début."
            );
        }

        if ($debut <= new DateTimeImmutable()) {
            throw new DomainException(
                "La date de début doit être postérieure à la date actuelle."
            );
        }

        $this->pdo->beginTransaction();

        try {
            // Verrouille l'évènement pendant la vérification et sa modification.
            $evenement = $this->evenementRepository->findByIdForUpdate($id);

            if ($evenement === null) {
                throw new DomainException(
                    "L'évènement est introuvable."
                );
            }

            if ($evenement['statut_evenement'] === 'ANNULE') {
                throw new DomainException(
                    "Un évènement annulé ne peut pas être modifié."
                );
            }

            $dateFinActuelle = new DateTimeImmutable(
                $evenement['date_heure_fin_evenement']
            );

            $maintenant = new DateTimeImmutable();

            if ($dateFinActuelle <= $maintenant) {
                throw new DomainException(
                    "Un évènement terminé ne peut pas être modifié."
                );
            }

            $this->evenementRepository->update(
                $id,
                $nom,
                $description,
                $image,
                $debut->format('Y-m-d H:i:s'),
                $fin->format('Y-m-d H:i:s')
            );

            $this->pdo->commit();
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }

    public function cancel(int $id): void
    {
        if ($id <= 0) {
            throw new DomainException(
                "L'évènement est invalide."
            );
        }

        $this->pdo->beginTransaction();

        try {
            // Verrouille l'événement pendant toute l'annulation.
            $evenement = $this->evenementRepository->findByIdForUpdate($id);

            if ($evenement === null) {
                throw new DomainException(
                    "L'évènement est introuvable."
                );
            }

            if ($evenement['statut_evenement'] === 'ANNULE') {
                throw new DomainException(
                    "L'évènement est déjà annulé."
                );
            }

            $dateFin = new DateTimeImmutable($evenement['date_heure_fin_evenement']);
            $maintenant = new DateTimeImmutable();

            if ($dateFin <= $maintenant) {
                throw new DomainException(
                    "Un évènement terminé ne peut pas être annulé."
                );
            }

            // Annulation des réservations confirmées.
            $this->reservationRepository->cancelByEventId($id);

            // Libération de toutes les places réservées.
            $this->placeRepository->markPlacesAsAvailableByEventId($id);

            // Annulation de l'évènement.
            $this->evenementRepository->cancel($id);

            $this->pdo->commit();
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }
}