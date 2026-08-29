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

// == Participants d'un match ==

const participantList = document.getElementById("participant-list");
const participantAdd = document.getElementById("participant-add");

// Mettre à jour les numéros des participants.
function updateParticipantNumbers() {
    const participants = participantList.querySelectorAll(".participant");
    participants.forEach(function(participant, index) {
        const participantNumber = participant.querySelector(".participant__index");
        if (participantNumber) {
            participantNumber.textContent = index + 1;
        }
    });
}

// Retirer un participant.
function removeParticipant(button) {
    const participants = participantList.querySelectorAll(".participant");
    // Au minimum deux participants.
    if (participants.length <= 2) {
        return;
    }
    const participant = button.closest(".participant");
    if (participant) {
        participant.remove();
        updateParticipantNumbers();
    }
}

// Activer les boutons Retirer déjà présents.
if (participantList) {
    const participantRemoveButtons = participantList.querySelectorAll(".participant-remove");
    participantRemoveButtons.forEach(function(button) {
        button.addEventListener("click", function() {
            removeParticipant(button);
        });
    });
}

// Ajouter un participant.
if (participantList && participantAdd) {
    participantAdd.addEventListener("click", function() {
        const firstParticipant = participantList.querySelector(".participant");
        if (!firstParticipant) {
            return;
        }
        // Copier un participant existant.
        const newParticipant = firstParticipant.cloneNode(true);
        // Réinitialiser les listes déroulantes.
        const selects = newParticipant.querySelectorAll("select");
        selects.forEach(function(select) {
            select.selectedIndex = 0;
        });
        // Activer le bouton Retirer de la nouvelle ligne.
        const removeButton = newParticipant.querySelector(".participant-remove");
        if (removeButton) {
            removeButton.addEventListener("click", function() {
                removeParticipant(removeButton);
            });
        }
        // Ajouter la nouvelle ligne.
        participantList.appendChild(newParticipant);
        // Mettre à jour les numéros.
        updateParticipantNumbers();
    });
}

// == Managers d'un match ==

const managerList = document.getElementById("manager-list");
const managerAdd = document.getElementById("manager-add");
const managerTemplate = document.getElementById("manager-template");

// Retirer un manager.
function removeManager(button) {
    const manager = button.closest(".participant--manager");
    if (manager) {
        manager.remove();
    }
}

// Ajouter un manager.
if (managerList && managerAdd && managerTemplate) {
    managerAdd.addEventListener("click", function() {
        // Copier le modèle caché.
        const newManager = managerTemplate.cloneNode(true);
        // Retirer l'id et afficher la copie.
        newManager.removeAttribute("id");
        newManager.hidden = false;
        // Réactiver les listes déroulantes.
        const selects = newManager.querySelectorAll("select");
        selects.forEach(function(select) {
            select.disabled = false;
        });
        // Activer le bouton Retirer.
        const removeButton = newManager.querySelector(".manager-remove");
        if (removeButton) {
            removeButton.addEventListener("click", function() {
                removeManager(removeButton);
            });
        }
        // Ajouter la ligne.
        managerList.appendChild(newManager);
    });
}