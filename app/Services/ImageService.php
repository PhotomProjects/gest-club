<?php

declare(strict_types=1);

namespace App\Services;

use DomainException;

class ImageService
{
    private const MAX_SIZE = 5 * 1024 * 1024;

    private const ALLOWED_TYPES = ['image/jpeg' => 'jpg', 'image/png' => 'png',];

    public function uploadEventImage(array $file): ?string
    {
        // Aucun fichier envoyé : l'image est facultative.
        if (
            !isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE
        ) {
            return null;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new DomainException(
                "Une erreur est survenue lors de l'envoi de l'image."
            );
        }

        if (
            !isset($file['size']) || $file['size'] > self::MAX_SIZE
        ) {
            throw new DomainException(
                "L'image ne doit pas dépasser 5 Mo."
            );
        }

        if (
            !isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])
        ) {
            throw new DomainException(
                "Le fichier envoyé est invalide."
            );
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (
            $mimeType === false || !isset(self::ALLOWED_TYPES[$mimeType])
        ) {
            throw new DomainException(
                "L'image doit être au format JPG ou PNG."
            );
        }

        $extension = self::ALLOWED_TYPES[$mimeType];

        $fileName = bin2hex(random_bytes(16)) . '.' . $extension;

        $directory = dirname(__DIR__, 2) . '/public/assets/images/evenements';

        if (
            !is_dir($directory)
            && !mkdir($directory, 0755, true)
            && !is_dir($directory)
        ) {
            throw new DomainException(
                "Impossible de créer le dossier des images."
            );
        }

        $destination = $directory . '/' . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new DomainException(
                "Impossible d'enregistrer l'image."
            );
        }

        return 'evenements/' . $fileName;
    }

    public function deleteEventImage(?string $image): void
    {
        if (
            $image === null || !str_starts_with($image, 'evenements/')
        ) {
            return;
        }

        $directory = dirname(__DIR__, 2) . '/public/assets/images/evenements';

        $filePath = $directory . '/' . basename($image);

        if (is_file($filePath)) {
            unlink($filePath);
        }
    }
}