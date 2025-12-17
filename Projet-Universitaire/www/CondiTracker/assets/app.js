// On récupère l'élément Select
const stationSelect = document.getElementById('station-select');

// Fonction pour remettre l'interface à zéro (Mode "Attente")
function resetInterface() {
    // On remet les textes à "--"
    document.getElementById('temp-display').innerText = "--";
    document.getElementById('hum-display').innerText = "--";
    document.getElementById('lum-display').innerText = "--";
    
    document.getElementById('note-display').innerText = "--";
    document.getElementById('message-display').innerText = "Veuillez sélectionner une station";
    document.getElementById('date-display').innerText = "--:--";

    // On remet le bandeau en bleu neutre
    const banner = document.getElementById('banner-bg');
    banner.style.background = "linear-gradient(135deg, #6dd5ed, #2193b0)";
    banner.style.color = "white";

    // On vide le tableau d'historique
    document.getElementById('history-body').innerHTML = `<tr><td colspan="4" style="text-align:center;">Aucune station sélectionnée</td></tr>`;
}

// Fonction principale
async function chargerDonnees(idStation) {
    // SÉCURITÉ : Si l'ID est vide (cas du démarrage), on ne fait rien sauf reset
    if (!idStation || idStation === "") {
        resetInterface();
        return; // On arrête la fonction ici
    }

    try {
        const reponse = await fetch(`api/api.php?id=${idStation}`);
        const resultat = await reponse.json();

        if(resultat.status === "success") {
            const current = resultat.current;
            
            // Mise à jour des valeurs
            document.getElementById('temp-display').innerText = current.temperature;
            document.getElementById('hum-display').innerText = current.humidite;
            document.getElementById('lum-display').innerText = current.luminosite;
            
            document.getElementById('note-display').innerText = current.note;
            document.getElementById('message-display').innerText = current.message;
            document.getElementById('date-display').innerText = current.date;

            // Couleur du bandeau selon la note
            const banner = document.getElementById('banner-bg');
            if(current.note >= 7) {
                banner.style.background = "linear-gradient(135deg, #56ab2f, #a8e063)"; // Vert
                banner.style.color = "white";
            } else if(current.note >= 4) {
                banner.style.background = "linear-gradient(135deg, #fceabb, #f8b500)"; // Orange
                banner.style.color = "#333";
            } else {
                banner.style.background = "linear-gradient(135deg, #bdc3c7, #2c3e50)"; // Gris/Rouge
                banner.style.color = "white";
            }

            // Historique
            const tbody = document.getElementById('history-body');
            tbody.innerHTML = ""; 
            resultat.history.forEach(mesure => {
                const row = `
                    <tr>
                        <td>${mesure.date_heure}</td>
                        <td>${mesure.temperature} °C</td>
                        <td>${mesure.humidite} %</td>
                        <td>${mesure.luminosite} Lux</td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });

        } else {
            console.warn("Info :", resultat.message);
            resetInterface(); // Si erreur API, on reset aussi
            document.getElementById('message-display').innerText = "Données indisponibles";
        }

    } catch (erreur) {
        console.error("Erreur connexion", erreur);
    }
}

// Écouteur : Quand on change le menu
stationSelect.addEventListener('change', (e) => {
    chargerDonnees(e.target.value);
});

// Au démarrage : on lance la fonction (qui va voir que c'est vide et lancer resetInterface)
chargerDonnees(stationSelect.value);

// Rafraîchissement auto : Seulement si une station est choisie !
setInterval(() => {
    if (stationSelect.value !== "") {
        chargerDonnees(stationSelect.value);
    }
}, 5000);