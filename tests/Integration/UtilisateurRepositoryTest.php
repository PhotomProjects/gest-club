<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Core\Database;
use App\Repositories\UtilisateurRepository;
use PDO;
use PHPUnit\Framework\TestCase;

final class UtilisateurRepositoryTest extends TestCase
{
    private PDO $pdo;
    private string $email;

    protected function setUp(): void
    {
        // Adresse unique pour chaque exécution du test.
        $this->email = 'phpunit-' . bin2hex(random_bytes(4)) . '@example.test';

        // Chargement de la configuration.
        $config = require dirname(__DIR__, 2) . '/config/app.php';

        // Connexion à la BDD de test.
        $database = new Database($config['database']);

        $this->pdo = $database->getConnection();
    }

    protected function tearDown(): void
    {
        // Nettoyage des données créées par le test.
        $statement = $this->pdo->prepare(
            'DELETE FROM utilisateur
            WHERE email = :email'
        );

        $statement->execute([
            'email' => $this->email,
        ]);
    }

    public function testCreationEtRechercheUtilisateur(): void
    {
        // Arrange
        $repository = new UtilisateurRepository($this->pdo);
        $passwordHash = password_hash('Test123!', PASSWORD_DEFAULT);

        // Act
        $idUtilisateur = $repository->create('Test', 'PHPUnit', $this->email, $passwordHash);
        $utilisateur = $repository->findByEmail($this->email);

        // Assert
        $this->assertNotNull($utilisateur);
        $this->assertSame($idUtilisateur, (int) $utilisateur['id_utilisateur']);
        $this->assertSame('Test', $utilisateur['prenom']);
        $this->assertSame('PHPUnit', $utilisateur['nom']);
        $this->assertSame('MEMBRE', $utilisateur['role_utilisateur']);
    }
}