<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Core\Database;
use App\Services\ReservationService;
use DomainException;
use PDO;
use PHPUnit\Framework\TestCase;

final class ReservationServiceTest extends TestCase
{
    private PDO $pdo;

    private int $userId;
    private int $eventId;

    protected function setUp(): void
    {
        $config = require dirname(__DIR__, 2) . '/config/app.php';
        $database = new Database($config['database']);

        $this->pdo = $database->getConnection();

        // Utilisateur.
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
            'nom' => 'Reservation',
            'email' => 'reservation@test.local',
            'mdp_hash' => password_hash(
                'Test123!',
                PASSWORD_DEFAULT
            ),
        ]);

        $this->userId = (int) $this->pdo->lastInsertId();

        // Évènement futur.
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
            'nom' => 'Évènement PHPUnit',
            'description' => 'Évènement utilisé pour les tests automatisés.',
            'date_debut' => '2030-06-01 20:00:00',
            'date_fin' => '2030-06-01 23:00:00',
        ]);

        $this->eventId = (int) $this->pdo->lastInsertId();

        // Places physiques.
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

        $placeIds = [];

        foreach (['1', '2'] as $numero) {
            $statement->execute([
                'numero' => $numero,
                'rangee' => 'TEST',
                'niveau' => 'BAS',
                'tribune' => 'NORD',
                'prix' => '25.00',
            ]);

            $placeIds[] = (int) $this->pdo->lastInsertId();
        }

        // Places disponibles pour l'évènement.
        $statement = $this->pdo->prepare(
            'INSERT INTO place_evenement (
                id_evenement,
                id_place
            ) VALUES (
                :id_evenement,
                :id_place
            )'
        );

        foreach ($placeIds as $placeId) {
            $statement->execute([
                'id_evenement' => $this->eventId,
                'id_place' => $placeId,
            ]);
        }
    }

    public function testConfirmationDeDeuxPlaces(): void
    {
        // Arrange
        $service = new ReservationService($this->pdo);

        // Act
        $reservationId = $service->confirm($this->userId, $this->eventId, 2, 'NORD', 'BAS');

        // Assert : la réservation est créée.
        $statement = $this->pdo->prepare(
            'SELECT
            id_utilisateur,
            id_evenement,
            statut_reservation
        FROM reservation
        WHERE id_reservation = :id_reservation'
        );
        $statement->execute(['id_reservation' => $reservationId,]);
        $reservation = $statement->fetch();

        $this->assertIsArray($reservation);
        $this->assertSame($this->userId, (int) $reservation['id_utilisateur']);
        $this->assertSame($this->eventId, (int) $reservation['id_evenement']);
        $this->assertSame('CONFIRMEE', $reservation['statut_reservation']);

        // Assert : deux places sont associées.
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*)
        FROM reservation_place
        WHERE id_reservation = :id_reservation'
        );
        $statement->execute(['id_reservation' => $reservationId,]);
        $nombrePlaces = (int) $statement->fetchColumn();

        $this->assertSame(2, $nombrePlaces);

        // Assert : les deux places sont réservées.
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*)
        FROM place_evenement
        WHERE id_evenement = :id_evenement
        AND statut_place = \'RESERVEE\''
        );
        $statement->execute(['id_evenement' => $this->eventId,]);
        $nombrePlacesReservees = (int) $statement->fetchColumn();

        $this->assertSame(2, $nombrePlacesReservees);

        // Assert : un billet est créé.
        $statement = $this->pdo->prepare(
            'SELECT
            id_billet,
            code_billet
        FROM billet
        WHERE id_reservation = :id_reservation'
        );

        $statement->execute(['id_reservation' => $reservationId,]);
        $billet = $statement->fetch();

        $this->assertIsArray($billet);
        $this->assertMatchesRegularExpression('/^[0-9a-f]{64}$/', $billet['code_billet']);
    }

    public function testUtilisateurNePeutPasDepasserDeuxPlacesSurUnEvenement(): void
    {
        // Arrange
        $service = new ReservationService($this->pdo);

        // Première réservation valide de 2 places.
        $premiereReservationId = $service->confirm($this->userId, $this->eventId, 2, 'NORD', 'BAS');

        // Act : tentative d'une place supplémentaire.
        try {
            $service->confirm($this->userId, $this->eventId, 1, 'NORD', 'BAS');
            $this->fail('La réservation supplémentaire aurait dû être refusée.');
        } catch (DomainException) {
            // Comportement attendu.
        }

        // Assert : une seule réservation existe toujours.
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*)
        FROM reservation
        WHERE id_utilisateur = :id_utilisateur
        AND id_evenement = :id_evenement
        AND statut_reservation = \'CONFIRMEE\''
        );

        $statement->execute(['id_utilisateur' => $this->userId, 'id_evenement' => $this->eventId,]);
        $nombreReservations = (int) $statement->fetchColumn();
        $this->assertSame(1, $nombreReservations);

        // Assert : la première réservation est toujours présente.
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*)
        FROM reservation
        WHERE id_reservation = :id_reservation'
        );

        $statement->execute(['id_reservation' => $premiereReservationId,]);

        $this->assertSame(1, (int) $statement->fetchColumn());

        // Assert : seulement 2 places sont réservées.
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*)
        FROM place_evenement
        WHERE id_evenement = :id_evenement
        AND statut_place = \'RESERVEE\''
        );

        $statement->execute(['id_evenement' => $this->eventId,]);
        $this->assertSame(2, (int) $statement->fetchColumn());
    }

    protected function tearDown(): void
    {
        // Billets des réservations du test.
        $statement = $this->pdo->prepare(
            'DELETE FROM billet
        WHERE id_reservation IN (
            SELECT id_reservation
            FROM reservation
            WHERE id_utilisateur = :id_utilisateur
            AND id_evenement = :id_evenement
        )'
        );

        $statement->execute(['id_utilisateur' => $this->userId, 'id_evenement' => $this->eventId,]);

        // Places associées aux réservations.
        $statement = $this->pdo->prepare(
            'DELETE FROM reservation_place
        WHERE id_reservation IN (
            SELECT id_reservation
            FROM reservation
            WHERE id_utilisateur = :id_utilisateur
            AND id_evenement = :id_evenement
        )'
        );

        $statement->execute(['id_utilisateur' => $this->userId, 'id_evenement' => $this->eventId,]);

        // Réservations.
        $statement = $this->pdo->prepare(
            'DELETE FROM reservation
        WHERE id_utilisateur = :id_utilisateur
        AND id_evenement = :id_evenement'
        );

        $statement->execute(['id_utilisateur' => $this->userId, 'id_evenement' => $this->eventId,]);

        // Associations événement / places.
        $statement = $this->pdo->prepare(
            'DELETE FROM place_evenement
        WHERE id_evenement = :id_evenement'
        );

        $statement->execute(['id_evenement' => $this->eventId,]);

        // Évènement.
        $statement = $this->pdo->prepare(
            'DELETE FROM evenement
        WHERE id_evenement = :id_evenement'
        );

        $statement->execute(['id_evenement' => $this->eventId,]);

        // Places physiques créées par le test.
        $statement = $this->pdo->prepare(
            'DELETE FROM place
        WHERE tribune_place = \'NORD\'
        AND niveau_place = \'BAS\'
        AND rangee_place = \'TEST\''
        );

        $statement->execute();

        // Utilisateur.
        $statement = $this->pdo->prepare(
            'DELETE FROM utilisateur
        WHERE id_utilisateur = :id_utilisateur'
        );

        $statement->execute(['id_utilisateur' => $this->userId,]);
    }

    public function testReservationRefuseeSiUneSeulePlaceEstDisponible(): void
    {
        // Arrange : une des deux places est déjà réservée.
        $statement = $this->pdo->prepare(
            'SELECT id_place_evenement
        FROM place_evenement
        WHERE id_evenement = :id_evenement
        ORDER BY id_place_evenement
        LIMIT 1'
        );

        $statement->execute(['id_evenement' => $this->eventId,]);
        $placeEvenementId = (int) $statement->fetchColumn();
        $statement = $this->pdo->prepare(
            'UPDATE place_evenement
        SET statut_place = \'RESERVEE\'
        WHERE id_place_evenement = :id_place_evenement'
        );
        $statement->execute(['id_place_evenement' => $placeEvenementId,]);
        $service = new ReservationService($this->pdo);

        // Act : l'utilisateur demande 2 places, alors qu'une seule est disponible.
        try {
            $service->confirm($this->userId, $this->eventId, 2, 'NORD', 'BAS');
            $this->fail('La réservation aurait dû être refusée.');
        } catch (DomainException) {
            // Comportement attendu.
        }

        // Assert : aucune réservation n'a été créée.
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*)
        FROM reservation
        WHERE id_utilisateur = :id_utilisateur
        AND id_evenement = :id_evenement'
        );
        $statement->execute(['id_utilisateur' => $this->userId, 'id_evenement' => $this->eventId,]);

        $this->assertSame(0, (int) $statement->fetchColumn());

        // Assert : aucun billet n'a été créé.
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*)
        FROM billet b
        INNER JOIN reservation r
            ON r.id_reservation = b.id_reservation
        WHERE r.id_utilisateur = :id_utilisateur
        AND r.id_evenement = :id_evenement'
        );
        $statement->execute(['id_utilisateur' => $this->userId, 'id_evenement' => $this->eventId,]);

        $this->assertSame(0, (int) $statement->fetchColumn());

        // Assert : une seule place reste réservée.
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*)
        FROM place_evenement
        WHERE id_evenement = :id_evenement
        AND statut_place = \'RESERVEE\''
        );
        $statement->execute(['id_evenement' => $this->eventId,]);

        $this->assertSame(1, (int) $statement->fetchColumn());

        // Assert : l'autre place reste disponible.
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*)
        FROM place_evenement
        WHERE id_evenement = :id_evenement
        AND statut_place = \'DISPONIBLE\''
        );
        $statement->execute(['id_evenement' => $this->eventId,]);

        $this->assertSame(1, (int) $statement->fetchColumn());
    }
}
