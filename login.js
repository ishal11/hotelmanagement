let signup = document.querySelector(".signup");
let login = document.querySelector(".login");
let slider = document.querySelector(".slider");
let formSection = document.querySelector(".form-section");

signup.addEventListener("click", () => {
    slider.classList.add("moveslider");
    formSection.classList.add("form-section-move");
});

login.addEventListener("click", () => {
    slider.classList.remove("moveslider");
    formSection.classList.remove("form-section-move");
});

document.addEventListener("DOMContentLoaded", () => {
    // Handle Signup Form Submission
    document.querySelector(".signup-box .clkbtn").addEventListener("click", () => {
        const name = document.querySelector(".signup-box .name").value;
        const email = document.querySelector(".signup-box .email").value;
        const password = document.querySelectorAll(".signup-box .password")[0].value;
        const confirmPassword = document.querySelectorAll(".signup-box .password")[1].value;

        // Basic Validation
        if (!name || !email || !password || !confirmPassword) {
            alert("Please fill in all fields.");
            return;
        }
        if (password !== confirmPassword) {
            alert("Passwords do not match.");
            return;
        }

        // Create and submit the signup form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'process.php';
        form.innerHTML = `
            <input type="hidden" name="action" value="signup">
            <input type="hidden" name="name" value="${name}">
            <input type="hidden" name="email" value="${email}">
            <input type="hidden" name="password" value="${password}">
        `;
        document.body.appendChild(form);
        form.submit();
    });

    // Handle Login Form Submission
    document.querySelector(".login-box .clkbtn").addEventListener("click", () => {
        const email = document.querySelector(".login-box .email").value;
        const password = document.querySelector(".login-box .password").value;

        if (!email || !password) {
            alert("Please enter your email and password.");
            return;
        }

        // Create and submit the login form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'process.php';
        form.innerHTML = `
            <input type="hidden" name="action" value="login">
            <input type="hidden" name="email" value="${email}">
            <input type="hidden" name="password" value="${password}">
        `;
        document.body.appendChild(form);
        form.submit();
    });
});
