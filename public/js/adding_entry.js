document.addEventListener("DOMContentLoaded", function () {
    const amountInput = document.querySelector(".amount_input");

    function updateAmount(change) {
        let currentAmount = parseFloat(amountInput.value) || 0;
        currentAmount += change;
        amountInput.value = currentAmount.toString();
    }

    document.querySelector(".minus_button").addEventListener("click", function () {
        updateAmount(-1);
    });

    document.querySelector(".mid_button_minus").addEventListener("click", function () {
        updateAmount(-5);
    });

    document.querySelector(".last_button_minus").addEventListener("click", function () {
        updateAmount(-10);
    });

    document.querySelector(".plus_button").addEventListener("click", function () {
        updateAmount(1);
    });

    document.querySelector(".mid_button_plus").addEventListener("click", function () {
        updateAmount(5);
    });

    document.querySelector(".last_button_plus").addEventListener("click", function () {
        updateAmount(10);
    });
});
