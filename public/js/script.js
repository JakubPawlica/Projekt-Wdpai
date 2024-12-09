const form = document.querySelector("form");
const emailInput = form.querySelector('input[name="email"]');

function isEmail(email) {
    return /\S+@\S+\.\S+/.test(email);
}

function markValidation(element, condition) {
    console.log("Mark validation called for:", element, "Condition:", condition);
    !condition ? element.classList.add('no-valid') : element.classList.remove('no-valid');
}

emailInput.addEventListener('keyup', function() {
    console.log("Keyup event triggered! Current value:", emailInput.value);
    setTimeout( function() {
        markValidation(emailInput, isEmail(emailInput.value));
    }, 1000);
});
