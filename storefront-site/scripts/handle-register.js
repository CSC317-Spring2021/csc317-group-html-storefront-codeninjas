(() => {
    try {
        addRegisterHandlers();
    } catch(e) {
        console.error(e);
    };
})();

function addRegisterHandlers() {
    const registerFormElement = document.getElementById("register-form");
    if (registerFormElement) {
        registerFormElement.addEventListener("submit", handleRegisterSubmit, false);
    };
};

async function handleRegisterSubmit(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const invalid = {
        name: false,
        email: false,
        password: false,
        passwordRepeat: false,
    };

    if (formData.get("name").length === 0) {
        invalid.name = true;
        document.getElementById("error-message-name").textContent = "Enter your name.";
    };
    if (formData.get("email").length === 0) {
        invalid.email = true;
        document.getElementById("error-message-email").textContent = "Enter your email.";
    } else if (!/^.+@.+$/.test(formData.get("email"))) {
        invalid.email = true;
        document.getElementById("error-message-email").textContent = "Enter a valid email.";
    };
    if (formData.get("password").length === 0) {
        invalid.password = true;
        document.getElementById("error-message-password").textContent = "Enter your password.";
    } else if (formData.get("password").length < 6) {
        invalid.password = true;
        document.getElementById("error-message-password").textContent = "Passwords must be at least 6 characters.";
    };
    if (formData.get("password-repeat") !== formData.get("password")) {
        invalid.passwordRepeat = true;
        document.getElementById("error-message-password-repeat").textContent = "Passwords do not match.";
    };

    if (invalid.name) {
        document.getElementById("error-message-container-name").style.display = "block";
        document.getElementById("register-name-input").classList.add("invalid");
    } else {
        document.getElementById("error-message-container-name").style.display = "none";
        document.getElementById("register-name-input").classList.remove("invalid");
    };
    if (invalid.email) {
        document.getElementById("error-message-container-email").style.display = "block";
        document.getElementById("register-email-input").classList.add("invalid");
    } else {
        document.getElementById("error-message-container-email").style.display = "none";
        document.getElementById("register-email-input").classList.remove("invalid");
    };
    if (invalid.password) {
        document.getElementById("error-message-container-password").style.display = "block";
        document.getElementById("register-password-input").classList.add("invalid");

        document.getElementById("password-requirement").style.display = "none";
    } else {
        document.getElementById("error-message-container-password").style.display = "none";
        document.getElementById("register-password-input").classList.remove("invalid");

        document.getElementById("password-requirement").style.display = "block";
    };
    if (invalid.passwordRepeat) {
        document.getElementById("error-message-container-password-repeat").style.display = "block";
        document.getElementById("register-password-repeat-input").classList.add("invalid");
    } else {
        document.getElementById("error-message-container-password-repeat").style.display = "none";
        document.getElementById("register-password-repeat-input").classList.remove("invalid");
    };

    let hasError = false;
    for (const field in invalid) {
        if (invalid[field]) {
            hasError = true;
        };
    };
    if (!hasError) {
        try {
            const response = await fetch("/php/register.php", {
                method: "POST",
                body: formData,
            });
            if (200 <= response.status && response.status < 300) {
                window.location.replace("/html/register-success.php");
            } else if (400 <= response.status && response.status < 500) {
                const responseText = await response.text();
                if (responseText === "This email is already taken.") { // HACK: An improvement would be to make the backend return JSON objects instead of plaintext, so that additional metadata can be sent to determine error types.
                    document.getElementById("error-message-email").textContent = responseText;
                    document.getElementById("error-message-container-email").style.display = "block";
                    document.getElementById("register-email-input").classList.add("invalid");
                } else {
                    document.getElementById("error-message-general").textContent = responseText;
                    document.getElementById("error-message-container-general").style.display = "block";
                };
            } else {
                throw Error();
            };
        } catch(e) {
            document.getElementById("error-message-general").textContent = "Unknown error. Please try again later.";
            document.getElementById("error-message-container-general").style.display = "block";
        };
    };
};