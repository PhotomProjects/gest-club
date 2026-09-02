// == Fonctions ==

// Afficher / Masquer un MDP

function togglePassword(passwordInput, toggleButton) {
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleButton.textContent = "Masquer";
        toggleButton.setAttribute("aria-pressed", "true");
    } else {
        passwordInput.type = "password";
        toggleButton.textContent = "Afficher";
        toggleButton.setAttribute("aria-pressed", "false");
    }
}

// Règles MDP

function checkPasswordRules(passwordInput, rulesContainer) {
    // Récupèration des quatre règles dans le bloc concerné.
    const lengthRule = rulesContainer.querySelector('[data-password-rule="length"]');
    const letterCaseRule = rulesContainer.querySelector('[data-password-rule="letter-case"]');
    const numberRule = rulesContainer.querySelector('[data-password-rule="number"]');
    const specialRule = rulesContainer.querySelector('[data-password-rule="special"]');

    passwordInput.addEventListener("input", function() {
        const password = passwordInput.value;

        // Champ vide : retour à l'état neutre.
        if (password === "") {
            lengthRule.classList.remove("is-valid", "is-invalid");
            letterCaseRule.classList.remove("is-valid", "is-invalid");
            numberRule.classList.remove("is-valid", "is-invalid");
            specialRule.classList.remove("is-valid", "is-invalid");
            return;
        }

        // Vérification des quatre règles.
        const hasLength = password.length >= 8;
        const hasNoWhitespace = !/\s/.test(password);
        const hasLetterCase = /[A-Z]/.test(password) && /[a-z]/.test(password);
        const hasNumber = /\d/.test(password);
        const hasSpecialCharacter = /[^A-Za-z0-9\s]/.test(password) && hasNoWhitespace;
        

        // Longueur
        if (hasLength) {
            lengthRule.classList.add("is-valid");
            lengthRule.classList.remove("is-invalid");
        } else {
            lengthRule.classList.add("is-invalid");
            lengthRule.classList.remove("is-valid");
        }

        // Majuscule + minuscule
        if (hasLetterCase) {
            letterCaseRule.classList.add("is-valid");
            letterCaseRule.classList.remove("is-invalid");
        } else {
            letterCaseRule.classList.add("is-invalid");
            letterCaseRule.classList.remove("is-valid");
        }

        // Chiffre
        if (hasNumber) {
            numberRule.classList.add("is-valid");
            numberRule.classList.remove("is-invalid");
        } else {
            numberRule.classList.add("is-invalid");
            numberRule.classList.remove("is-valid");
        }

        // Caractère spécial
        if (hasSpecialCharacter) {
            specialRule.classList.add("is-valid");
            specialRule.classList.remove("is-invalid");
        } else {
            specialRule.classList.add("is-invalid");
            specialRule.classList.remove("is-valid");
        }
    });
}

// Vérifier qu'un champ de confirmation correspond au champ principal

function checkConfirmation(mainInput, confirmationInput, errorElement, mismatchMessage) {
    function compareValues() {
        // Champ de confirmation vide : état neutre.
        if (confirmationInput.value === "") {
            confirmationInput.classList.remove("is-valid", "is-invalid");
            confirmationInput.removeAttribute("aria-invalid");
            errorElement.hidden = true;
            return;
        }

        // Les deux valeurs correspondent.
        if (mainInput.value === confirmationInput.value) {
            confirmationInput.classList.add("is-valid");
            confirmationInput.classList.remove("is-invalid");
            confirmationInput.removeAttribute("aria-invalid");
            errorElement.hidden = true;
        } else {
            // Les deux valeurs sont différentes.
            confirmationInput.classList.add("is-invalid");
            confirmationInput.classList.remove("is-valid");
            confirmationInput.setAttribute("aria-invalid", "true");
            errorElement.textContent = mismatchMessage;
            errorElement.hidden = false;
        }
    }
    // Vérification si le champ principal change.
    mainInput.addEventListener("input", compareValues);

    // Vérification si la confirmation change aussi.
    confirmationInput.addEventListener("input", compareValues);
}

// == Pages ==

// -- Connexion --

const loginPassword = document.getElementById("login-password");
const loginPasswordToggle = document.getElementById("login-password-toggle");

if (loginPassword && loginPasswordToggle) {
    loginPasswordToggle.addEventListener("click", function() {
        togglePassword(loginPassword, loginPasswordToggle);
    });
}

// -- Inscription --

const signupEmail = document.getElementById("signup-email");
const signupEmail2 = document.getElementById("signup-email2");
const signupEmailError = document.getElementById("signup-email-confirmation-error");
const signupPassword = document.getElementById("signup-password");
const signupPassword2 = document.getElementById("signup-password2");
const signupPasswordToggle = document.getElementById("signup-password-toggle");
const signupPassword2Toggle = document.getElementById("signup-password2-toggle");
const signupPasswordError = document.getElementById("signup-password-confirmation-error");
const signupPasswordRules = document.getElementById("password-rules");

// Afficher / masquer le mot de passe d'inscription

if (signupPassword && signupPasswordToggle) {
    signupPasswordToggle.addEventListener("click", function() {
        togglePassword(signupPassword, signupPasswordToggle);
    });
}

// Afficher / masquer la confirmation du mot de passe

if (signupPassword2 && signupPassword2Toggle) {
    signupPassword2Toggle.addEventListener("click", function() {
        togglePassword(signupPassword2, signupPassword2Toggle);
    });
}

// Règles du mot de passe

if (signupPassword && signupPasswordRules) {
    checkPasswordRules(signupPassword, signupPasswordRules);
}

// Confirmation de l'adresse e-mail

if (signupEmail && signupEmail2 && signupEmailError) {
    checkConfirmation(signupEmail, signupEmail2, signupEmailError, "Les adresses e-mail ne correspondent pas.");
}

// Confirmation du mot de passe

if (signupPassword && signupPassword2 && signupPasswordError) {
    checkConfirmation(signupPassword, signupPassword2, signupPasswordError, "Les mots de passe ne correspondent pas.");
}

// -- Sécurité du compte --

const currentPassword = document.getElementById("actuel");
const currentPasswordToggle = document.getElementById("current-password-toggle");
const newPassword = document.getElementById("nouveau");
const newPasswordToggle = document.getElementById("new-password-toggle");
const newPassword2 = document.getElementById("nouveau2");
const newPassword2Toggle = document.getElementById("new-password2-toggle");
const newPasswordError = document.getElementById("new-password-confirmation-error");
const newPasswordRules = document.getElementById("new-password-rules");

// Afficher / masquer le mot de passe actuel

if (currentPassword && currentPasswordToggle) {
    currentPasswordToggle.addEventListener("click", function() {
        togglePassword(currentPassword, currentPasswordToggle);
    });
}
// Afficher / masquer le nouveau mot de passe

if (newPassword && newPasswordToggle) {
    newPasswordToggle.addEventListener("click", function() {
        togglePassword(newPassword, newPasswordToggle);
    });
}

// Afficher / masquer la confirmation

if (newPassword2 && newPassword2Toggle) {
    newPassword2Toggle.addEventListener("click", function() {
        togglePassword(newPassword2, newPassword2Toggle);
    });
}

// Vérifier les règles du nouveau mot de passe

if (newPassword && newPasswordRules) {
    checkPasswordRules(newPassword, newPasswordRules);
}

// Confirmation du nouveau mot de passe

if (newPassword && newPassword2 && newPasswordError) {
    checkConfirmation(newPassword, newPassword2, newPasswordError, "Les mots de passe ne correspondent pas.");
}

// == Réservation ==

const placeInputs = document.querySelectorAll('input[name="nb_places"]');
const levelInputs = document.querySelectorAll('input[name="niveau"]');
const reservationTotal = document.getElementById("reservation-total");

// Mettre à jour le prix total.
function updateReservationTotal() {
    const selectedPlace = document.querySelector('input[name="nb_places"]:checked');
    const selectedLevel = document.querySelector('input[name="niveau"]:checked');
    if (!selectedPlace || !selectedLevel || !reservationTotal) {
        return;
    }
    const numberOfPlaces = Number(selectedPlace.value);
    const price = Number(selectedLevel.dataset.price);
    reservationTotal.textContent = numberOfPlaces * price + " €";
}

if (reservationTotal) {
    placeInputs.forEach(function(input) {
        input.addEventListener("change", updateReservationTotal);
    });
    levelInputs.forEach(function(input) {
        input.addEventListener("change", updateReservationTotal);
    });

    // Afficher le total dès le chargement.
    updateReservationTotal();
}

// == Annulation d'une réservation ==

const reservationCancelForms = document.querySelectorAll(".reservation-cancel-form");
reservationCancelForms.forEach(function(form) {
    form.addEventListener("submit", function(event) {
        const isConfirmed = window.confirm("Voulez-vous vraiment annuler cette réservation ?");

        if (!isConfirmed) {
            event.preventDefault();
        }
    });
});