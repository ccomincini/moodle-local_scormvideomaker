# Istruzioni per il Test con Logging Migliorato

## Modifiche Applicate

Ho aggiunto logging dettagliato in tutto il codice per tracciare ogni fase della creazione SCORM:

### Funzioni con logging:
1. **scorm_creator::create_scorm_activity()** - Punto di ingresso principale
2. **scorm_creator::create_scorm_module()** - Creazione del modulo corso
3. **scorm_creator::upload_scorm_package()** - Upload e parsing del pacchetto
4. **scorm_package_generator::generate_scorm_package()** - Generazione del pacchetto

### Messaggi di log aggiunti:
- `[SCORM Creator]` - Operazioni principali del creator
- `[create_scorm_module]` - Creazione del modulo
- `[upload_scorm_package]` - Upload del pacchetto
- `[SCORM Generator]` - Generazione del pacchetto
- `[... ERROR]` - Tutti gli errori

## Come Vedere i Log

### Opzione 1: Abilitare il debugging in Moodle
1. Vai in **Site administration > Development > Debugging**
2. Imposta:
   - **Debug messages**: DEVELOPER
   - **Display debug messages**: Yes
3. I log appariranno direttamente nella pagina

### Opzione 2: Vedere i log nel terminale
Se esegui Moodle da CLI o hai accesso ai log del server:

```bash
# Per Apache
tail -f /var/log/apache2/error.log

# Per PHP-FPM
tail -f /var/log/php-fpm/error.log

# Log di Moodle (se configurato)
tail -f /path/to/moodledata/error.log
```

### Opzione 3: Controllare i log di Moodle tramite DB
```sql
SELECT * FROM mdl_logstore_standard_log 
WHERE component = 'local_scormvideomaker' 
ORDER BY timecreated DESC 
LIMIT 50;
```

## Test della Versione 1.0.2

Dopo aver fatto il test, dovrai vedere output simile a questo:

```
[SCORM Creator] Starting SCORM activity creation...
[SCORM Creator] Course validated: Nome Corso
[SCORM Generator] Starting package generation
[SCORM Generator] Video type: vimeo
[SCORM Generator] Template path: /path/to/templates/vimeo
[SCORM Generator] Work directory created: /tmp/scormvideomaker/scorm_xxxxx
[SCORM Generator] Copying template...
[SCORM Generator] Template copied successfully
[SCORM Generator] Replacing variables...
[SCORM Generator] Variables replaced
[SCORM Generator] Creating ZIP file...
[SCORM Generator] ZIP file created: /tmp/scormvideomaker/scorm_xxxxx.zip
[SCORM Generator] Work directory cleaned up
[SCORM Creator] Package generated: /tmp/scormvideomaker/scorm_xxxxx.zip (12345 bytes)
[SCORM Creator] Creating SCORM module in course...
[create_scorm_module] Starting module creation
[create_scorm_module] Course: 2, Section: 0
[create_scorm_module] Section found with ID: 10
[create_scorm_module] Creating course module record...
[create_scorm_module] SCORM module ID: 15
[create_scorm_module] Course module created with ID: 123
[create_scorm_module] SCORM instance created with ID: 45
[create_scorm_module] Course module updated with SCORM instance
[create_scorm_module] Uploading SCORM package...
[upload_scorm_package] Starting upload - SCORM ID: 45, CM ID: 123
[upload_scorm_package] ZIP file: /tmp/scormvideomaker/scorm_xxxxx.zip (12345 bytes)
[upload_scorm_package] Context ID: 234
[upload_scorm_package] Existing package files deleted
[upload_scorm_package] Creating file in storage...
[upload_scorm_package] File stored successfully, hash: abc123...
[upload_scorm_package] SCORM record retrieved
[upload_scorm_package] SCORM properties set for parsing
[upload_scorm_package] Starting SCORM package parsing...
[upload_scorm_package] SCORM package parsed successfully
[upload_scorm_package] SCORM record updated with parsed data
[create_scorm_module] SCORM package uploaded successfully
[SCORM Creator] SCORM activity created successfully with ID: 45
[SCORM Creator] Temporary file cleaned up
```

## Cosa Cercare Quando Ottieni l'Errore

Se ottieni ancora "Impossibile creare l'attività SCORM nel corso", cerca quale delle fasi sopra **NON** appare nei log. Questo ti dirà esattamente dove si ferma il processo:

- Se non vedi `[SCORM Generator]` → problema nel generatore
- Se non vedi `[create_scorm_module]` → problema prima della creazione modulo
- Se non vedi `[upload_scorm_package]` → problema nell'upload
- Se vedi `[... ERROR]` → leggi il messaggio di errore dettagliato

## Prossimi Passi

1. Abilita il debugging in Moodle
2. Esegui il test di creazione SCORM
3. Copia qui TUTTI i log che vedi (compresi gli errori)
4. Sarò in grado di dirti esattamente dove si blocca il processo
