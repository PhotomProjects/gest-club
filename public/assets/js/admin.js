// == Image d'un évènement ==

const eventImage = document.getElementById("ev-image");
const eventImageError = document.getElementById("ev-image-error");
const eventImagePreview = document.getElementById("ev-image-preview");

// Remettre l'aperçu dans son état par défaut.
function resetImagePreview() {
    if (!eventImagePreview) {
        return;
    }

    if (eventImagePreview.dataset.imageMode === "create") {
        eventImagePreview.innerHTML = "";
        eventImagePreview.hidden = true;
    }

    if (eventImagePreview.dataset.imageMode === "edit") {
        eventImagePreview.innerHTML = "Image actuelle";
        eventImagePreview.hidden = false;
    }
}

if (eventImage && eventImageError) {
    eventImage.addEventListener("change", function() {
        // Récupère le premier fichier sélectionné.
        const file = eventImage.files[0];

        // Aucun fichier sélectionné.
        if (!file) {
            eventImage.classList.remove("is-invalid");
            eventImageError.hidden = true;
            eventImage.setCustomValidity("");
            resetImagePreview();
            return;
        }

        // Types autorisés.
        const isValidType = file.type === "image/jpeg" || file.type === "image/png";

        // Taille maximale : 5 Mo.
        const maxSize = 5 * 1024 * 1024;
        const isValidSize = file.size <= maxSize;

        // Type incorrect.
        if (!isValidType) {
            const errorMessage = "Le fichier doit être une image JPG ou PNG.";
            eventImage.classList.add("is-invalid");
            eventImageError.textContent = errorMessage;
            eventImageError.hidden = false;
            eventImage.setCustomValidity(errorMessage);
            resetImagePreview();
            return;
        }

        // Fichier trop volumineux.
        if (!isValidSize) {
            const errorMessage = "L'image ne doit pas dépasser 5 Mo.";
            eventImage.classList.add("is-invalid");
            eventImageError.textContent = errorMessage;
            eventImageError.hidden = false;
            eventImage.setCustomValidity(errorMessage);
            resetImagePreview();
            return;
        }

        // Le fichier est valide.
        eventImage.classList.remove("is-invalid");
        eventImageError.hidden = true;
        eventImage.setCustomValidity("");

        // Afficher l'aperçu.
        if (eventImagePreview) {
            const image = document.createElement("img");
            image.src = URL.createObjectURL(file);
            image.alt = "Aperçu de l'image sélectionnée";
            eventImagePreview.innerHTML = "";
            eventImagePreview.appendChild(image);
            eventImagePreview.hidden = false;
        }
    });
}