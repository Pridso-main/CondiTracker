import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;

public class GestionBDD {

    // On s'assure de désactiver explicitement GSSAPI dans l'URL pour être sûr
    // Et on ajoute ?allowPublicKeyRetrieval=true pour éviter d'autres erreurs courantes
    String url = "jdbc:mariadb://localhost:3307/conditracker?useGssapi=false&allowPublicKeyRetrieval=true";
    
    // Tes nouveaux identifiants
    String utilisateur = "condi_user";
    String motDePasse = "condi"; 

    public void enregistrerMesure(String idStation, float temp, float hum, int lum, int pluie) {
        try {
            Class.forName("org.mariadb.jdbc.Driver"); 
            Connection connexion = DriverManager.getConnection(url, utilisateur, motDePasse);

            String sql = "INSERT INTO mesures (station_id, temperature, humidite, luminosite, pluie) VALUES (?, ?, ?, ?, ?)";
            PreparedStatement requete = connexion.prepareStatement(sql);

            requete.setString(1, idStation);
            requete.setFloat(2, temp);
            requete.setFloat(3, hum);
            requete.setInt(4, lum);
            requete.setInt(5, pluie);

            requete.executeUpdate();
            System.out.println("[BDD] Mesure enregistrée pour la station " + idStation);

            requete.close();
            connexion.close();

        } catch (Exception e) {
            System.out.println("[ERREUR BDD] " + e.getMessage());
            // e.printStackTrace(); // Tu peux commenter ça si tu veux moins de texte rouge
        }
    }
}