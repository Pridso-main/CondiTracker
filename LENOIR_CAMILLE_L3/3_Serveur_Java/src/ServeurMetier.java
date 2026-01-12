public class ServeurMetier {

    public static void main(String[] args) {
        System.out.println("--- CONDITRACKER SERVEUR METIER ---");
        System.out.println("Démarrage du système...");

        // Imaginons que nous avons une station branchée sur COM10
        // On crée un thread dédié pour elle
        ThreadStation station1 = new ThreadStation("COM10");
        
        // On lance le thread (ça appelle la méthode run() automatiquement)
        station1.start();

        // Si tu avais une deuxième station sur COM11 :
        // ThreadStation station2 = new ThreadStation("COM11");
        // station2.start();

        System.out.println("Serveur en écoute. Appuyez sur Ctrl+C pour arrêter.");
        
        // Le main s'arrête ici, mais les threads continuent de tourner en fond !
    }
}