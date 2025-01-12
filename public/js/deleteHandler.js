const currentUserName = document.querySelector('meta[name="current-user"]').content;

function handleDelete(entryUserName) {
    console.log("Użytkownik wpisu:", entryUserName, "Aktualny użytkownik:", currentUserName);

    if (entryUserName !== currentUserName) {
        alert("Nie możesz usunąć wpisów należących do innego użytkownika.");
        return false;
    }
    return confirm("Czy na pewno chcesz usunąć ten wpis?");
}
