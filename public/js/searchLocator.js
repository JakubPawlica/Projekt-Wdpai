document.addEventListener("DOMContentLoaded", () => {
    const search = document.querySelector('input.searchTerm');
    const locatorContainer = document.querySelector(".locator-list");

    if (!search || !locatorContainer) {
        console.error("Nie znaleziono wymaganych elementów w DOM.");
        return;
    }

    let timeout;

    search.addEventListener("input", function () {
        clearTimeout(timeout);

        timeout = setTimeout(() => {
            const data = { search: this.value };

            fetch("/searchLocator", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(data),
            })
                .then((response) => response.text()) // Zmień na text(), aby zobaczyć całą odpowiedź
                .then((text) => {
                    console.log("Odpowiedź serwera (tekst):", text);
                    const results = JSON.parse(text); // Przekonwertuj tekst na JSON
                    locatorContainer.innerHTML = ""; // Wyczyść listę przed załadowaniem wyników
                    loadLocators(results);
                })
                .catch((error) => {
                    console.error("Błąd JSON lub Fetch:", error.message);
                });
        }, 300);
    });

    function loadLocators(results) {
        results.forEach((entry) => {
            createLocator(entry);
        });
    }

    function createLocator(entry) {
        const template = document.querySelector("#locator-template");

        if (!template) {
            console.error("Nie znaleziono szablonu #locator-template.");
            return;
        }

        const clone = template.content.cloneNode(true);

        const entryId = clone.querySelector(".entry-id");
        entryId.textContent = entry.entry_id;

        const locations = clone.querySelector(".locations");
        locations.innerHTML  = entry.all_locations.replace(/\n/g, '<br>');

        const amounts = clone.querySelector(".amounts");
        amounts.innerHTML  = entry.all_amounts.replace(/\n/g, '<br>');

        const totalAmount = clone.querySelector(".total-amount");
        totalAmount.textContent = entry.total_amount;

        locatorContainer.appendChild(clone);
    }
});
