const search = document.querySelector('input[placeholder="Szukaj wpisów po dowolnym z kryteriów"]');
const entryContainer = document.querySelector(".list table tbody");

let timeout;

search.addEventListener("input", function () {

    clearTimeout(timeout);

    timeout = setTimeout(() => {
        const data = { search: this.value };

        fetch("/search", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        }).then(function (response) {
            if (!response.ok) {
                throw new Error('Błąd serwera: ' + response.statusText);
            }
            return response.json();
        }).then(function (home) {
            entryContainer.innerHTML = "";
            loadProjects(home);
        }).catch(function (error) {
            console.error("Błąd:", error.message);
        });
    }, 300);
});

function loadProjects(home) {
    home.forEach(entry => {
        console.log(entry);
        createEntry(entry);
    });
}

function createEntry(entry) {
    const template = document.querySelector("#entry-template");

    const clone = template.content.cloneNode(true);

    const user_name = clone.querySelector(".user_name");
    user_name.innerHTML = entry.user_name;
    const number_id = clone.querySelector(".number_id");
    number_id.innerHTML = entry.entry_id;
    const location = clone.querySelector(".location");
    location.innerHTML = entry.location;
    const amount = clone.querySelector(".amount");
    amount.innerHTML = entry.amount;

    // Tworzenie dynamicznego linku do usuwania wpisu
    const deleteLink = clone.querySelector("a");
    deleteLink.href = `deleteEntry?id=${entry.id}`;
    deleteLink.setAttribute(
        "onclick",
        `return handleDelete('${entry.user_name}');`
    );

    entryContainer.appendChild(clone);
}
