# SCORM Video Maker - Guida per Amministratori

## Indice

1. [Introduzione](#introduzione)
2. [Installazione](#installazione)
3. [Configurazione](#configurazione)
4. [Requisiti di Sistema](#requisiti-di-sistema)
5. [Manutenzione e Monitoraggio](#manutenzione-e-monitoraggio)
6. [Considerazioni sulla Sicurezza](#considerazioni-sulla-sicurezza)
7. [Ottimizzazione delle Prestazioni](#ottimizzazione-delle-prestazioni)

---

## Introduzione

SCORM Video Maker è un plugin locale Moodle che consente a docenti e creatori di contenuti di generare pacchetti SCORM 1.2 contenenti contenuti video da varie fonti (Vimeo, YouTube, stream HLS).

Questa guida fornisce informazioni complete per gli amministratori Moodle sull'installazione, configurazione e manutenzione del plugin.

---

## Installazione

### Prerequisiti

Prima di installare il plugin, assicurati che la tua installazione Moodle soddisfi i seguenti requisiti:

- Moodle 4.1 o superiore
- PHP 7.4 o superiore
- Permessi di scrittura nella directory dati di Moodle
- Spazio su disco sufficiente per l'archiviazione dei pacchetti SCORM

### Metodi di Installazione

#### Metodo 1: Tramite Installatore Plugin Moodle (Consigliato)

1. Scarica il file ZIP del plugin
2. Accedi al tuo sito Moodle come amministratore
3. Naviga in **Amministrazione del sito** → **Plugin** → **Installa plugin**
4. Carica il file ZIP
5. Clicca su **"Installa plugin dal file ZIP"**
6. Segui la procedura guidata di installazione sullo schermo
7. Clicca su **"Continua"** per completare l'installazione

#### Metodo 2: Installazione Manuale

1. Scarica ed estrai i file del plugin
2. Carica la cartella `scormvideomaker` nella tua installazione Moodle:
   ```
   /percorso/a/moodle/local/scormvideomaker/
   ```
3. Assicurati dei permessi file corretti:
   ```bash
   chmod -R 755 /percorso/a/moodle/local/scormvideomaker
   ```
4. Accedi come amministratore e naviga in **Amministrazione del sito** → **Notifiche**
5. Segui le istruzioni di installazione

### Operazioni Post-Installazione

1. Verifica che il plugin appaia in **Amministrazione del sito** → **Plugin** → **Plugin locali**
2. Configura le impostazioni predefinite (vedi [Configurazione](#configurazione))
3. Testa il plugin creando un pacchetto SCORM di esempio
4. Assegna permessi appropriati ai docenti (vedi [Configurazione](#configurazione))

---

## Configurazione

### Impostazioni del Plugin

Accedi alle impostazioni del plugin in: **Amministrazione del sito** → **Plugin** → **Plugin locali** → **SCORM Video Maker**

#### Impostazioni Disponibili

**Tipo di Video Predefinito**
- Opzioni: Vimeo, YouTube, HLS
- Predefinito: Vimeo
- Descrizione: Pre-seleziona il tipo di video nel modulo di creazione

**Posizione Archiviazione**
- Predefinito: Directory dati Moodle in `/scormvideomaker/`
- Descrizione: Dove vengono archiviati i pacchetti SCORM generati
- Nota: Deve avere permessi di scrittura

**Dimensione Massima Pacchetto**
- Predefinito: 100 MB
- Intervallo: 10 MB - 500 MB
- Descrizione: Dimensione massima consentita per i pacchetti SCORM generati

**Periodo di Pulizia Automatica**
- Predefinito: 30 giorni
- Intervallo: 7 giorni - 365 giorni
- Descrizione: Elimina automaticamente i pacchetti più vecchi dei giorni specificati

### Permessi e Capacità

Il plugin definisce le seguenti capacità:

**`local/scormvideomaker:create`**
- Descrizione: Creare nuovi pacchetti SCORM
- Ruoli predefiniti: Docente, Manager
- Rischio: RISK_SPAM

**`local/scormvideomaker:delete`**
- Descrizione: Eliminare pacchetti SCORM esistenti
- Ruoli predefiniti: Docente, Manager
- Rischio: RISK_DATALOSS

**`local/scormvideomaker:view`**
- Descrizione: Visualizzare ed elencare i pacchetti SCORM
- Ruoli predefiniti: Docente, Manager, Studente
- Rischio: Nessuno

#### Personalizzare i Permessi

Per modificare i permessi:

1. Naviga in **Amministrazione del sito** → **Utenti** → **Permessi** → **Definisci ruoli**
2. Seleziona il ruolo che vuoi modificare
3. Cerca "scormvideomaker"
4. Regola i permessi secondo necessità
5. Salva le modifiche

---

## Requisiti di Sistema

### Requisiti Server

**Sistema Operativo:**
- Linux (consigliato)
- Windows Server
- macOS (solo per sviluppo)

**Server Web:**
- Apache 2.4+ con mod_rewrite
- Nginx 1.18+

**Requisiti PHP:**
- Versione: 7.4 - 8.2
- Estensioni richieste:
  - zip
  - dom
  - mbstring
  - curl
  - json
  - xml

**Database:**
- MySQL 5.7+ o MariaDB 10.2+
- PostgreSQL 10+

**Spazio su Disco:**
- Minimo 500 MB per plugin e pacchetti generati
- Consigliati 5 GB+ a seconda dell'utilizzo

### Requisiti di Rete

**Connessioni in Uscita:**
- Accesso alle API Vimeo (https://vimeo.com)
- Accesso a YouTube (https://www.youtube.com)
- Accesso ai server di stream HLS (se si usa HLS)

**Regole Firewall:**
- Consentire HTTPS (443) in uscita per l'accesso alle fonti video
- Consentire HTTP (80) in uscita se necessario

### Compatibilità Browser

L'interfaccia del plugin supporta:
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

I pacchetti SCORM creati dal plugin sono compatibili con:
- Tutti i browser moderni che supportano video HTML5
- Browser mobile (iOS Safari, Chrome Mobile)

---

## Manutenzione e Monitoraggio

### Attività di Manutenzione Regolare

#### Controlli Giornalieri

- Monitorare l'utilizzo dello spazio su disco
- Rivedere i log degli errori per problemi relativi al plugin
- Verificare tentativi falliti di generazione pacchetti

#### Attività Settimanali

- Rivedere e pulire i pacchetti vecchi
- Verificare che le procedure di backup includano i dati del plugin
- Verificare aggiornamenti del plugin

#### Attività Mensili

- Analizzare le statistiche di utilizzo
- Rivedere le tendenze di consumo dello storage
- Aggiornare la documentazione se necessario

### Monitoraggio

#### File di Log

Le attività del plugin sono registrate in:
- Log standard di Moodle (visualizzabili in **Amministrazione del sito** → **Report** → **Log**)
- Log errori PHP (controlla la posizione del log errori del tuo server)

#### Eventi Chiave da Monitorare

- Successo/fallimento creazione pacchetti
- Attività di download
- Eventi di eliminazione
- Avvisi soglia archiviazione

#### Tabelle Database

Il plugin utilizza le seguenti tabelle:
- `mdl_local_scormvideomaker_packages` - Memorizza i metadati dei pacchetti
- `mdl_files` - Memorizza i file effettivi dei pacchetti SCORM (API file Moodle)

#### Monitoraggio dello Storage

Verifica l'utilizzo dello storage con:
```sql
SELECT COUNT(*) as pacchetti_totali, 
       SUM(filesize) as dimensione_totale 
FROM mdl_files 
WHERE component = 'local_scormvideomaker';
```

### Backup e Ripristino

#### Cosa Fare il Backup

1. **File del Plugin:**
   ```
   /percorso/a/moodle/local/scormvideomaker/
   ```

2. **Tabelle Database:**
   - `mdl_local_scormvideomaker_packages`

3. **File SCORM Generati:**
   - Directory Moodledata: `/scormvideomaker/`

#### Strategia di Backup

- Includere i dati del plugin nei backup regolari di Moodle
- Considerare backup separati per pacchetti SCORM di grandi dimensioni
- Testare periodicamente le procedure di ripristino

#### Procedura di Ripristino

1. Ripristinare i file del plugin nella directory corretta
2. Ripristinare le tabelle database
3. Ripristinare i file dei pacchetti SCORM
4. Eseguire i controlli di aggiornamento Moodle
5. Verificare la funzionalità del plugin

---

## Considerazioni sulla Sicurezza

### Generazione ZIP in Memoria

**Miglioramento Sicurezza Versione 1.0.2+:**

Il plugin implementa la generazione ZIP in memoria per prevenire vulnerabilità di sicurezza:

- **Nessun file temporaneo su disco**: Tutto il contenuto ZIP è generato e gestito interamente in memoria
- **Nessuna directory temporanea accessibile**: Elimina il rischio di accesso non autorizzato a file temporanei
- **Gestione file sicura**: Utilizza `create_file_from_string()` di Moodle per trasferimento diretto memoria-storage
- **Pulizia automatica**: La memoria viene liberata immediatamente dopo la generazione del pacchetto

**Implementazione Tecnica:**
- `ZipArchive` di PHP con archiviazione temporanea in memoria
- API File Moodle per archiviazione sicura
- Nessun file o directory residua che richiede pulizia

### Controllo Accessi

**Permessi basati su capacità:**
- Solo gli utenti con capacità appropriate possono creare/eliminare pacchetti
- I permessi di visualizzazione possono essere limitati per ruolo

**Accesso ai file:**
- I pacchetti SCORM sono archiviati usando l'API file di Moodle
- Accesso controllato tramite il sistema di autenticazione Moodle
- Nessun accesso URL diretto ai file dei pacchetti

### Privacy dei Dati

**Dati utente:**
- Il plugin memorizza dati utente minimi (ID creatore, timestamp)
- Nessun contenuto video è archiviato localmente (solo riferimenti/URL)
- I metadati dei pacchetti non includono informazioni personali

**Conformità GDPR:**
- I pacchetti dell'utente possono essere eliminati con l'eliminazione dell'account
- Nessun tracciamento della visualizzazione video nei pacchetti generati
- Conservazione dati configurabile tramite impostazioni di pulizia automatica

### Sicurezza Fonti Video

**Contenuto esterno:**
- I video sono incorporati da fonti esterne (non ospitati localmente)
- L'amministratore dovrebbe assicurarsi che le fonti video rispettino le politiche sulla privacy
- Considerare regole firewall per i domini video consentiti

**Validazione URL:**
- Il plugin valida gli URL delle fonti video prima della creazione del pacchetto
- Previene l'iniezione di contenuti dannosi
- Supporta solo piattaforme video sicure conosciute

### Aggiornamenti di Sicurezza Regolari

- Mantenere aggiornato il core Moodle
- Monitorare il repository del plugin per patch di sicurezza
- Iscriversi agli annunci di sicurezza

---

## Ottimizzazione delle Prestazioni

### Ottimizzazione Server

**Configurazione PHP:**

Impostazioni php.ini consigliate:
```ini
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 100M
post_max_size = 100M
```

**Server Web:**

Per Apache, abilitare:
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE application/zip
</IfModule>
```

Per Nginx:
```nginx
gzip on;
gzip_types application/zip;
```

### Ottimizzazione Database

**Indicizzazione:**

Il plugin crea automaticamente indici sulle colonne frequentemente interrogate:
- `userid` (per filtrare i pacchetti per utente)
- `timecreated` (per ordinare per data)

**Ottimizzazione Query:**

- Il plugin utilizza chiamate efficienti all'API DB Moodle
- Paginazione implementata per liste di pacchetti grandi
- Query database minimali per caricamento pagina

### Ottimizzazione Storage

**Strategia di Pulizia:**
- Abilitare la pulizia automatica per pacchetti non utilizzati
- Impostare periodi di conservazione ragionevoli
- Monitorare regolarmente l'utilizzo dello storage

**Dimensione Pacchetti:**
- I pacchetti SCORM sono minimali (tipicamente 5-50 KB)
- Nessun contenuto video archiviato (solo wrapper HTML/JavaScript)
- Impatto trascurabile sullo storage

### Caching

**Cache Moodle:**
- Il plugin rispetta il sistema cache di Moodle
- Lista pacchetti in cache per sessione utente
- Pulire la cache dopo operazioni massive

### Bilanciamento del Carico

Per siti ad alto traffico:
- Il plugin è stateless e funziona con server bilanciati
- Lo storage dei file dovrebbe essere su storage di rete condiviso (NFS, ecc.)
- Il database deve essere accessibile da tutti i nodi web

---

## Aggiornamento

### Processo di Aggiornamento

1. **Backup:** Fare sempre backup prima dell'aggiornamento
2. **Download:** Ottenere l'ultima versione del plugin
3. **Sostituire file:** Sovrascrivere i file del plugin esistenti
4. **Eseguire aggiornamento:** Navigare in **Amministrazione del sito** → **Notifiche**
5. **Testare:** Verificare la funzionalità del plugin dopo l'aggiornamento

### Note Specifiche per Versione

**Aggiornamento a 1.0.2 o successive:**
- Implementata generazione ZIP in memoria
- Nessuna modifica configurazione richiesta
- Rimozione automatica vecchi file temporanei (se esistono)

---

## Risoluzione Problemi

Per informazioni dettagliate sulla risoluzione dei problemi, consulta la [Guida alla Risoluzione dei Problemi](TROUBLESHOOTING_IT.md).

Le attività comuni dell'amministratore includono:
- Verificare i permessi del server
- Verificare le estensioni PHP
- Rivedere i log degli errori
- Testare l'accesso ai video esterni

---

## Supporto e Risorse

**Repository Plugin:**
https://github.com/yourusername/moodle-local_scormvideomaker

**Tracker Problemi:**
Segnala bug e richieste funzionalità su GitHub Issues

**Documentazione:**
- [Guida Docenti](TEACHER_GUIDE_IT.md)
- [Guida Risoluzione Problemi](TROUBLESHOOTING_IT.md)
- [Changelog](CHANGELOG.md)

**Supporto Community:**
- Forum Moodle
- Bacheca discussione plugin

---

## Licenza

Questo plugin è distribuito con licenza GNU GPL v3 o successiva.

---

*Ultimo aggiornamento: Novembre 2024*
*Versione plugin: 1.0.2*
