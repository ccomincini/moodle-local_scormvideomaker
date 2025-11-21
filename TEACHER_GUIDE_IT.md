# SCORM Video Maker - Guida per Docenti

## Indice

1. [Introduzione](#introduzione)
2. [Creare un Nuovo Pacchetto SCORM](#creare-un-nuovo-pacchetto-scorm)
3. [Tipi di Video e Configurazione](#tipi-di-video-e-configurazione)
4. [Gestire i Tuoi Pacchetti](#gestire-i-tuoi-pacchetti)
5. [Best Practice](#best-practice)

---

## Introduzione

SCORM Video Maker è un plugin Moodle che ti permette di creare pacchetti SCORM contenenti contenuti video. Questi pacchetti possono essere facilmente aggiunti ai tuoi corsi Moodle per fornire esperienze di apprendimento multimediali coinvolgenti.

Questa guida ti aiuterà a capire come utilizzare il plugin per creare e gestire pacchetti SCORM basati su video.

---

## Creare un Nuovo Pacchetto SCORM

### Passo 1: Accedere al Plugin

1. Naviga in **Amministrazione del sito** → **Plugin** → **Plugin locali** → **SCORM Video Maker**
2. Clicca su **"Crea nuovo pacchetto SCORM"**

![Accesso a SCORM Video Maker](images/access-plugin.png)
*Screenshot: Accesso al plugin SCORM Video Maker*

### Passo 2: Compilare i Dettagli del Pacchetto

Vedrai un modulo con i seguenti campi:

**Nome Pacchetto** (obbligatorio)
- Inserisci un nome descrittivo per il tuo pacchetto SCORM
- Questo nome sarà utilizzato per identificare il pacchetto nella tua lista
- Esempio: "Introduzione alla Biologia - Struttura Cellulare"

**Tipo di Video** (obbligatorio)
- Scegli tra: Vimeo, YouTube o HLS
- Seleziona in base a dove è ospitato il tuo video
- Vedi [Tipi di Video e Configurazione](#tipi-di-video-e-configurazione) per i dettagli

**Sorgente Video** (obbligatorio)
- Inserisci l'identificativo o l'URL del video
- Il formato dipende dal tipo di video selezionato

![Modulo di Creazione Pacchetto](images/creation-form.png)
*Screenshot: Modulo di creazione pacchetto SCORM*

### Passo 3: Inviare e Generare

1. Rivedi i tuoi inserimenti per verificarne l'accuratezza
2. Clicca su **"Crea Pacchetto"**
3. Attendi il completamento del processo di generazione
4. Scarica il pacchetto SCORM generato

![Pacchetto Generato](images/package-ready.png)
*Screenshot: Pacchetto generato con successo pronto per il download*

---

## Tipi di Video e Configurazione

### Vimeo

**Formato Sorgente Video:**
- Inserisci solo il numero ID del video
- Esempio: Per `https://vimeo.com/123456789`, inserisci: `123456789`

**Requisiti:**
- Il video deve essere pubblicamente accessibile o avere impostazioni di condivisione appropriate
- L'embedding deve essere abilitato nelle impostazioni Vimeo

![Configurazione Vimeo](images/vimeo-config.png)
*Screenshot: Configurazione video Vimeo*

### YouTube

**Formato Sorgente Video:**
- Inserisci solo l'ID del video
- Esempio: Per `https://www.youtube.com/watch?v=dQw4w9WgXcQ`, inserisci: `dQw4w9WgXcQ`

**Requisiti:**
- Il video deve essere pubblico o non in elenco
- L'embedding deve essere consentito

![Configurazione YouTube](images/youtube-config.png)
*Screenshot: Configurazione video YouTube*

### HLS (HTTP Live Streaming)

**Formato Sorgente Video:**
- Inserisci l'URL completo del file playlist .m3u8
- Esempio: `https://example.com/video/playlist.m3u8`

**Requisiti:**
- Lo stream HLS deve essere accessibile dal tuo server Moodle
- Gli header CORS devono essere configurati correttamente se lo stream è su un dominio diverso

![Configurazione HLS](images/hls-config.png)
*Screenshot: Configurazione stream HLS*

---

## Gestire i Tuoi Pacchetti

### Visualizzare i Tuoi Pacchetti

1. Vai alla pagina del plugin SCORM Video Maker
2. Vedrai una lista di tutti i pacchetti che hai creato con:
   - Nome del pacchetto
   - Tipo di video
   - Data di creazione
   - Azioni disponibili

![Lista Pacchetti](images/package-list.png)
*Screenshot: Lista dei pacchetti SCORM creati*

### Scaricare un Pacchetto

1. Individua il tuo pacchetto nella lista
2. Clicca sul pulsante **"Scarica"**
3. Il pacchetto SCORM (file ZIP) sarà scaricato sul tuo dispositivo

### Eliminare un Pacchetto

1. Individua il pacchetto che vuoi rimuovere
2. Clicca sul pulsante **"Elimina"**
3. Conferma l'eliminazione quando richiesto
4. Il pacchetto sarà rimosso permanentemente

**Nota:** Eliminare un pacchetto dal plugin non influisce sulle copie già aggiunte ai tuoi corsi.

---

## Best Practice

### Scegliere il Tipo di Video Giusto

- **Vimeo**: Ideale per hosting video professionale con controlli avanzati sulla privacy
- **YouTube**: Perfetto per contenuti educativi pubblici con ampia accessibilità
- **HLS**: Consigliato per streaming di alta qualità o quando hai bisogno di controllo completo sull'hosting

### Convenzioni di Nomenclatura

Usa nomi chiari e descrittivi che includano:
- Nome del corso o del modulo
- Identificativo dell'argomento o della lezione
- Numero di versione se prevedi aggiornamenti

Esempio: "BIO101-Modulo2-StrutturaCellulare-v1"

### Prima di Creare un Pacchetto

1. **Verifica l'accessibilità del video**: Assicurati che il video possa essere visualizzato pubblicamente o con permessi appropriati
2. **Controlla la qualità del video**: Conferma che il video venga riprodotto correttamente alla sua sorgente
3. **Testa su diversi dispositivi**: Se possibile, verifica che il video funzioni su varie piattaforme
4. **Rivedi il contenuto**: Assicurati che il contenuto video sia appropriato e completo

### Dopo Aver Creato un Pacchetto

1. **Testa il pacchetto SCORM**: Caricalo in un corso di test e verifica che venga riprodotto correttamente
2. **Controlla su diversi browser**: Testa in Chrome, Firefox, Safari ed Edge
3. **Verifica la compatibilità mobile**: Se gli studenti usano dispositivi mobili, testa su telefoni/tablet
4. **Conserva il video originale**: Non eliminare il video sorgente, potresti aver bisogno di ricreare il pacchetto

### Gestione dello Spazio di Archiviazione

- Rivedi ed elimina regolarmente i pacchetti non utilizzati
- Conserva solo la versione più recente dei pacchetti che hai aggiornato
- Archivia i pacchetti importanti fuori da Moodle per la conservazione a lungo termine

### Considerazioni sull'Accessibilità

- Assicurati che i video abbiano sottotitoli quando possibile
- Fornisci trascrizioni per i contenuti importanti
- Considera di aggiungere testo introduttivo alle tue attività SCORM in Moodle
- Testa che i video funzionino con gli screen reader se applicabile

---

## Hai Bisogno di Aiuto?

Se riscontri problemi nell'utilizzo del plugin, consulta la [Guida alla Risoluzione dei Problemi](TROUBLESHOOTING_IT.md) o contatta il tuo amministratore Moodle.

Per supporto tecnico e aggiornamenti, visita: https://github.com/yourusername/moodle-local_scormvideomaker
