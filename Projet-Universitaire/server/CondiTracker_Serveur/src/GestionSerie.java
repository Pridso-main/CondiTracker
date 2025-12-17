import com.fazecast.jSerialComm.SerialPort;
import java.io.InputStream;
import java.io.OutputStream;

public class GestionSerie {

    SerialPort lePort;
    InputStream entree;
    OutputStream sortie;

    // Fonction pour se connecter au port (ex: COM10)
    public boolean connecter(String nomDuPort) {
        lePort = SerialPort.getCommPort(nomDuPort);
        lePort.setBaudRate(9600); // Vitesse standard Arduino
        // Important : on définit des timeouts pour éviter de bloquer indéfiniment
        lePort.setComPortTimeouts(SerialPort.TIMEOUT_READ_SEMI_BLOCKING, 0, 0);

        if (lePort.openPort()) {
            System.out.println("[SERIE] Port " + nomDuPort + " ouvert avec succès !");
            entree = lePort.getInputStream();
            sortie = lePort.getOutputStream();
            return true;
        } else {
            System.err.println("[ERREUR] Impossible d'ouvrir le port " + nomDuPort);
            return false;
        }
    }

    // Fonction pour envoyer un message vers l'Arduino
    public void envoyer(String message) {
        try {
            // On ajoute un saut de ligne au cas où, mais ton protocole gère les balises < >
            byte[] octets = message.getBytes();
            sortie.write(octets);
            sortie.flush(); // On force l'envoi immédiat
            // System.out.println("-> ENVOI : " + message); // Décommenter pour voir tout ce qui part
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    // Est-ce qu'il y a des données qui arrivent ?
    public boolean aDesDonnees() {
        if (lePort.bytesAvailable() > 0) {
            return true;
        }
        return false;
    }

    // Lire le texte reçu
    public String lire() {
        try {
            if (lePort.bytesAvailable() == 0) return "";
            
            // On attend un tout petit peu pour être sûr d'avoir le message entier
            try { Thread.sleep(20); } catch(Exception e) {}

            int taille = lePort.bytesAvailable();
            byte[] buffer = new byte[taille];
            entree.read(buffer, 0, taille);
            
            return new String(buffer);
        } catch (Exception e) {
            return "";
        }
    }
    
    // Fermer proprement
    public void fermer() {
        if (lePort != null) {
            lePort.closePort();
        }
    }
}