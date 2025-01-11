// Pobierz imię i nazwisko obecnie zalogowanego użytkownika
const currentUserName = document.querySelector('meta[name="current-user"]').content;

// Funkcja obsługująca usuwanie wpisów
function handleDelete(entryUserName) {
    console.log("Użytkownik wpisu:", entryUserName, "Aktualny użytkownik:", currentUserName);

    if (entryUserName !== currentUserName) {
        alert("Nie możesz usunąć wpisów należących do innego użytkownika.");
        return false; // Zatrzymuje wykonanie akcji usunięcia
    }
    return confirm("Czy na pewno chcesz usunąć ten wpis?");
}
