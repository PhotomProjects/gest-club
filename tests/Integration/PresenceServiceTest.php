<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Core\Database;
use App\Services\PresenceService;
use App\Services\ReservationService;
use DateTimeImmutable;
use DomainException;
use PDO;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class PresenceServiceTest extends TestCase
{
    private PDO $pdo;

    private int $userId;
    private int $eventId;
    private int $placeId;
    private int $reservationId;
    private int $ticketId;

    protected function setUp(): void
    {
        $config = require dirname(__DIR__, 2) . '/config/app.php';
        $database = new Database($config['database']);

        $this->pdo = $database->getConnection();

        // Utilisateur propre au test.
        $statement = $this->pdo->prepare(
            'INSERT INTO utilisateur (
                prenom,
                nom,
                email,
                mdp_hash
            ) VALUES (
                :prenom,
                :nom,
                :email,
                :mdp_hash
            )'
        );

        $statement->execute([
            'prenom' => 'Test',
            'nom' => 'Presence',
            'email' => 'presence-' . bin2hex(random_bytes(4)) . '@example.test',
            'mdp_hash' => password_hash('Test123!', PASSWORD_DEFAULT),
        ]);

        $this->userId = (int) $this->pdo->lastInsertId();

        // Évènement futur.
        $dateDebut = new DateTimeImmutable('+30 days');
        $dateFin = $dateDebut->modify('+3 hours');

        $statement = $this->pdo->prepare(
            'INSERT INTO evenement (
                nom_evenement,
                description_evenement,
                date_heure_debut_evenement,
                date_heure_fin_evenement
            ) VALUES (
                :nom,
                :description,
                :date_debut,
                :date_fin
            )'
        );

        $statement->execute([
            'nom' => 'Évènement test présence',
            'description' => 'Évènement utilisé pour tester le contrôle des billets.',
            'date_debut' => $dateDebut->format('Y-m-d H:i:s'),
            'date_fin' => $dateFin->format('Y-m-d H:i:s'),
        ]);

        $this->eventId = (int) $this->pdo->lastInsertId();

        // Place physique unique.
        $statement = $this->pdo->prepare(
            'INSERT INTO place (
                numero_place,
                rangee_place,
                niveau_place,
                tribune_place,
                prix_place
            ) VALUES (
                :numero,
                :rangee,
                :niveau,
                :tribune,
                :prix
            )'
        );

        $statement->execute([
            'numero' => '1',
            'rangee' => 'PT' . bin2hex(random_bytes(3)),
            'niveau' => 'BAS',
            'tribune' => 'NORD',
            'prix' => '45.00',
        ]);

        $this->placeId = (int) $this->pdo->lastInsertId();

        // Disponibilité de la place pour l’évènement.
        $statement = $this->pdo->prepare(
            'INSERT INTO place_evenement (
                id_evenement,
                id_place
            ) VALUES (
                :id_evenement,
                :id_place
            )'
        );

        $statement->execute(['id_evenement' => $this->eventId, 'id_place' => $this->placeId,]);

        // Création d’une réservation et de son billet.
        $reservationService = new ReservationService($this->pdo);
        $this->reservationId = $reservationService->confirm($this->userId, $this->eventId, 1, 'NORD', 'BAS');

        $statement = $this->pdo->prepare(
            'SELECT id_billet
            FROM billet
            WHERE id_reservation = :id_reservation'
        );

        $statement->execute(['id_reservation' => $this->reservationId,]);

        $this->ticketId = (int) $statement->fetchColumn();

        if ($this->ticketId < 1) {
            throw new RuntimeException(
                "Le billet nécessaire au test n'a pas été créé."
            );
        }
    }

    public function testValidationCreeUnePresence(): void
    {
        // Arrange
        $service = new PresenceService($this->pdo);

        // Act
        $presenceId = $service->validateEntry($this->ticketId);

        // Assert
        $this->assertGreaterThan(0, $presenceId);

        $statement = $this->pdo->prepare(
            'SELECT COUNT(*)
        FROM presence
        WHERE id_billet = :id_billet'
        );

        $statement->execute(['id_billet' => $this->ticketId,]);

        $this->assertSame(1, (int) $statement->fetchColumn());
    }

    public function testDeuxiemeControleRefuse(): void
    {
        // Arrange
        $service = new PresenceService($this->pdo);

        // Le premier contrôle place le billet dans l’état attendu pour le scénario testé.
        $service->validateEntry($this->ticketId);

        // Act
        $exception = null;

        try {
            $service->validateEntry($this->ticketId);
        } catch (DomainException $caughtException) {
            $exception = $caughtException;
        }

        // Assert
        $this->assertInstanceOf(DomainException::class, $exception);
        $this->assertSame('Ce billet a déjà été utilisé.', $exception->getMessage());
    }

    public function testReservationAnnuleeRefusee(): void
    {
        // Arrange
        $reservationService = new ReservationService($this->pdo);

        $reservationService->cancel($this->userId, $this->reservationId);

        $presenceService = new PresenceService($this->pdo);

        // Act
        $exception = null;

        try {
            $presenceService->validateEntry($this->ticketId);
        } catch (DomainException $caughtException) {
            $exception = $caughtException;
        }

        // Assert
        $this->assertInstanceOf(DomainException::class, $exception);
        $this->assertSame('Cette réservation est annulée.', $exception->getMessage());
    }

    protected function tearDown(): void
    {
        $statement = $this->pdo->prepare(
            'DELETE FROM presence
            WHERE id_billet = :id_billet'
        );
        $statement->execute(['id_billet' => $this->ticketId]);

        $statement = $this->pdo->prepare(
            'DELETE FROM billet
            WHERE id_billet = :id_billet'
        );
        $statement->execute(['id_billet' => $this->ticketId]);

        $statement = $this->pdo->prepare(
            'DELETE FROM reservation_place
            WHERE id_reservation = :id_reservation'
        );
        $statement->execute(['id_reservation' => $this->reservationId]);

        $statement = $this->pdo->prepare(
            'DELETE FROM reservation
            WHERE id_reservation = :id_reservation'
        );
        $statement->execute(['id_reservation' => $this->reservationId]);

        $statement = $this->pdo->prepare(
            'DELETE FROM place_evenement
            WHERE id_evenement = :id_evenement'
        );
        $statement->execute(['id_evenement' => $this->eventId]);

        $statement = $this->pdo->prepare(
            'DELETE FROM evenement
            WHERE id_evenement = :id_evenement'
        );
        $statement->execute(['id_evenement' => $this->eventId]);

        $statement = $this->pdo->prepare(
            'DELETE FROM place
            WHERE id_place = :id_place'
        );
        $statement->execute(['id_place' => $this->placeId]);

        $statement = $this->pdo->prepare(
            'DELETE FROM utilisateur
            WHERE id_utilisateur = :id_utilisateur'
        );
        $statement->execute(['id_utilisateur' => $this->userId]);
    }
}