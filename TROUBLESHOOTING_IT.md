# SCORM Video Maker - Guida alla Risoluzione dei Problemi

Questa guida fornisce soluzioni ai problemi e alle difficoltà comuni che potresti incontrare utilizzando il plugin SCORM Video Maker.

---

## Indice

1. [Problemi di Installazione](#problemi-di-installazione)
2. [Problemi di Creazione Pacchetto](#problemi-di-creazione-pacchetto)
3. [Problemi di Riproduzione Video](#problemi-di-riproduzione-video)
4. [Errori di Permesso](#errori-di-permesso)
5. [Problemi di Prestazioni](#problemi-di-prestazioni)
6. [Problemi di Download](#problemi-di-download)
7. [Riferimento Messaggi di Errore](#riferimento-messaggi-di-errore)
8. [Ottenere Ulteriore Aiuto](#ottenere-ulteriore-aiuto)

---

## Problemi di Installazione

### Plugin Non Visibile Dopo l'Installazione

**Sintomi:**
- Plugin installato ma non visibile nel menu amministratore
- Nessuna opzione SCORM Video Maker sotto Plugin locali

**Soluzioni:**

1. **Pulire la cache di Moodle:**
   - Naviga in **Amministrazione del sito** → **Sviluppo** → **Elimina tutte le cache**
   - Clicca "Elimina tutte le cache"

2. **Verificare la posizione di installazione:**
   - Il plugin deve essere in: `/percorso/a/moodle/local/scormvideomaker/`
   - Controlla il nome corretto della cartella (deve essere esattamente `scormvideomaker`)

3. **Controllare i permessi dei file:**
   ```bash
   chmod -R 755 /percorso/a/moodle/local/scormvideomaker
   chown -R www-data:www-data /percorso/a/moodle/local/scormvideomaker
   ```

4. **Eseguire lo script di aggiornamento:**
   - Visita: **Amministrazione del sito** → **Notifiche**
   - Completa eventuali aggiornamenti in sospeso

### Errori di Installazione Database

**Sintomi:**
- Errore durante l'installazione del plugin relativo alle tabelle database
- Errore "La tabella esiste già"

**Soluzioni:**

1. **Controllare i permessi utente database:**
   - Assicurati che l'utente database Moodle abbia i privilegi CREATE TABLE

2. **Rimuovere manualmente le tabelle in conflitto:**
   ```sql
   DROP TABLE IF EXISTS mdl_local_scormvideomaker_packages;
   ```
   Quindi reinstalla il plugin

3. **Controllare il prefisso database:**
   - Verifica che il tuo Moodle usi il prefisso tabella corretto (predefinito: `mdl_`)

### Compatibilità Versione PHP

**Sintomi:**
- Schermata bianca dopo l'installazione
- Errori di sintassi PHP nei log

**Soluzioni:**

1. **Verificare la versione PHP:**
   ```bash
   php -v
   ```
   Deve essere PHP 7.4 o superiore

2. **Controllare le estensioni PHP richieste:**
   ```bash
   php -m | grep -E 'zip|dom|mbstring|curl|json|xml'
   ```

3. **Installare le estensioni mancanti:**
   ```bash
   # Per Ubuntu/Debian:
   sudo apt-get install php-zip php-xml php-mbstring php-curl
   
   # Per CentOS/RHEL:
   sudo yum install php-zip php-xml php-mbstring php-curl
   ```

---

## Problemi di Creazione Pacchetto

### Errore "Impossibile Creare il Pacchetto"

**Sintomi:**
- Messaggio di errore quando si cerca di creare un pacchetto SCORM
- Il modulo di creazione pacchetto si invia ma fallisce

**Cause Comuni e Soluzioni:**

1. **Sorgente video non valida:**
   - **Vimeo:** Assicurati di inserire solo l'ID video (solo numeri), non l'URL completo
     - ✅ Corretto: `123456789`
     - ❌ Sbagliato: `https://vimeo.com/123456789`
   
   - **YouTube:** Assicurati di inserire solo l'ID video, non l'URL completo
     - ✅ Corretto: `dQw4w9WgXcQ`
     - ❌ Sbagliato: `https://www.youtube.com/watch?v=dQw4w9WgXcQ`
   
   - **HLS:** Deve essere URL completo che termina con `.m3u8`
     - ✅ Corretto: `https://example.com/stream/playlist.m3u8`
     - ❌ Sbagliato: `example.com/stream` (manca protocollo ed estensione file)

2. **Problemi di spazio su disco:**
   - Controlla lo spazio disponibile:
     ```bash
     df -h /percorso/a/moodledata
     ```
   - Libera spazio se necessario
   - Aumenta la quota disco se su hosting condiviso

3. **Limite memoria PHP:**
   - Controlla il limite attuale: `php -i | grep memory_limit`
   - Aumenta in php.ini:
     ```ini
     memory_limit = 256M
     ```
   - Riavvia il server web dopo le modifiche

4. **Timeout di esecuzione:**
   - Aumenta in php.ini:
     ```ini
     max_execution_time = 300
     ```

### Video Non Accessibile

**Sintomi:**
- Errore "Sorgente video non accessibile"
- Il pacchetto si crea ma il video non si riproduce

**Soluzioni:**

1. **Verificare che il video esista e sia pubblico:**
   - Apri l'URL del video direttamente in un browser
   - Assicurati che il video non sia privato o eliminato
   - Controlla le impostazioni di privacy/condivisione sulla piattaforma video

2. **Controllare la connettività di rete:**
   - Testa dalla riga di comando del server:
     ```bash
     curl -I https://vimeo.com/123456789
     curl -I https://www.youtube.com/watch?v=VIDEO_ID
     curl -I https://tuo-server-hls.com/stream.m3u8
     ```

3. **Problemi firewall/proxy:**
   - Assicurati che il server possa accedere alle piattaforme video esterne
   - Controlla che le regole firewall consentano HTTPS in uscita
   - Configura il proxy se necessario nella configurazione Moodle

4. **Problemi certificato SSL:**
   - Aggiorna i certificati CA:
     ```bash
     sudo update-ca-certificates
     ```

### Fallimento Generazione ZIP

**Sintomi:**
- Errore nella creazione del file ZIP
- Download parziale o corrotto

**Soluzioni:**

1. **Controllare l'estensione ZIP PHP:**
   ```bash
   php -m | grep zip
   ```
   Installa se mancante:
   ```bash
   sudo apt-get install php-zip  # Ubuntu/Debian
   sudo yum install php-zip      # CentOS/RHEL
   ```

2. **Verificare i permessi dei file:**
   ```bash
   # Controlla i permessi directory dati Moodle
   ls -la /percorso/a/moodledata/scormvideomaker/
   
   # Imposta i permessi corretti
   chmod -R 755 /percorso/a/moodledata/scormvideomaker/
   chown -R www-data:www-data /percorso/a/moodledata/scormvideomaker/
   ```

3. **Controllare la directory temporanea PHP:**
   - Verifica che la directory tmp sia scrivibile
   - Controlla l'impostazione php.ini: `sys_temp_dir`

---

## Problemi di Riproduzione Video

### Video Non Si Riproduce nel Pacchetto SCORM

**Sintomi:**
- Il pacchetto SCORM si carica con successo nel corso
- Il lettore video appare ma il video non si riproduce
- Schermata nera o icona di caricamento

**Soluzioni:**

1. **Compatibilità browser:**
   - Testa in browser diversi (Chrome, Firefox, Safari, Edge)
   - Aggiorna il browser all'ultima versione
   - Pulisci cache e cookie del browser

2. **Impostazioni embedding piattaforma video:**
   
   **Per Vimeo:**
   - Accedi a Vimeo
   - Vai nelle impostazioni video
   - Abilita "Consenti incorporamento video su altri siti"
   - Imposta privacy su "Pubblico" o "Chiunque abbia il link"

   **Per YouTube:**
   - Controlla che il video non abbia restrizioni d'età
   - Assicurati che l'incorporamento sia consentito (non disabilitato dall'uploader)
   - Verifica che il video sia pubblico o non in elenco, non privato

3. **Content Security Policy (CSP):**
   - Controlla se il tuo Moodle ha intestazioni CSP rigide
   - Potrebbe essere necessario aggiungere i domini video alla whitelist nella configurazione Moodle:
     ```php
     // In config.php
     $CFG->contentSecurityPolicy = "default-src 'self'; frame-src 'self' https://player.vimeo.com https://www.youtube.com;";
     ```

4. **HTTPS/Contenuto misto:**
   - Assicurati che il sito Moodle usi HTTPS
   - Anche le sorgenti video devono essere HTTPS
   - Controlla la console del browser per avvisi contenuto misto

### Il Video Si Carica Lentamente

**Sintomi:**
- Lunghi tempi di buffering
- Riproduzione intermittente

**Soluzioni:**

1. **Larghezza di banda rete:**
   - Testa la velocità della connessione internet
   - Usa video di qualità inferiore se disponibile
   - Considera HLS per streaming adattivo

2. **Posizione server piattaforma video:**
   - Scegli piattaforma video con CDN vicina ai tuoi utenti
   - Considera di duplicare il contenuto su più piattaforme

3. **Risorse server Moodle:**
   - Controlla l'utilizzo CPU e memoria del server
   - Monitora il traffico di rete
   - Considera di aggiornare le risorse server

---

## Errori di Permesso

### "Non Hai il Permesso di Creare Pacchetti"

**Sintomi:**
- Impossibile accedere al modulo di creazione pacchetto
- Messaggio di errore su permessi insufficienti

**Soluzioni:**

1. **Controllare il ruolo utente:**
   - Verifica di avere assegnato un ruolo docente o manager
   - Contatta l'amministratore del sito se necessario

2. **Assegnazione capacità:**
   - L'amministratore dovrebbe controllare: **Amministrazione del sito** → **Utenti** → **Permessi** → **Definisci ruoli**
   - Assicurati che il tuo ruolo abbia la capacità `local/scormvideomaker:create`

3. **Permessi di contesto:**
   - I permessi possono essere sovrascritti a livello di categoria o corso
   - Controlla le sovrascritture permessi specifiche del contesto

### Impossibile Eliminare il Pacchetto

**Sintomi:**
- Pulsante elimina non visibile
- Errore nel tentativo di eliminare

**Soluzioni:**

1. **Controllare la capacità di eliminazione:**
   - Devi avere la capacità `local/scormvideomaker:delete`
   - Contatta l'amministratore per concedere il permesso

2. **Proprietà del pacchetto:**
   - Alcune configurazioni consentono di eliminare solo i propri pacchetti
   - L'amministratore potrebbe dover eliminare per tuo conto

---

## Problemi di Prestazioni

### Creazione Pacchetto Lenta

**Sintomi:**
- La creazione del pacchetto richiede molto tempo
- Errori di timeout durante la creazione

**Soluzioni:**

1. **Risorse server:**
   - Controlla l'utilizzo CPU e memoria durante la creazione
   - Chiudi applicazioni non necessarie sul server
   - Considera hosting dedicato per Moodle

2. **Configurazione PHP:**
   ```ini
   max_execution_time = 300
   memory_limit = 256M
   ```

3. **Latenza di rete:**
   - Testa la connessione alle piattaforme video
   - Usa `ping` e `traceroute` per diagnosticare
   - Considera caching locale se problemi ripetuti

### Il Plugin Rallenta Moodle

**Sintomi:**
- Lentezza generale del sito
- Caricamento pagine lento nell'area amministratore

**Soluzioni:**

1. **Controllare dimensione database:**
   ```sql
   SELECT COUNT(*) FROM mdl_local_scormvideomaker_packages;
   ```
   - Elimina vecchi pacchetti non utilizzati
   - Abilita pulizia automatica nelle impostazioni

2. **Rivedere utilizzo storage:**
   ```bash
   du -sh /percorso/a/moodledata/scormvideomaker/
   ```
   - Pulisci vecchi file
   - Imposta periodi di conservazione ragionevoli

3. **Ottimizzare database:**
   ```sql
   OPTIMIZE TABLE mdl_local_scormvideomaker_packages;
   ```

---

## Problemi di Download

### Impossibile Scaricare il Pacchetto

**Sintomi:**
- Il pulsante download non funziona
- Il download inizia ma fallisce
- File ZIP corrotto

**Soluzioni:**

1. **Problemi browser:**
   - Prova un browser diverso
   - Disabilita temporaneamente le estensioni browser
   - Controlla le impostazioni download del browser

2. **Limiti dimensione file:**
   - Controlla impostazioni PHP:
     ```ini
     upload_max_filesize = 100M
     post_max_size = 100M
     ```
   - Aumenta i limiti se il pacchetto è grande

3. **Gestione file Moodle:**
   - Controlla impostazione $CFG->slasharguments in config.php
   - Prova alternare tra true e false
   - Testa con metodi diversi di gestione file

4. **Antivirus/Firewall:**
   - Disabilita temporaneamente l'antivirus
   - Controlla che il firewall non blocchi i download
   - Aggiungi il dominio Moodle alla whitelist se necessario

### Il Download è Corrotto

**Sintomi:**
- Il file ZIP non si estrae
- Errore nell'apertura del pacchetto scaricato
- La dimensione del file è 0 KB o chiaramente errata

**Soluzioni:**

1. **Riscarica il pacchetto:**
   - Prova a scaricare di nuovo
   - Pulisci la cache del browser prima di riprovare

2. **Controllare integrità file sorgente:**
   - L'amministratore dovrebbe verificare il pacchetto nella directory Moodledata
   - Testa l'estrazione sul server:
     ```bash
     unzip -t /percorso/a/pacchetto.zip
     ```

3. **Problemi storage:**
   - Controlla errori disco:
     ```bash
     sudo fsck /dev/sdXX
     ```
   - Verifica che il file system non sia pieno

---

## Riferimento Messaggi di Errore

### "Tipo di video non valido selezionato"

**Causa:** Parametro tipo video mancante o non valido

**Soluzione:** Assicurati di selezionare Vimeo, YouTube o HLS dal menu a discesa

### "La sorgente video non può essere vuota"

**Causa:** Nessun ID video o URL fornito

**Soluzione:** Compila il campo sorgente video con il valore appropriato

### "Impossibile generare il pacchetto SCORM"

**Causa:** Molteplici possibili cause (rete, permessi, risorse)

**Soluzioni:**
1. Controlla i log errori server: `/var/log/apache2/error.log` o `/var/log/nginx/error.log`
2. Controlla messaggi debug Moodle (abilita debugging nelle impostazioni)
3. Verifica che tutti i prerequisiti siano soddisfatti

### "Pacchetto non trovato"

**Causa:** Il pacchetto è stato eliminato o non esiste

**Soluzione:** Ricrea il pacchetto o verifica che non sia stato eliminato accidentalmente

### "Permessi insufficienti"

**Causa:** L'utente non ha le capacità richieste

**Soluzione:** Contatta l'amministratore per concedere i permessi appropriati

---

## Ottenere Ulteriore Aiuto

### Abilitare il Debugging

Per ottenere messaggi di errore più dettagliati:

1. Naviga in **Amministrazione del sito** → **Sviluppo** → **Debugging**
2. Imposta **Messaggi di debug** su "DEVELOPER"
3. Imposta **Visualizza messaggi di debug** su "Sì"
4. Prova a riprodurre il problema
5. Controlla i messaggi di errore dettagliati

**Ricorda di disattivare il debugging dopo la risoluzione dei problemi!**

### Controllare i File di Log

**Log Moodle:**
- **Amministrazione del sito** → **Report** → **Log**
- Filtra per utente e periodo temporale
- Cerca errori relativi a "scormvideomaker"

**Log server:**
```bash
# Apache
tail -f /var/log/apache2/error.log

# Nginx
tail -f /var/log/nginx/error.log

# PHP-FPM
tail -f /var/log/php-fpm/error.log
```

### Raccogliere Informazioni Diagnostiche

Quando segnali problemi, includi:

1. **Versione Moodle:**
   - **Amministrazione del sito** → **Notifiche** → Guarda in cima alla pagina

2. **Versione plugin:**
   - Controlla il file version.php o la lista plugin

3. **Versione PHP:**
   ```bash
   php -v
   ```

4. **Browser e OS:**
   - Specifica quale browser e versione
   - Sistema operativo (Windows, macOS, Linux)

5. **Messaggi di errore:**
   - Copia il testo esatto dell'errore
   - Includi eventuali codici errore

6. **Passaggi per riprodurre:**
   - Descrizione dettagliata di cosa hai fatto
   - Cosa ti aspettavi che succedesse
   - Cosa è successo realmente

### Risorse di Supporto

**GitHub Issues:**
- https://github.com/yourusername/moodle-local_scormvideomaker/issues
- Controlla problemi esistenti prima di crearne di nuovi
- Usa i template per le issue se forniti

**Forum Moodle:**
- Pubblica nei forum Moodle.org con tag [SCORM Video Maker]
- Includi versione e dettagli errore

**Documentazione Plugin:**
- [Guida Docenti](TEACHER_GUIDE_IT.md)
- [Guida Amministratori](ADMIN_GUIDE_IT.md)
- [README](README.md)
- [Changelog](CHANGELOG.md)

### Correzioni di Emergenza

**Se il plugin causa problemi al sito:**

1. **Disabilita temporaneamente il plugin:**
   ```sql
   UPDATE mdl_config_plugins 
   SET value = 0 
   WHERE plugin = 'local_scormvideomaker' 
   AND name = 'enabled';
   ```

2. **Rimuovi plugin (ultima risorsa):**
   ```bash
   # Fai backup prima!
   mv /percorso/a/moodle/local/scormvideomaker /percorso/a/backup/
   ```
   Quindi visita **Amministrazione del sito** → **Notifiche** per disinstallare

---

*Ultimo aggiornamento: Novembre 2024*
*Versione plugin: 1.0.2*
