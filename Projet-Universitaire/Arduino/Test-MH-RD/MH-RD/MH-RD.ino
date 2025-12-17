const int pinPluie = A1; // On utilise l'entrée analogique A1

void setup() {
  Serial.begin(9600);
}

void loop() {
  int valeurPluie = analogRead(pinPluie);
  
  Serial.print("Valeur Brute Pluie : ");
  Serial.print(valeurPluie);
  
  // Interprétation rapide pour t'aider
  if(valeurPluie > 900) {
    Serial.println(" -> (Sec / Pas de pluie)");
  } else if(valeurPluie > 500) {
    Serial.println(" -> (Pluie faible / Gouttes)");
  } else {
    Serial.println(" -> (Averse / Inondation !)");
  }
  
  delay(500);
}