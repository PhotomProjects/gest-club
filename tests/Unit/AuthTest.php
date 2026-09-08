<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\Auth;
use PHPUnit\Framework\TestCase;

final class AuthTest extends TestCase
{
    public function testUtilisateurNonConnecte(): void
    {
        // Arrange
        $auth = new Auth(null);

        // Act
        $resultat = $auth->isAuthenticated();

        // Assert
        $this->assertFalse($resultat);
    }

    public function testUtilisateurConnecte(): void
    {
        // Arrange
        $utilisateur = [
            'id' => 1,
            'role' => 'MEMBRE',
        ];

        $auth = new Auth($utilisateur);

        // Act
        $resultat = $auth->isAuthenticated();

        // Assert
        $this->assertTrue($resultat);
    }

    public function testUtilisateurSansRoleNonAuthentifie(): void
    {
        // Arrange
        $utilisateur = [
            'id' => 1,
        ];

        $auth = new Auth($utilisateur);

        // Act
        $resultat = $auth->isAuthenticated();

        // Assert
        $this->assertFalse($resultat);
    }
}