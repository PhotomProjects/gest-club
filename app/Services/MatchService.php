<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\EvenementRepository;
use App\Repositories\IntervenantRepository;
use App\Repositories\MatchRepository;
use DateTimeImmutable;
use DomainException;
use PDO;
use Throwable;

class MatchService
{
    private PDO $pdo;
    private MatchRepository $matchRepository;
    private EvenementRepository $evenementRepository;
    private IntervenantRepository $intervenantRepository;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->matchRepository = new MatchRepository($pdo);
        $this->evenementRepository = new EvenementRepository($pdo);
        $this->intervenantRepository = new IntervenantRepository($pdo);
    }

    public function create(
        int $eventId,
        string $name,
        int $typeId,
        int $order,
        array $wrestlers,
        array $camps,
        int $referee,
        array $managers,
        array $managerCamps
    ): int {
        $name = trim($name);

        // Informations principales.
        if ($eventId <= 0) {
            throw new DomainException(
                "L'évènement est invalide."
            );
        }

        if ($name === '' || mb_strlen($name) > 150) {
            throw new DomainException(
                'Le nom du match est invalide.'
            );
        }

        if ($typeId <= 0) {
            throw new DomainException(
                'Le type de match est obligatoire.'
            );
        }

        if ($order < 1 || $order > 255) {
            throw new DomainException(
                "L'ordre du match est invalide."
            );
        }

        $typeMatch = $this->matchRepository->findTypeById($typeId);

        if ($typeMatch === null) {
            throw new DomainException(
                'Le type de match sélectionné est invalide.'
            );
        }

        // Normalisation des identifiants.
        $wrestlers = $this->normalizeIntegerList($wrestlers);
        $camps = $this->normalizeIntegerList($camps);
        $managers = $this->normalizeIntegerList($managers);
        $managerCamps = $this->normalizeIntegerList($managerCamps);

        $minWrestlers = (int) $typeMatch['nombre_catcheurs_min'];
        $maxWrestlers = (int) $typeMatch['nombre_catcheurs_max'];
        $campCount = (int) $typeMatch['nombre_camps'];

        // Catcheurs.
        $wrestlerCount = count($wrestlers);

        if (
            $wrestlerCount < $minWrestlers || $wrestlerCount > $maxWrestlers
        ) {
            throw new DomainException(
                'Le nombre de catcheurs ne correspond pas au type de match.'
            );
        }

        if (count($camps) !== $wrestlerCount) {
            throw new DomainException(
                'Chaque catcheur doit avoir un camp.'
            );
        }

        foreach ($wrestlers as $wrestler) {
            if ($wrestler <= 0) {
                throw new DomainException(
                    'Tous les catcheurs doivent être sélectionnés.'
                );
            }
        }

        foreach ($camps as $camp) {
            if ($camp < 1 || $camp > $campCount) {
                throw new DomainException(
                    'Le camp sélectionné est invalide.'
                );
            }
        }

        // Tous les camps prévus doivent être représentés.
        $usedCamps = array_unique($camps);

        if (count($usedCamps) !== $campCount) {
            throw new DomainException(
                'Chaque camp doit contenir au moins un catcheur.'
            );
        }

        // Arbitre.
        if ($referee <= 0) {
            throw new DomainException(
                'Un arbitre doit être sélectionné.'
            );
        }

        // Managers.
        if (count($managers) > 2) {
            throw new DomainException(
                'Un match ne peut pas avoir plus de deux managers.'
            );
        }

        if (count($managers) !== count($managerCamps)) {
            throw new DomainException(
                'Chaque manager doit avoir un camp.'
            );
        }

        foreach ($managers as $manager) {
            if ($manager <= 0) {
                throw new DomainException(
                    'Tous les managers doivent être sélectionnés.'
                );
            }
        }

        foreach ($managerCamps as $camp) {
            if ($camp < 1 || $camp > $campCount) {
                throw new DomainException(
                    'Le camp du manager est invalide.'
                );
            }
        }

        // Un même intervenant ne peut jouer qu'un rôle dans ce match.
        $participants = array_merge($wrestlers, [$referee], $managers);

        if (
            count($participants)
            !== count(array_unique($participants))
        ) {
            throw new DomainException(
                "Un intervenant ne peut apparaître qu'une seule fois dans un match."
            );
        }

        // Tous les intervenants sélectionnés doivent être actifs.
        $activeIntervenants = $this->intervenantRepository->findActive();

        $activeIds = array_map(
            'intval',
            array_column(
                $activeIntervenants,
                'id_intervenant'
            )
        );

        foreach ($participants as $participant) {
            if (!in_array($participant, $activeIds, true)) {
                throw new DomainException(
                    'Un intervenant sélectionné est invalide ou inactif.'
                );
            }
        }

        $this->pdo->beginTransaction();

        try {
            // Empêche une modification concurrente de l'événement.
            $event = $this->evenementRepository->findByIdForUpdate($eventId);

            if ($event === null) {
                throw new DomainException(
                    "L'évènement est introuvable."
                );
            }

            if ($event['statut_evenement'] === 'ANNULE') {
                throw new DomainException(
                    "Impossible d'ajouter un match à un évènement annulé."
                );
            }

            $eventEnd = new DateTimeImmutable(
                $event['date_heure_fin_evenement']
            );

            if ($eventEnd <= new DateTimeImmutable()) {
                throw new DomainException(
                    "Impossible d'ajouter un match à un évènement terminé."
                );
            }

            if (
                $this->matchRepository->orderExists(
                    $eventId,
                    $order
                )
            ) {
                throw new DomainException(
                    "Un match utilise déjà cet ordre dans le programme."
                );
            }

            $matchId = $this->matchRepository->create(
                $eventId,
                $typeId,
                $name,
                $order
            );

            $participationOrder = 1;

            // Catcheurs.
            foreach ($wrestlers as $index => $wrestler) {
                $this->matchRepository->addParticipation(
                    $matchId,
                    $wrestler,
                    'CATCHEUR',
                    $camps[$index],
                    $participationOrder
                );

                $participationOrder++;
            }

            // Arbitre.
            $this->matchRepository->addParticipation(
                $matchId,
                $referee,
                'ARBITRE',
                null,
                $participationOrder
            );

            $participationOrder++;

            // Managers.
            foreach ($managers as $index => $manager) {
                $this->matchRepository->addParticipation(
                    $matchId,
                    $manager,
                    'MANAGER',
                    $managerCamps[$index],
                    $participationOrder
                );

                $participationOrder++;
            }

            $this->pdo->commit();

            return $matchId;
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }

    public function update(
        int $matchId,
        string $name,
        int $typeId,
        int $order,
        array $wrestlers,
        array $camps,
        int $referee,
        array $managers,
        array $managerCamps
    ): void {
        $name = trim($name);

        if ($matchId <= 0) {
            throw new DomainException(
                'Le match est invalide.'
            );
        }

        if ($name === '' || mb_strlen($name) > 150) {
            throw new DomainException(
                'Le nom du match est invalide.'
            );
        }

        if ($typeId <= 0) {
            throw new DomainException(
                'Le type de match est obligatoire.'
            );
        }

        if ($order < 1 || $order > 255) {
            throw new DomainException(
                "L'ordre du match est invalide."
            );
        }

        $typeMatch = $this->matchRepository->findTypeById($typeId);

        if ($typeMatch === null) {
            throw new DomainException(
                'Le type de match sélectionné est invalide.'
            );
        }

        $wrestlers = $this->normalizeIntegerList($wrestlers);
        $camps = $this->normalizeIntegerList($camps);
        $managers = $this->normalizeIntegerList($managers);
        $managerCamps = $this->normalizeIntegerList($managerCamps);

        $minWrestlers = (int) $typeMatch['nombre_catcheurs_min'];
        $maxWrestlers = (int) $typeMatch['nombre_catcheurs_max'];
        $campCount = (int) $typeMatch['nombre_camps'];

        // Catcheurs.
        $wrestlerCount = count($wrestlers);

        if (
            $wrestlerCount < $minWrestlers || $wrestlerCount > $maxWrestlers
        ) {
            throw new DomainException(
                'Le nombre de catcheurs ne correspond pas au type de match.'
            );
        }

        if (count($camps) !== $wrestlerCount) {
            throw new DomainException(
                'Chaque catcheur doit avoir un camp.'
            );
        }

        foreach ($wrestlers as $wrestler) {
            if ($wrestler <= 0) {
                throw new DomainException(
                    'Tous les catcheurs doivent être sélectionnés.'
                );
            }
        }

        foreach ($camps as $camp) {
            if ($camp < 1 || $camp > $campCount) {
                throw new DomainException(
                    'Le camp sélectionné est invalide.'
                );
            }
        }

        $usedCamps = array_unique($camps);

        if (count($usedCamps) !== $campCount) {
            throw new DomainException(
                'Chaque camp doit contenir au moins un catcheur.'
            );
        }

        // Arbitre.
        if ($referee <= 0) {
            throw new DomainException(
                'Un arbitre doit être sélectionné.'
            );
        }

        // Managers.
        if (count($managers) > 2) {
            throw new DomainException(
                'Un match ne peut pas avoir plus de deux managers.'
            );
        }

        if (count($managers) !== count($managerCamps)) {
            throw new DomainException(
                'Chaque manager doit avoir un camp.'
            );
        }

        foreach ($managers as $manager) {
            if ($manager <= 0) {
                throw new DomainException(
                    'Tous les managers doivent être sélectionnés.'
                );
            }
        }

        foreach ($managerCamps as $camp) {
            if ($camp < 1 || $camp > $campCount) {
                throw new DomainException(
                    'Le camp du manager est invalide.'
                );
            }
        }

        // Aucun intervenant en double.
        $participants = array_merge($wrestlers, [$referee], $managers);

        if (
            count($participants) !== count(array_unique($participants))
        ) {
            throw new DomainException(
                "Un intervenant ne peut apparaître qu'une seule fois dans un match."
            );
        }

        // Intervenants actifs uniquement.
        $activeIntervenants = $this->intervenantRepository->findActive();

        $activeIds = array_map(
            'intval',
            array_column(
                $activeIntervenants,
                'id_intervenant'
            )
        );

        foreach ($participants as $participant) {
            if (!in_array($participant, $activeIds, true)) {
                throw new DomainException(
                    'Un intervenant sélectionné est invalide ou inactif.'
                );
            }
        }

        // Modification transactionnelle.
        $this->pdo->beginTransaction();

        try {
            $match = $this->matchRepository->findByIdForUpdate($matchId);

            if ($match === null) {
                throw new DomainException(
                    'Le match est introuvable.'
                );
            }

            $eventId = (int) $match['id_evenement'];

            $event = $this->evenementRepository->findByIdForUpdate($eventId);

            if ($event === null) {
                throw new DomainException(
                    "L'évènement est introuvable."
                );
            }

            if ($event['statut_evenement'] === 'ANNULE') {
                throw new DomainException(
                    "Impossible de modifier un match d'un évènement annulé."
                );
            }

            $eventEnd = new DateTimeImmutable(
                $event['date_heure_fin_evenement']
            );

            if ($eventEnd <= new DateTimeImmutable()) {
                throw new DomainException(
                    "Impossible de modifier un match d'un évènement terminé."
                );
            }

            if (
                $this->matchRepository
                    ->orderExistsForOtherMatch(
                        $eventId,
                        $order,
                        $matchId
                    )
            ) {
                throw new DomainException(
                    "Un match utilise déjà cet ordre dans le programme."
                );
            }

            // Mise à jour du match.
            $this->matchRepository->update(
                $matchId,
                $typeId,
                $name,
                $order
            );

            // Reconstruction des participations.
            $this->matchRepository->deleteParticipations($matchId);

            $participationOrder = 1;

            foreach ($wrestlers as $index => $wrestler) {
                $this->matchRepository->addParticipation(
                    $matchId,
                    $wrestler,
                    'CATCHEUR',
                    $camps[$index],
                    $participationOrder
                );

                $participationOrder++;
            }

            $this->matchRepository->addParticipation(
                $matchId,
                $referee,
                'ARBITRE',
                null,
                $participationOrder
            );

            $participationOrder++;

            foreach ($managers as $index => $manager) {
                $this->matchRepository->addParticipation(
                    $matchId,
                    $manager,
                    'MANAGER',
                    $managerCamps[$index],
                    $participationOrder
                );

                $participationOrder++;
            }

            $this->pdo->commit();
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }

    public function delete(int $matchId): void
    {
        if ($matchId <= 0) {
            throw new DomainException(
                'Le match est invalide.'
            );
        }

        $this->pdo->beginTransaction();

        try {
            $match = $this->matchRepository->findByIdForUpdate($matchId);

            if ($match === null) {
                throw new DomainException(
                    'Le match est introuvable.'
                );
            }

            $eventId = (int) $match['id_evenement'];

            $event = $this->evenementRepository->findByIdForUpdate($eventId);

            if ($event === null) {
                throw new DomainException(
                    "L'évènement est introuvable."
                );
            }

            if ($event['statut_evenement'] === 'ANNULE') {
                throw new DomainException(
                    "Impossible de supprimer un match d'un évènement annulé."
                );
            }

            $eventEnd = new DateTimeImmutable(
                $event['date_heure_fin_evenement']
            );

            if ($eventEnd <= new DateTimeImmutable()) {
                throw new DomainException(
                    "Impossible de supprimer un match d'un évènement terminé."
                );
            }

            $this->matchRepository->deleteParticipations($matchId);

            $this->matchRepository->delete($matchId);

            $this->pdo->commit();
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }

    private function normalizeIntegerList(array $values): array
    {
        $normalized = [];

        foreach ($values as $value) {
            if (is_int($value)) {
                $normalized[] = $value;
                continue;
            }

            if (!is_string($value) || !ctype_digit($value)) {
                throw new DomainException(
                    'Les données des participants sont invalides.'
                );
            }

            $normalized[] = (int) $value;
        }

        return $normalized;
    }
}