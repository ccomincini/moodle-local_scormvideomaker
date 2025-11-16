# Changelog - SCORM Video Maker

## [1.0.3] - 2025-11-16

### Changed
- **IMPORTANTE**: Migrazione da `scorm_templates/` a `scorm_base/` come template principale
  - Ora usa la directory completa con VideoJS e tutte le funzionalità
  - Utilizza file `.template` per index.html, config.js e imsmanifest.xml
  - Sistema di sostituzione variabili migliorato
- Gestione MIME type corretta per HLS (`application/x-mpegURL`)
- Autoplay ora genera `true/false` invece di `0/1` per JavaScript
- Escape HTML corretto con `ENT_QUOTES` per tutti i campi

### Added
- Template files in `scorm_base/`:
  - `index.html.template`
  - `config.js.template`
  - `imsmanifest.xml.template`
- Funzione `process_template_files()` per gestione template

### Removed
- Dipendenza da `scorm_templates/` (vimeo/youtube/hls)
- Funzione `get_template_path()` non più necessaria

## [1.0.2] - 2025-11-16

### Fixed
- **CRITICAL BUG FIX**: Aggiunto file mancante `scorm_runtime.js` nei template HLS e YouTube
  - Il parsing SCORM falliva perché i manifest dichiaravano il file ma non era presente
  - Questo causava l'errore "Impossibile creare l'attività SCORM nel corso"
  
### Added
- Logging dettagliato con `mtrace()` e `debugging()` in tutte le funzioni critiche:
  - `scorm_creator::create_scorm_activity()`
  - `scorm_creator::create_scorm_module()`
  - `scorm_creator::upload_scorm_package()`
  - `scorm_package_generator::generate_scorm_package()`
- Prefissi di log chiari per identificare facilmente la fonte dei messaggi:
  - `[SCORM Creator]` - Operazioni principali
  - `[create_scorm_module]` - Creazione modulo
  - `[upload_scorm_package]` - Upload pacchetto
  - `[SCORM Generator]` - Generazione pacchetto
  - `[... ERROR]` - Tutti gli errori
- File `TEST_LOG_INSTRUCTIONS.md` con istruzioni per il debugging

### Changed
- Gestione errori migliorata con messaggi dettagliati
- Stack trace completo in caso di eccezioni

## [1.0.1] - 2025-11-15

### Added
- Versione iniziale del plugin
- Supporto per video Vimeo, YouTube e HLS
- Generazione pacchetti SCORM 1.2
- Template personalizzabili per ogni tipo di video
- Gestione completamento video
- Opzioni seekbar (locked, free, backward)

### Known Issues (v1.0.1)
- File `scorm_runtime.js` mancante nei template HLS e YouTube
