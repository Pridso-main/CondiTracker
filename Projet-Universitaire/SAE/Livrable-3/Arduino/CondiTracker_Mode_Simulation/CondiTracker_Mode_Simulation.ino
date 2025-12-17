#include <DHT.h>
#include <math.h>

// --- CONFIGURATION ---
#define DHTPIN 2     // Pin du DHT11
#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);

const int pinPluie = A1;       // Capteur Pluie 
const int facteurVitesse = 300; // 300x plus vite que la réalité

// Variables Temps Virtuel
unsigned long dernierTopReel = 0;
int vAnnee = 2025; int vMois = 11; int vJour = 22;
float vHeure = 6.0; // On commence la démo au lever du soleil (6h)

void setup() {
  Serial.begin(9600);
  dht.begin(); // Démarrage du capteur Temp/Hum
  pinMode(pinPluie, INPUT);
}

void loop() {
  unsigned long maintenant = millis();
  
  // Mise à jour toutes les 100ms réelles pour fluidité
  if (maintenant - dernierTopReel >= 100) { 
    dernierTopReel = maintenant;
    
    // --- 1. TEMPS ACCÉLÉRÉ ---
    // On avance de (0.1s * facteur) converti en heures
    vHeure += (0.1 * facteurVitesse) / 3600.0; 
    if (vHeure >= 24.0) { vHeure -= 24.0; vJour++; }

    // --- 2. LUMINOSITÉ SIMULÉE (Mathématique) ---
    int lum = 0;
    if (vHeure > 6.0 && vHeure < 20.0) {
      // Courbe en cloche pour le jour
      float angle = mapFloat(vHeure, 6.0, 20.0, 0, 3.14159);
      lum = sin(angle) * 100; // 0 à 100%
    }
    // Simulation : S'il pleut fort (capteur réel), on baisse la lumière virtuelle
    int valPluie = analogRead(pinPluie); 
    if(valPluie < 400) lum = lum / 2; // Il fait sombre s'il pleut

    // --- 3. CAPTEURS RÉELS (DHT11 + Pluie) ---
    float h = dht.readHumidity();
    float t = dht.readTemperature();
    
    // Sécurité si le capteur est débranché
    if (isnan(h) || isnan(t)) { h = 50.0; t = 20.0; }

    // --- 4. ENVOI TRAME (Format CDC) ---
    envoieTrame(t, h, lum, valPluie);
  }
}

void envoieTrame(float t, float h, int l, int p) {
  Serial.print("<TEMP:"); Serial.print(t, 1);
  Serial.print(";HUM:"); Serial.print((int)h);
  Serial.print(";LUM:"); Serial.print(l);
  Serial.print(";RAIN:"); Serial.print(p);
  Serial.print(";DATE:");
  Serial.print(vAnnee); Serial.print("-"); Serial.print(vMois); Serial.print("-"); Serial.print(vJour);
  Serial.print(" ");
  
  int hh = (int)vHeure;
  int mm = (int)((vHeure - hh) * 60);
  int ss = (int)((((vHeure - hh) * 60) - mm) * 60);
  
  if(hh<10) Serial.print("0"); Serial.print(hh); Serial.print(":");
  if(mm<10) Serial.print("0"); Serial.print(mm); Serial.print(":");
  if(ss<10) Serial.print("0"); Serial.print(ss);
  Serial.println(">");
}

float mapFloat(float x, float in_min, float in_max, float out_min, float out_max) {
  return (x - in_min) * (out_max - out_min) / (in_max - in_min) + out_min;
}