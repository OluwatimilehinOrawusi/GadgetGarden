// Validate that new and confirm passwords match
function validateForm() {
    const newPassword = document.getElementById("new-password").value;
    const confirmPassword = document.getElementById("confirm-password").value;

    // Check if the passwords match
    if (newPassword !== confirmPassword) {
        alert("Please make sure your passwords match");
        return false; // Prevent form submission
    }

    // Password validation rules to improve security
    const passwordRequirements = [
        /[a-z]/, // At least one lowercase letter
        /[A-Z]/, // At least one uppercase letter
        /[0-9]/, // At least one number
        /[!@#$%^&*(),.?":{}|<>]/ // At least one special character
    ];

    let validPassword = true;
    let message = "Your password must contain:\n";

    if (!passwordRequirements[0].test(newPassword)) {
        validPassword = false;
        message += "- At least one lowercase letter\n";
    }
    if (!passwordRequirements[1].test(newPassword)) {
        validPassword = false;
        message += "- At least one uppercase letter\n";
    }
    if (!passwordRequirements[2].test(newPassword)) {
        validPassword = false;
        message += "- At least one number\n";
    }
    if (!passwordRequirements[3].test(newPassword)) {
        validPassword = false;
        message += "- At least one special character (e.g., @, #, $, %, etc.)\n";
    }

    // If password is invalid, alert the user
    if (!validPassword) {
        alert(message);
        return false; // Prevent form submission
    }

    return true; // Allow form submission if everything is valid
}

// Toggle password visibility
function togglePassword(inputId, icon) {
    const input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}

// Redirect to the login page
function goToLogin() {
    window.location.href = "login.php"; // Adjust path if needed
}
