<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class UtilisateurRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT
                id_utilisateur,
                prenom,
                nom,
                email,
                mdp_hash,
                role_utilisateur
            FROM utilisateur
            WHERE email = :email'
        );

        $statement->execute([
            'email' => $email,
        ]);

        $utilisateur = $statement->fetch();

        return $utilisateur ?: null;
    }

    public function create(
        string $prenom,
        string $nom,
        string $email,
        string $passwordHash
    ): int {
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
            'prenom' => $prenom,
            'nom' => $nom,
            'email' => $email,
            'mdp_hash' => $passwordHash,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function updateInformations(
        int $idUtilisateur,
        string $prenom,
        string $nom,
        string $email
    ): void {
        $statement = $this->pdo->prepare(
            'UPDATE utilisateur
        SET
            prenom = :prenom,
            nom = :nom,
            email = :email
        WHERE id_utilisateur = :id_utilisateur'
        );

        $statement->execute([
            'prenom' => $prenom,
            'nom' => $nom,
            'email' => $email,
            'id_utilisateur' => $idUtilisateur,
        ]);
    }

    public function findById(int $idUtilisateur): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT
            id_utilisateur,
            prenom,
            nom,
            email,
            mdp_hash,
            role_utilisateur
        FROM utilisateur
        WHERE id_utilisateur = :id_utilisateur'
        );

        $statement->execute([
            'id_utilisateur' => $idUtilisateur,
        ]);

        $utilisateur = $statement->fetch();

        return $utilisateur ?: null;
    }

    public function updatePassword(
        int $idUtilisateur,
        string $passwordHash
    ): void {
        $statement = $this->pdo->prepare(
            'UPDATE utilisateur
        SET mdp_hash = :mdp_hash
        WHERE id_utilisateur = :id_utilisateur'
        );

        $statement->execute([
            'mdp_hash' => $passwordHash,
            'id_utilisateur' => $idUtilisateur,
        ]);
    }
}