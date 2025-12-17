#include <DHT.h>

// --- CONFIGURATION MATÉRIEL ---
#define DHTPIN 2
#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);

const int pinPluie = A1;    // Capteur Pluie (Analogique)
const int pinLumiere = 4;   // Capteur Lumière (Numérique D4)

// --- CONFIGURATION TEMPS ---
// 1 heure = 3600000 ms. 
// Pour les tests, mets 5000 (5 sec) sinon on attend trop longtemps !
const unsigned long DELAI_PERIODIQUE = 3600000; 

unsigned long dernierEnvoi = 0; // Chronomètre pour le mode périodique

void setup() {
  Serial.begin(9600);
  dht.begin();
  pinMode(pinPluie, INPUT);
  pinMode(pinLumiere, INPUT);
  
  Serial.println("Sation Test - Initialisation");

}

void loop() {
  // --- A. GESTION DU MODE PÉRIODIQUE ---
  // On regarde si le temps écoulé dépasse le délai fixé (1 heure)
  unsigned long tempsActuel = millis();
  
  if (tempsActuel - dernierEnvoi >= DELAI_PERIODIQUE) {
    // C'est l'heure ! On envoie les mesures
    envoyerMesures();
    dernierEnvoi = tempsActuel; // On remet le chrono à zéro
  }

  // --- B. GESTION DU MODE "SUR DEMANDE" (Listening) ---
  // On regarde si le serveur Java nous parle sur le port série
  if (Serial.available() > 0) {
    // On lit la commande envoyée par Java jusqu'au saut de ligne
    String commande = Serial.readStringUntil('\n');
    
    // Nettoyage de la chaine (enlève les espaces ou retour chariot parasites)
    commande.trim();

    // Protocole d'échange : Si Java envoie "GET", on répond
    if (commande == "GET") {
      envoyerMesures();
    }
    // Tu pourras ajouter d'autres commandes ici (ex: "STATUS", "RESET"...)
  }
}

// --- FONCTION D'ENVOI (Pour ne pas répéter le code) ---
void envoyerMesures() {
  // 1. Lecture
  float t = dht.readTemperature();
  float h = dht.readHumidity();
  int l = digitalRead(pinLumiere); // 0 ou 1
  int p = analogRead(pinPluie);    // 0 à 1023

  // Gestion des erreurs capteurs (Sécurité)
  if (isnan(t) || isnan(h)) {
    t = 0.0; h = 0.0; // Valeurs par défaut en cas d'erreur
  }

  // 2. Construction de la trame (Respect strict du format CDC)
  Serial.print("<TEMP:"); 
  Serial.print(t, 1);
  Serial.print(";HUM:"); 
  Serial.print((int)h);
  Serial.print(";LUM:"); 
  Serial.print(l); 
  Serial.print(";RAIN:"); 
  Serial.print(p); 
  // La date est gérée par le serveur Java à la réception, on met un placeholder
  Serial.println(";DATE:0>"); 
}