# SCORM Base Template Structure

## Overview
La directory `scorm_base` contiene il template completo del pacchetto SCORM con VideoJS integrato.

## File Template (con variabili)
Questi file vengono processati e le variabili sostituite:

- **index.html.template** → index.html
- **config.js.template** → config.js  
- **imsmanifest.xml.template** → imsmanifest.xml

## Variabili Disponibili

### In tutti i template:
- `{{TITLE}}` - Titolo dell'attività
- `{{DESCRIPTION}}` - Descrizione
- `{{VIDEO_URL}}` - URL del video
- `{{VIDEO_TYPE}}` - Tipo (VIMEO/YOUTUBE/HLS)
- `{{VIDEO_MIME_TYPE}}` - MIME type (application/x-mpegURL per HLS)
- `{{TIMESTAMP}}` - Unix timestamp

### In config.js:
- `{{SEEKBAR}}` - Modalità seekbar (free/locked/backward)
- `{{COMPLETION_TYPE}}` - Tipo completamento (end/percentage)
- `{{COMPLETION_PERCENTAGE}}` - Percentuale (0-100)
- `{{AUTOPLAY}}` - Autoplay (true/false)

## Struttura Directory

```
scorm_base/
├── *.xsd                        # Schema SCORM
├── index.html.template          # Template HTML principale
├── config.js.template           # Template configurazione
├── imsmanifest.xml.template     # Template manifest SCORM
├── css/
│   └── style.css
└── js/
    ├── main.js                  # Logica principale player
    ├── bridge.js                # Bridge SCORM API
    ├── videoall.js
    ├── videojs-http-streaming.js
    └── 3rd/
        ├── videojs/             # VideoJS completo
        └── xapiwrapper.min.js
```

## Processo di Generazione

1. Copia `scorm_base/` in directory temporanea
2. Processa i 3 file `.template`:
   - Legge contenuto
   - Sostituisce variabili
   - Salva versione finale (senza `.template`)
   - Rimuove file `.template`
3. Crea ZIP di tutto il contenuto
4. Cleanup directory temporanea

## Note
- I file `.template` NON vengono inclusi nello ZIP finale
- Tutti i file in `js/3rd/videojs/` vengono inclusi così come sono
- Le variabili vengono escapate con `htmlspecialchars(ENT_QUOTES, 'UTF-8')`
