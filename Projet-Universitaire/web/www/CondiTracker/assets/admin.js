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

    // Changement 1 : On récupère les valeurs
    const userVal = document.getElementById('username').value; // ID HTML n'a pas changé
    const passVal = document.getElementById('password').value; // ID HTML n'a pas changé

    try {
        // Changement 2 : On envoie 'identifiant' et 'mot_de_passe'
        const response = await fetch('api/admin_api.php?action=login', {
            method: 'POST',
            body: JSON.stringify({ 
                identifiant: userVal, 
                mot_de_passe: passVal 
            }),
            headers: { 'Content-Type': 'application/json' }
        });
        const result = await response.json();

        // Changement 3 : On vérifie 'success' (booléen)
        if (result.success === true) {
            // Connexion réussie !
            passerEnModeDashboard();
        } else {
            // Erreur
            loginError.innerText = "Erreur : " + result.message;
            loginError.style.display = 'block'; // S'assurer qu'il est visible
        }
    } catch (err) {
        console.error(err);
        loginError.innerText = "Erreur de connexion au serveur (API introuvable ?)";
        loginError.style.display = 'block';
    }
});

// 2. FONCTION POUR AFFICHER LE DASHBOARD
function passerEnModeDashboard() {
    loginView.style.display = 'none';       // Cache le login
    dashboardView.style.display = 'block';  // Affiche le dashboard
    
    // Si tu as un bouton logout dans le HTML, affiche-le
    if(logoutBtn) logoutBtn.style.display = 'block'; 
    
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

        // Changement 4 : Vérification de 'success'
        if (result.success === true) {
            const data = result.data;

            // A. Afficher l'état des stations
            const stationsList = document.getElementById('stations-list');
            if(stationsList) {
                stationsList.innerHTML = "";
                
                data.stations.forEach(st => {
                    const statusClass = st.online ? "status-online" : "status-offline";
                    const statusText = st.online ? "En ligne" : "Hors ligne";
                    const html = `
                        <div class="station-card">
                            <div>
                                <strong>${st.nom}</strong> (ID:${st.id})<br>
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
            }

            // B. Afficher les Logs
            const logsBody = document.getElementById('logs-body');
            if(logsBody) {
                logsBody.innerHTML = "";
                
                data.logs.forEach(log => {
                    // Couleur selon le niveau
                    let colorClass = "";
                    if(log.niveau === "ERROR") colorClass = "log-error";
                    if(log.niveau === "INFO") colorClass = "log-info";
                    if(log.niveau === "WARNING") colorClass = "log-warning";

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
        }
    } catch (err) {
        console.error("Erreur supervision", err);
    }
}

// 4. GESTION DÉCONNEXION
if(logoutBtn) {
    logoutBtn.addEventListener('click', () => {
        window.location.reload();
    });
}