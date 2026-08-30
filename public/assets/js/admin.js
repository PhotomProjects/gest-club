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
const matchType = document.getElementById("m-type");

// Récupérer le nombre de catcheurs attendu pour le type de match sélectionné.
function getExpectedParticipantCount() {
    if (!matchType) {
        return 0;
    }
    const selectedOption = matchType.options[matchType.selectedIndex];
    return Number(selectedOption.dataset.participants) || 0;
}

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

// Mettre à jour les boutons et la validation.
function updateParticipantState() {
    if (!participantList || !participantAdd || !matchType) {
        return;
    }
    const participants = participantList.querySelectorAll(".participant");
    const expectedCount = getExpectedParticipantCount();
    // Il faut d'abord sélectionner un type de match.
    // Le bouton est aussi désactivé lorsque le maximum est atteint.
    participantAdd.disabled = expectedCount === 0 || participants.length >= expectedCount;
    // Toujours conserver au minimum deux catcheurs.
    participants.forEach(function(participant) {
        const removeButton = participant.querySelector(".participant-remove");
        if (removeButton) {
            removeButton.disabled = participants.length <= 2;
        }
    });
    // Le champ type de match porte le message d'erreur.
    if (expectedCount > 0 && participants.length !== expectedCount) {
        matchType.setCustomValidity("Ce type de match nécessite exactement " + expectedCount + " catcheurs.");
    } else {
        matchType.setCustomValidity("");
    }
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
        updateParticipantState();
    }
}

// Ajouter un participant.
if (participantList && participantAdd) {
    participantAdd.addEventListener("click", function() {
        const participants = participantList.querySelectorAll(".participant");
        const expectedCount = getExpectedParticipantCount();
        // Aucun type sélectionné ou nombre maximum atteint.
        if (expectedCount === 0 || participants.length >= expectedCount) {
            return;
        }
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
        updateParticipantNumbers();
        updateParticipantState();
    });
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

// Réagir au changement de type de match.
if (matchType) {
    matchType.addEventListener("change", function() {
        updateParticipantState();
    });
}

// Initialiser l'état du formulaire.
updateParticipantState();

// == Managers d'un match ==

const managerList = document.getElementById("manager-list");
const managerAdd = document.getElementById("manager-add");
const managerTemplate = document.getElementById("manager-template");
const maxManagers = 2;

// Mettre à jour le bouton d'ajout.
// Le bouton est désactivé lorsque deux managers sont déjà présents.
function updateManagerButton() {
    if (!managerList || !managerAdd) {
        return;
    }
    const managers = managerList.querySelectorAll(".participant--manager");
    managerAdd.disabled = managers.length >= maxManagers;
}

// Retirer un manager.
function removeManager(button) {
    const manager = button.closest(".participant--manager");
    if (manager) {
        manager.remove();
        updateManagerButton();
    }
}

// Ajouter un manager.
if (managerList && managerAdd && managerTemplate) {
    managerAdd.addEventListener("click", function() {
        const managers = managerList.querySelectorAll(".participant--manager");
        // Ne jamais dépasser deux managers.
        if (managers.length >= maxManagers) {
            return;
        }
        // Copier le modèle caché.
        const newManager = managerTemplate.cloneNode(true);
        // Retirer l'id et afficher la copie.
        newManager.removeAttribute("id");
        newManager.hidden = false;
        // Réactiver les listes déroulantes.
        const selects = newManager.querySelectorAll("select");
        selects.forEach(function(select) {
            select.disabled = false;
            select.selectedIndex = 0;
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
        updateManagerButton();
    });
    updateManagerButton();
}

// == Scan QR Code ==

const cameraButton = document.getElementById("camera-button");
const cameraVideo = document.getElementById("camera-video");
const cameraPlaceholder = document.getElementById("camera-placeholder");
const cameraError = document.getElementById("camera-error");
const ticketCode = document.getElementById("code");

// Arrêter la caméra.
function stopCamera() {
    const stream = cameraVideo.srcObject;
    if (stream) {
        stream.getTracks().forEach(function(track) {
            track.stop();
        });
        cameraVideo.srcObject = null;
    }
}

if (
    cameraButton &&
    cameraVideo &&
    cameraPlaceholder &&
    cameraError &&
    ticketCode
) {
    cameraButton.addEventListener("click", async function() {
        // Vérifier que le navigateur peut lire les QR Codes.
        if (!("BarcodeDetector" in window)) {
            cameraError.textContent = "La lecture des QR Codes n'est pas disponible sur ce navigateur.";
            cameraError.hidden = false;
            return;
        }
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: true,
                caudio: false
            });
            cameraVideo.srcObject = stream;
            cameraVideo.hidden = false;
            cameraPlaceholder.hidden = true;
            cameraError.hidden = true;
            cameraButton.textContent = "Caméra activée";
            cameraButton.disabled = true;
            const detector = new BarcodeDetector({
                formats: ["qr_code"]
            });
            scanQRCode(detector);
        } catch (error) {
            cameraError.textContent = "Impossible d'accéder à la caméra.";
            cameraError.hidden = false;
        }
    });
}

// Lire un QR Code.
async function scanQRCode(detector) {
    try {
        const codes = await detector.detect(cameraVideo);
        if (codes.length > 0) {
            ticketCode.value = codes[0].rawValue;
            stopCamera();
            cameraVideo.hidden = true;
            cameraPlaceholder.hidden = false;
            cameraPlaceholder.textContent = "QR Code détecté";
            cameraButton.textContent = "Activer la caméra";
            cameraButton.disabled = false;
            return;
        }
        // Recommencer la lecture après un court délai.
        setTimeout(function() {
            scanQRCode(detector);
        }, 500);
    } catch (error) {
        cameraError.textContent = "Impossible de lire le QR Code.";
        cameraError.hidden = false;
        stopCamera();
    }
}

// == Programme des matchs ==

const programList = document.getElementById("program-list");

// Mettre à jour les numéros des matchs.
function updateProgramNumbers() {
    const matches = programList.querySelectorAll(".program__item");
    matches.forEach(function(match, index) {
        const matchNumber = match.querySelector(".program__index");
        if (matchNumber) {
            matchNumber.textContent = index + 1;
        }
    });
}

// Retirer un match du programme.
function removeProgramMatch(button) {
    const match = button.closest(".program__item");
    if (match) {
        match.remove();
        updateProgramNumbers();
    }
}

// Activer les boutons Retirer.
if (programList) {
    const removeButtons = programList.querySelectorAll(".program-remove");
    removeButtons.forEach(function(button) {
        button.addEventListener("click", function() {
            removeProgramMatch(button);
        });
    });
}