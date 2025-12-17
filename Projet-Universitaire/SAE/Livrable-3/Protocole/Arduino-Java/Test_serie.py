import serial
import time

# --- CONFIGURATION ---
PORT_COM = 'COM10'  # Vérifie ton port !
BAUD_RATE = 9600

# Ouverture du port avec sécurité
try:
    ser = serial.Serial(PORT_COM, BAUD_RATE, timeout=1)
    time.sleep(2) # On laisse le temps à l'Arduino de rebooter
except serial.SerialException:
    print(f"Erreur : Impossible d'ouvrir le port {PORT_COM}")
    exit()

# --- 1. MENU DE SELECTION ---
print("\n--- SIMULATEUR SERVEUR CONDITRACKER ---")
print("1. Tester le Mode PUSH (Envoi automatique)")
print("2. Tester le Mode PULL (Requête manuelle)")
print("3. Tester le Mode NACK (Refus de connexion)")
print("---------------------------------------")

choix = input("Votre choix (1-3) : ")

reponse_config = ""
mode_actuel = ""

if choix == "1":
    freq = input("Entrez la fréquence en ms (ex: 2000) : ")
    # Trame : <ACK;ID:01;MODE:Push;FREQ:2000>
    reponse_config = f"<ACK;ID:01;MODE:Push;FREQ:{freq}>"
    mode_actuel = "PUSH"

elif choix == "2":
    # Trame : <ACK;ID:01;MODE:Pull>
    reponse_config = "<ACK;ID:01;MODE:Pull>"
    mode_actuel = "PULL"

elif choix == "3":
    # Trame : <NACK;ID:01>
    reponse_config = "<NACK;ID:01>"
    mode_actuel = "NACK"

else:
    print("Choix invalide.")
    ser.close()
    exit()

print(f"\n[INFO] Démarrage du test en mode {mode_actuel}...")
print("[INFO] En attente de la station (SYN)...")

# --- 2. PHASE DE CONNEXION (HANDSHAKE) ---
est_connecte = False

# On boucle tant qu'on n'a pas configuré l'Arduino
# (Sauf en NACK où on reste indéfiniment dans cette logique)
while not est_connecte:
    if ser.in_waiting > 0:
        try:
            line = ser.readline().decode('utf-8').strip()
            if line:
                print(f"Reçu : {line}")
                
                if "SYN" in line:
                    print(f" -> [AUTO] Envoi réponse {mode_actuel}...")
                    ser.write(reponse_config.encode('utf-8'))
                    
                    # Si on n'est pas en NACK, on considère que c'est connecté
                    if mode_actuel != "NACK":
                        est_connecte = True 
                        
        except UnicodeDecodeError:
            pass

print(f"\n>>> CONFIGURATION REUSSIE EN MODE {mode_actuel} <<<")

# --- 3. PHASE DE FONCTIONNEMENT ---

try:
    # CAS A : MODE PULL (Interactif / Bloquant)
    if mode_actuel == "PULL":
        print(">>> Appuie sur ENTREE pour demander une mesure <<<")
        while True:
            # Le script s'arrête ici et attend que tu tapes Entrée
            input("Commande > [Entrée]")
            
            print(" -> Envoi <REQ;ID:01>...")
            ser.write("<REQ;ID:01>".encode('utf-8'))
            
            # On laisse un instant à l'Arduino pour répondre
            time.sleep(0.1)
            
            # On lit tout ce qui est arrivé
            while ser.in_waiting > 0:
                line = ser.readline().decode('utf-8').strip()
                print(f"    RÉPONSE : {line}")

    # CAS B : MODE PUSH (Passif / Lecture seule)
    else:
        print(">>> Lecture des données entrantes (Ctrl+C pour arrêter) <<<")
        while True:
            if ser.in_waiting > 0:
                line = ser.readline().decode('utf-8').strip()
                if line:
                    print(f"Reçu : {line}")

except KeyboardInterrupt:
    print("\nArrêt du test.")
    ser.close()
    