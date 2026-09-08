<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Core\Database;
use App\Services\EvenementService;
use App\Services\ReservationService;
use DateTimeImmutable;
use DomainException;
use PDO;
use PHPUnit\Framework\TestCase;

final class EvenementServiceTest extends TestCase
{
    private PDO $pdo;

    private array $placeIds = [];
    private ?int $eventId = null;
    private ?int $userId = null;
    private ?int $reservationId = null;

    protected function setUp(): void
    {
        $config = require dirname(__DIR__, 2) . '/config/app.php';
        $database = new Database($config['database']);

        $this->pdo = $database->getConnection();

        // Deux places physiques propres au test.
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

        $rangee = 'ET' . bin2hex(random_bytes(3));

        foreach (['1', '2'] as $numero) {
            $statement->execute([
                'numero' => $numero,
                'rangee' => $rangee,
                'niveau' => 'BAS',
                'tribune' => 'NORD',
                'prix' => '45.00',
            ]);

            $this->placeIds[] = (int) $this->pdo->lastInsertId();
        }
    }

    public function testCreationGenereLesPlacesDeLEvenement(): void
    {
        // Arrange
        $service = new EvenementService($this->pdo);

        $dateDebut = new DateTimeImmutable('+30 days');
        $dateFin = $dateDebut->modify('+3 hours');

        $nombrePlacesPhysiques = (int) $this->pdo
            ->query('SELECT COUNT(*) FROM place')
            ->fetchColumn();

        // Act
        $this->eventId = $service->create(
            'Évènement PHPUnit',
            'Évènement utilisé pour tester la génération des places.',
            null,
            $dateDebut->format('Y-m-d H:i'),
            $dateFin->format('Y-m-d H:i')
        );

        // Assert
        $this->assertGreaterThan(0, $this->eventId);

        $statement = $this->pdo->prepare(
            'SELECT COUNT(*)
            FROM evenement
            WHERE id_evenement = :id_evenement'
        );

        $statement->execute(['id_evenement' => $this->eventId,]);

        $this->assertSame(1, (int) $statement->fetchColumn());

        $statement = $this->pdo->prepare(
            'SELECT COUNT(*)
            FROM place_evenement
            WHERE id_evenement = :id_evenement'
        );

        $statement->execute(['id_evenement' => $this->eventId,]);

        $this->assertSame($nombrePlacesPhysiques, (int) $statement->fetchColumn());
    }

    public function testCreationEvenementPasseRefusee(): void
    {
        // Arrange
        $service = new EvenementService($this->pdo);

        $nom = 'Évènement passé PHPUnit ' . bin2hex(random_bytes(3));

        $dateDebut = new DateTimeImmutable('-2 days');
        $dateFin = $dateDebut->modify('+3 hours');

        // Act
        $exception = null;

        try {
            $service->create(
                $nom,
                'Cet évènement ne doit pas être créé.',
                null,
                $dateDebut->format('Y-m-d H:i'),
                $dateFin->format('Y-m-d H:i')
            );
        } catch (DomainException $caughtException) {
            $exception = $caughtException;
        }

        // Assert
        $this->assertInstanceOf(DomainException::class, $exception);

        $this->assertSame(
            'La date de début doit être postérieure à la date actuelle.',
            $exception->getMessage()
        );

        $statement = $this->pdo->prepare(
            'SELECT COUNT(*)
            FROM evenement
            WHERE nom_evenement = :nom'
        );

        $statement->execute(['nom' => $nom,]);

        $this->assertSame(0, (int) $statement->fetchColumn());
    }

    public function testAnnulationEvenementAnnuleReservationsEtLiberePlaces(): void
    {
        // Arrange
        $evenementService = new EvenementService($this->pdo);

        $dateDebut = new DateTimeImmutable('+30 days');
        $dateFin = $dateDebut->modify('+3 hours');

        $this->eventId = $evenementService->create(
            'Évènement à annuler PHPUnit',
            'Évènement utilisé pour tester une annulation complète.',
            null,
            $dateDebut->format('Y-m-d H:i'),
            $dateFin->format('Y-m-d H:i')
        );

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
            'nom' => 'Annulation',
            'email' => 'annulation-' . bin2hex(random_bytes(4)) . '@example.test',
            'mdp_hash' => password_hash(
                'Test123!',
                PASSWORD_DEFAULT
            ),
        ]);

        $this->userId = (int) $this->pdo->lastInsertId();

        $reservationService = new ReservationService($this->pdo);

        $this->reservationId = $reservationService->confirm(
            $this->userId,
            $this->eventId,
            1,
            'NORD',
            'BAS'
        );

        // Act
        $evenementService->cancel($this->eventId);

        // Assert : l’évènement est annulé.
        $statement = $this->pdo->prepare(
            'SELECT statut_evenement
            FROM evenement
            WHERE id_evenement = :id_evenement'
        );

        $statement->execute(['id_evenement' => $this->eventId,]);

        $this->assertSame('ANNULE', $statement->fetchColumn());

        // Assert : la réservation est annulée.
        $statement = $this->pdo->prepare(
            'SELECT statut_reservation
            FROM reservation
            WHERE id_reservation = :id_reservation'
        );

        $statement->execute(['id_reservation' => $this->reservationId,]);

        $this->assertSame('ANNULEE', $statement->fetchColumn());

        // Assert : aucune place ne reste réservée.
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*)
            FROM place_evenement
            WHERE id_evenement = :id_evenement
            AND statut_place = \'RESERVEE\''
        );

        $statement->execute(['id_evenement' => $this->eventId,]);

        $this->assertSame(0, (int) $statement->fetchColumn());

        // Assert : toutes les places sont disponibles.
        $statement = $this->pdo->prepare(
            'SELECT
                COUNT(*) AS total,
                SUM(
                    CASE
                        WHEN statut_place = \'DISPONIBLE\' THEN 1
                        ELSE 0
                    END
                ) AS disponibles
            FROM place_evenement
            WHERE id_evenement = :id_evenement'
        );

        $statement->execute(['id_evenement' => $this->eventId,]);

        $places = $statement->fetch();

        $this->assertSame(
            (int) $places['total'],
            (int) $places['disponibles']
        );
    }

    protected function tearDown(): void
    {
        if ($this->reservationId !== null) {
            $statement = $this->pdo->prepare(
                'DELETE FROM presence
                WHERE id_billet IN (
                    SELECT id_billet
                    FROM billet
                    WHERE id_reservation = :id_reservation
                )'
            );

            $statement->execute([
                'id_reservation' => $this->reservationId,
            ]);

            $statement = $this->pdo->prepare(
                'DELETE FROM billet
                WHERE id_reservation = :id_reservation'
            );

            $statement->execute([
                'id_reservation' => $this->reservationId,
            ]);

            $statement = $this->pdo->prepare(
                'DELETE FROM reservation_place
                WHERE id_reservation = :id_reservation'
            );

            $statement->execute([
                'id_reservation' => $this->reservationId,
            ]);

            $statement = $this->pdo->prepare(
                'DELETE FROM reservation
                WHERE id_reservation = :id_reservation'
            );

            $statement->execute([
                'id_reservation' => $this->reservationId,
            ]);
        }

        if ($this->eventId !== null) {
            $statement = $this->pdo->prepare(
                'DELETE FROM place_evenement
                WHERE id_evenement = :id_evenement'
            );

            $statement->execute([
                'id_evenement' => $this->eventId,
            ]);

            $statement = $this->pdo->prepare(
                'DELETE FROM evenement
                WHERE id_evenement = :id_evenement'
            );

            $statement->execute([
                'id_evenement' => $this->eventId,
            ]);
        }

        if ($this->userId !== null) {
            $statement = $this->pdo->prepare(
                'DELETE FROM utilisateur
                WHERE id_utilisateur = :id_utilisateur'
            );

            $statement->execute([
                'id_utilisateur' => $this->userId,
            ]);
        }

        $statement = $this->pdo->prepare(
            'DELETE FROM place
            WHERE id_place = :id_place'
        );

        foreach ($this->placeIds as $placeId) {
            $statement->execute([
                'id_place' => $placeId,
            ]);
        }
    }
}