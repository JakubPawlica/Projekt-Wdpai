function updateUserName(id, newUserName) {
    fetch('/updateUserName', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id, newUserName: newUserName })
    })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Nie udało się zaktualizować użytkownika.');
            }
        })
        .then(data => {
            // Zaktualizuj tabelę na stronie
            document.querySelector(`#user-name-${id}`).innerText = newUserName;
        })
        .catch(error => console.error(error));
}
