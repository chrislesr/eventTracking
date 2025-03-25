 async function fetchNotifications() {
    try {
        const csrfToken = document.getElementById('token_csrf').value;

        const control = 'profil';
        const views = 'profil';
        const response = await fetch('notifications.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `csrf_token=${encodeURIComponent(csrfToken)}&control=${encodeURIComponent(control)}&views=${encodeURIComponent(views)}`,
        });
        if (!response.ok) {
            throw new Error('Erreur serveur : ' + response.status);
        }
        const data = await response.json();

         // Mettre à jour les éléments HTML
        if (data.nbrenotification !== undefined) {
            document.getElementById('nbrenotification').textContent = data.nbrenotification;
        }

        if (data.csrf_token) {
            document.getElementById('token_csrf').value = data.csrf_token;
        }

    } catch (error) {
        console.error('Erreur lors de la récupération des données :', error);
    }
}

// Appeler la fonction
fetchNotifications();
setInterval(fetchNotifications, 5000);