    // Menu mobile

    const navToggle = document.getElementById("nav-toggle");
    const mobileMenu = document.getElementById("mobile-menu");

    if (navToggle && mobileMenu) {
        const backgroundElements = document.querySelectorAll(".skip-link, .brand, .main-nav, .header-actions, .banner, main, .site-footer");
        const firstMenuControl = mobileMenu.querySelector("a, button");

        function updateMobileMenu(isOpen, moveFocus = true) {
            backgroundElements.forEach(function(element) {
                element.inert = isOpen;
            });

            document.body.classList.toggle("menu-open", isOpen);

            if (!moveFocus) {
                return;
            }

            if (isOpen) {
                firstMenuControl?.focus();
            } else {
                navToggle.focus();
            }
        }

        navToggle.addEventListener("change", function() {
            updateMobileMenu(navToggle.checked);
        });

        document.addEventListener("keydown", function(event) {
            if (event.key === "Escape" && navToggle.checked) {
                navToggle.checked = false;
                updateMobileMenu(false);
            }
        });

        const mobileMedia = window.matchMedia("(max-width: 860px)");

        mobileMedia.addEventListener("change", function(event) {
            if (!event.matches && navToggle.checked) {
                navToggle.checked = false;
                updateMobileMenu(false, false);
            }
        });

        navToggle.addEventListener("keydown", function(event) {
        if (event.key !== "Enter") {
            return;
        }

            event.preventDefault();
            navToggle.checked = !navToggle.checked;
            updateMobileMenu(navToggle.checked);
        });
    }

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
                confirmationInput.removeAttribute("aria-describedby");
                errorElement.hidden = true;
                return;
            }

            // Les deux valeurs correspondent.
            if (mainInput.value === confirmationInput.value) {
                confirmationInput.classList.add("is-valid");
                confirmationInput.classList.remove("is-invalid");
                confirmationInput.removeAttribute("aria-invalid");
                confirmationInput.removeAttribute("aria-describedby");
                errorElement.hidden = true;
            } else {
                // Les deux valeurs sont différentes.
                confirmationInput.classList.add("is-invalid");
                confirmationInput.classList.remove("is-valid");
                confirmationInput.setAttribute("aria-invalid", "true");
                confirmationInput.setAttribute("aria-describedby", errorElement.id);
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
    const tribuneInputs = document.querySelectorAll('input[name="tribune"]');
    const levelInputs = document.querySelectorAll('input[name="niveau"]');
    const reservationTotal = document.getElementById("reservation-total");
    const reservationStatus = document.getElementById("reservation-status");
    const reservationForm = document.querySelector(".reservation-form");
    const reservationSubmitButton = reservationForm?.querySelector('button[type="submit"]');

    let availabilityByZone = {};

    if (reservationForm) {
        availabilityByZone = JSON.parse(reservationForm.dataset.availability || "{}");
    }

    function getZoneAvailability(tribune, level) {
        return Number(availabilityByZone[tribune]?.[level] || 0);
    }

    function updateOptionState(input, availablePlaces, requestedPlaces) {
        const status = input.closest(".option") ?.querySelector("[data-option-status]");
        const isUnavailable = availablePlaces < requestedPlaces;
        
        input.disabled = isUnavailable;
        
        if (!status) {
            return;
        }

        status.hidden = !isUnavailable;
        status.textContent = availablePlaces === 0 ? "Complet" : "Indisponible pour " + requestedPlaces + " places";
    }

    function selectFirstAvailable(inputs) {
        const selectedInput = Array.from(inputs).find(function (input) {
            return input.checked && !input.disabled;
        });

        if (selectedInput) {
            return selectedInput;
        }

        const firstAvailableInput = Array.from(inputs).find(function (input) {
            return !input.disabled;
        });

        if (firstAvailableInput) {
            firstAvailableInput.checked = true;
        }

        return firstAvailableInput || null;
    }

    function updateReservationTotal() {
        const selectedPlace = document.querySelector('input[name="nb_places"]:checked');
        const selectedLevel = document.querySelector('input[name="niveau"]:checked');

        if (
            !selectedPlace
            || !selectedLevel
            || selectedLevel.disabled
            || !reservationTotal
        ) {
            if (reservationTotal) {
                reservationTotal.textContent = "— €";
            }

            return;
        }

        const numberOfPlaces = Number(selectedPlace.value);
        const price = Number(selectedLevel.dataset.price);
        
        reservationTotal.textContent = numberOfPlaces * price + " €";
    }
    
    function updateReservationStatus() {
        if (!reservationStatus) {
            return;
        }

        const selectedPlace = document.querySelector('input[name="nb_places"]:checked');
        const selectedTribune = document.querySelector('input[name="tribune"]:checked:not(:disabled)');
        const selectedLevel = document.querySelector('input[name="niveau"]:checked:not(:disabled)');

        if (!selectedPlace || !selectedTribune || !selectedLevel) {
            reservationStatus.textContent = "Aucune combinaison disponible pour cette sélection.";
            return;
        }

        const numberOfPlaces = Number(selectedPlace.value);
        const availablePlaces = getZoneAvailability(selectedTribune.value, selectedLevel.value);
        const total = numberOfPlaces * Number(selectedLevel.dataset.price);
        const selectedPlaceLabel = numberOfPlaces > 1 ? "places" : "place";
        const availablePlaceLabel = availablePlaces > 1 ? "places disponibles" : "place disponible";
        
        reservationStatus.textContent = numberOfPlaces + " " + selectedPlaceLabel
        + ", tribune " + selectedTribune.value.toLowerCase()
        + ", niveau " + selectedLevel.value.toLowerCase()
        + ". " + availablePlaces + " " + availablePlaceLabel
        + ". Total : " + total + " euros.";
    }

    function updateReservationAvailability() {
        const selectedPlace = document.querySelector('input[name="nb_places"]:checked');

        if (!selectedPlace) {
            return;
        }

        const requestedPlaces = Number(selectedPlace.value);

        // Une tribune reste disponible si au moins un de ses niveaux contient suffisamment de places.
        tribuneInputs.forEach(function (input) {
            const levels = Object.values(availabilityByZone[input.value] || {});
            const bestAvailability = levels.length > 0 ? Math.max(...levels.map(Number)) : 0;
            
            updateOptionState(input, bestAvailability, requestedPlaces);
        });

        const selectedTribune = selectFirstAvailable(tribuneInputs);

        // Les niveaux dépendent de la tribune actuellement sélectionnée.
        levelInputs.forEach(function (input) {
            const availablePlaces = selectedTribune ? getZoneAvailability(selectedTribune.value, input.value) : 0;

            updateOptionState(input, availablePlaces, requestedPlaces);
        });

        const selectedLevel = selectFirstAvailable(levelInputs);

        if (reservationSubmitButton) {
            reservationSubmitButton.disabled = !selectedTribune || !selectedLevel;
        }

        updateReservationTotal();
        updateReservationStatus();
    }

    if (reservationTotal) {
        placeInputs.forEach(function (input) {
            input.addEventListener("change", updateReservationAvailability);
        });

        tribuneInputs.forEach(function (input) {
            input.addEventListener("change", updateReservationAvailability);
        });

        levelInputs.forEach(function (input) {
            input.addEventListener("change", function () {
                updateReservationTotal();
                updateReservationStatus();
            });
        });

        updateReservationAvailability();
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