document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("importForm").addEventListener("submit", function (event) {
        const correctPassword = "MK18PSWD";
        const userPassword = prompt("Podaj hasło, aby kontynuować:");

        if (userPassword !== correctPassword) {
            event.preventDefault();
            alert("Niepoprawne hasło! Import anulowany.");
        }
    });
});
