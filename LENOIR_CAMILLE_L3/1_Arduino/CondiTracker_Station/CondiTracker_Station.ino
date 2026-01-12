/*
 * CondiTracker - Station Météo Connectée (SAE33)
 * Protocole : Version Finale (SYN/ACK + Push/Pull)
 * Matériel :
 * - DHT11 : Pin D2
 * - Pluie (MH-RD) : Pin A1
 * - Luminosité (GT541) : Pin D4
 */

#include <DHT.h>

// --- CONFIGURATION MATERIELLE ---
#define DHTPIN 2        // Capteur Temp/Hum (Digital 2)
#define DHTTYPE DHT11   // Type de capteur
#define PIN_PLUIE A1    // Capteur Pluie (Analogique 1)
#define PIN_LUM 4       // Capteur Luminosité (Digital 4)

// --- IDENTITE ---
const String STATION_ID = "03"; 

// --- OBJETS ---
DHT dht(DHTPIN, DHTTYPE);

// --- MACHINE A ETATS ---
enum Etat {
  ETAT_INIT,       // Handshake (SYN)
  ETAT_PUSH,       // Envoi auto
  ETAT_PULL        // Attente requête
};

Etat etatActuel = ETAT_INIT;

// --- VARIABLES GESTION ---
unsigned long pushFrequency = 60000; // Par défaut 1 min (sera écrasé par le serveur)
unsigned long dernierEnvoi = 0;
unsigned long dernierSyn = 0;

// Variables communication
String messageRecu = "";
bool receptionEnCours = false;

void setup() {
  Serial.begin(9600);
  
  // Init Capteurs
  dht.begin();
  pinMode(PIN_PLUIE, INPUT);
  pinMode(PIN_LUM, INPUT);
  
  delay(1000); // Chauffe capteurs
}

void loop() {
  // 1. Ecoute permanente du port série
  ecouterPortSerie();

  // 2. Machine à états
  switch (etatActuel) {
    case ETAT_INIT:
      gererInitialisation();
      break;

    case ETAT_PUSH:
      gererModePush();
      break;

    case ETAT_PULL:
      // En attente d'ordres (traité dans ecouterPortSerie)
      break;
  }
}

// -------------------------------------------------------------------------
// FONCTIONS ETATS
// -------------------------------------------------------------------------

void gererInitialisation() {
  // Envoi SYN toutes les 2 secondes tant que pas de réponse
  if (millis() - dernierSyn > 2000) {
    
    // Diagnostic rapide des capteurs
    float h = dht.readHumidity();
    float t = dht.readTemperature();
    int valPluie = analogRead(PIN_PLUIE); 
    int valLum = digitalRead(PIN_LUM); 

    String statut = "UP"; // Par défaut tout va bien
    
    // Si un capteur ne répond pas, on signale l'erreur
    if (isnan(h) || isnan(t)) {
      statut = "ERR_DHT";
    }
    else if (isnan(valPluie)) {
      statut = "ERR_MH-RD";
    }
    else if (isnan(valLum)) {
      statut = "ERR_GT541";
    }
    else {
      statut = "UP";
    }

    // Trame : <SYN;ID:01;STA:UP>
    String trame = "<SYN;ID:" + STATION_ID + ";STA:" + statut + ">";
    Serial.println(trame);
    
    dernierSyn = millis();
  }
}

void gererModePush() {
  if (millis() - dernierEnvoi >= pushFrequency) {
    envoyerMesures();
    dernierEnvoi = millis();
  }
}

// -------------------------------------------------------------------------
// PROTOCOLE & COMMUNICATION
// -------------------------------------------------------------------------

void ecouterPortSerie() {
  while (Serial.available() > 0) {
    char c = (char)Serial.read();

    if (c == '<') { 
      receptionEnCours = true;
      messageRecu = ""; 
    } 
    else if (c == '>') {
      receptionEnCours = false;
      traiterMessageServeur(messageRecu);
    } 
    else if (receptionEnCours) {
      messageRecu += c;
    }
  }
}

void traiterMessageServeur(String msg) {
  // Vérification destinataire
  if (msg.indexOf("ID:" + STATION_ID) == -1) return;

  // CAS 1 : ACK (Configuration)
  if (msg.indexOf("ACK") != -1) {
    if (msg.indexOf("MODE:Push") != -1) {
      // Extraction Fréquence
      int indexFreq = msg.indexOf("FREQ:");
      if (indexFreq != -1) {
        // substring jusqu'à la fin ou prochain ';'
        // Ici on suppose que FREQ est le dernier ou suivi d'un point virgule
        String sub = msg.substring(indexFreq + 5);
        pushFrequency = strtoul(sub.c_str(), NULL, 10);
      }
      etatActuel = ETAT_PUSH;
    } 
    else if (msg.indexOf("MODE:Pull") != -1) {
      etatActuel = ETAT_PULL;
    }
  }
  
  // CAS 2 : NACK (Erreur)
  else if (msg.indexOf("NACK") != -1) {
    etatActuel = ETAT_INIT;
  }

  // CAS 3 : REQ (Demande de mesure en mode Pull)
  else if (etatActuel == ETAT_PULL && msg.indexOf("REQ") != -1) {
    envoyerMesures();
  }
}

// -------------------------------------------------------------------------
// ACQUISITION
// -------------------------------------------------------------------------

void envoyerMesures() {
  // 1. Lecture DHT11 (D2)
  float h = dht.readHumidity();
  float t = dht.readTemperature();
  
  // 2. Lecture Pluie (A1)
  // Valeur brute entre 0 (trempé) et 1023 (sec)
  int valPluie = analogRead(PIN_PLUIE); 

  // 3. Lecture Luminosité (D4)
  // Note: digitalRead renvoie 0=nuit ou 1=jour. 
  int valLum = digitalRead(PIN_LUM); 

  String trame = "";

  // Gestion erreur des capteurs
  if (isnan(h) || isnan(t)) {
    trame = "<REP;ID:" + STATION_ID + ";STA:ERR_DHT>";
  }
  else if (isnan(valPluie)) {
    trame = "<REP;ID:" + STATION_ID + ";STA:ERR_MH-RD>";
  }
  else if (isnan(valLum)) {
    trame = "<REP;ID:" + STATION_ID + ";STA:ERR_GT541>";
  } else {
    // Construction de la trame finale
    // Format : <REP;ID:01;TEMP:20.5;HUM:60;PLU:1023;LUM:1>
    trame = "<REP;ID:" + STATION_ID + 
            ";TEMP:" + String(t, 1) + 
            ";HUM:" + String(h, 0) + 
            ";PLU:" + String(valPluie) + 
            ";LUM:" + String(valLum) + ">";
  }

  Serial.println(trame);
}