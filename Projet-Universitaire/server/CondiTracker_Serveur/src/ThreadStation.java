public class ThreadStation extends Thread {

    String nomPort;
    GestionSerie serie;
    GestionBDD bdd;
    boolean estConnecte = false;

    // Constructeur : on lui donne le port (ex: COM10)
    public ThreadStation(String lePort) {
        this.nomPort = lePort;
        this.serie = new GestionSerie();
        this.bdd = new GestionBDD();
    }

    // C'est cette fonction qui tourne en parallèle quand on fait .start()
    public void run() {
        System.out.println("[THREAD] Démarrage du thread pour " + nomPort);

        // 1. Ouvrir le port
        if (!serie.connecter(nomPort)) {
            System.out.println("[THREAD] Echec connexion " + nomPort);
            return; // On arrête ce thread
        }

        // Boucle infinie pour écouter la station
        while (true) {
            try {
                if (serie.aDesDonnees()) {
                    String message = serie.lire().trim(); // .trim() enlève les espaces inutiles
                    
                    // On vérifie que c'est une vraie trame <...>
                    if (message.startsWith("<") && message.endsWith(">")) {
                        traiterMessage(message);
                    }
                }
                Thread.sleep(100); // Pause cpu
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }

    // Fonction pour décortiquer le message et respecter le protocole
    void traiterMessage(String msg) {
        // On enlève < et > pour avoir juste le contenu : SYN;ID:01;STA:UP
        String contenu = msg.substring(1, msg.length() - 1);
        String[] blocs = contenu.split(";"); // On coupe par point-virgule

        // --- CAS 1 : HANDSHAKE (SYN) ---
        if (contenu.contains("SYN")) {
            System.out.println("[" + nomPort + "] Demande SYN reçue : " + msg);
            
            // On récupère l'ID (ex: ID:01) -> on prend juste "01"
            String idStation = blocs[1].split(":")[1]; 

            // On répond ACK en mode PUSH (automatique) pour simplifier
            String reponse = "<ACK;ID:" + idStation + ";MODE:Push;FREQ:5000>";
            serie.envoyer(reponse);
            System.out.println("[" + nomPort + "] -> Envoi ACK (Config Push 5s)");
        }

        // --- CAS 2 : DONNEES (REP) ---
        // Ex: REP;ID:01;TEMP:20.5;HUM:64;PLU:1023;LUM:1
        else if (contenu.contains("REP")) {
            System.out.println("[" + nomPort + "] Données reçues : " + msg);

            try {
                // On extrait les valeurs (Parsing simple)
                String id = blocs[1].split(":")[1];
                float t = Float.parseFloat(blocs[2].split(":")[1]); // TEMP
                float h = Float.parseFloat(blocs[3].split(":")[1]); // HUM
                int p = Integer.parseInt(blocs[4].split(":")[1]);   // PLU
                int l = Integer.parseInt(blocs[5].split(":")[1]);   // LUM

                // On envoie à la BDD
                bdd.enregistrerMesure(id, t, h, l, p);

            } catch (Exception e) {
                System.out.println("[" + nomPort + "] Erreur lecture données (Trame corrompue ?)");
            }
        }
    }
}