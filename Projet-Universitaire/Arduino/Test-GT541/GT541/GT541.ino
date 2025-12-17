// Déclaration du pin
const int pinLuminosite = 4; 
int valeurLue = 0;

void setup() {
  Serial.begin(9600); // Initialisation de la liaison série
}

void loop() {
  // Lecture de la valeur analogique (entre 0 et 1023)
  valeurLue = digitalRead(pinLuminosite);

  // Affichage pour le debug
  Serial.print("Valeur Brute GT541 : ");
  Serial.println(valeurLue);

  // Petite pause pour ne pas saturer le moniteur série
  delay(500); 
}