// Fichier : assets/admin.js

// Éléments du DOM
const loginView = document.getElementById('login-view');
const dashboardView = document.getElementById('dashboard-view');
const loginForm = document.getElementById('login-form');
const loginError = document.getElementById('login-error');
const logoutBtn = document.getElementById('btn-logout');

// 1. GESTION DE LA CONNEXION
loginForm.addEventListener('submit', async (e) => {
    e.preventDefault(); // Empêche la page de se recharger

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    try {
        // Appel à l'API Admin pour vérifier le login
        const response = await fetch('api/admin_api.php?action=login', {
            method: 'POST',
            body: JSON.stringify({ username: username, password: password }),
            headers: { 'Content-Type': 'application/json' }
        });
        const result = await response.json();

        if (result.status === 'success') {
            // Connexion réussie !
            passerEnModeDashboard();
        } else {
            // Erreur (mot de passe faux)
            loginError.innerText = "Erreur : " + result.message;
        }
    } catch (err) {
        console.error(err);
        loginError.innerText = "Erreur de connexion au serveur.";
    }
});

// 2. FONCTION POUR AFFICHER LE DASHBOARD
function passerEnModeDashboard() {
    loginView.style.display = 'none';       // Cache le login
    dashboardView.style.display = 'block';  // Affiche le dashboard
    logoutBtn.style.display = 'block';      // Affiche le bouton déconnexion
    
    // On charge les données tout de suite
    chargerDonneesSupervision();
    
    // Et on rafraîchit toutes les 10 secondes
    setInterval(chargerDonneesSupervision, 10000);
}

// 3. CHARGEMENT DES DONNÉES TECHNIQUES
async function chargerDonneesSupervision() {
    try {
        const response = await fetch('api/admin_api.php?action=getData');
        const result = await response.json();

        if (result.status === 'success') {
            const data = result.data;

            // A. Afficher l'état des stations
            const stationsList = document.getElementById('stations-list');
            stationsList.innerHTML = "";
            
            data.stations.forEach(st => {
                const statusClass = st.online ? "status-online" : "status-offline";
                const statusText = st.online ? "En ligne" : "Hors ligne";
                const html = `
                    <div class="station-card">
                        <div>
                            <strong>Station ${st.id}</strong><br>
                            <small>Vue : ${st.last_seen}</small>
                        </div>
                        <div style="display:flex; align-items:center;">
                            <span class="status-indicator ${statusClass}"></span>
                            <span>${statusText}</span>
                        </div>
                    </div>
                `;
                stationsList.innerHTML += html;
            });

            // B. Afficher les Logs
            const logsBody = document.getElementById('logs-body');
            logsBody.innerHTML = "";
            
            data.logs.forEach(log => {
                // Couleur selon le niveau
                let colorClass = "";
                if(log.niveau === "ERROR") colorClass = "log-error";
                if(log.niveau === "INFO") colorClass = "log-info";

                const row = `
                    <tr>
                        <td>${log.date_heure}</td>
                        <td class="${colorClass}"><strong>${log.niveau}</strong></td>
                        <td>${log.message}</td>
                    </tr>
                `;
                logsBody.innerHTML += row;
            });
        }
    } catch (err) {
        console.error("Erreur supervision", err);
    }
}

// 4. GESTION DÉCONNEXION (Recharge la page simplement)
logoutBtn.addEventListener('click', () => {
    window.location.reload();
});