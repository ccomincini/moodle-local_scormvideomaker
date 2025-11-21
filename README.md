# SCORM Video Maker

Un plugin Moodle per creare automaticamente pacchetti SCORM da video Vimeo, YouTube o HLS.

## 📋 Indice

- [Caratteristiche](#caratteristiche)
- [Requisiti](#requisiti)
- [Installazione](#installazione)
- [Guida per Amministratori](#guida-per-amministratori)
- [Guida per Docenti](#guida-per-docenti)
- [Sicurezza](#sicurezza)
- [Risoluzione Problemi](#risoluzione-problemi)
- [Supporto](#supporto)

---

## ✨ Caratteristiche

- **Supporto multi-piattaforma**: Vimeo, YouTube, HLS streaming
- **Tracciamento SCORM completo**: Progressione, voto e completamento automatici
- **Controllo riproduzione**: Blocco barra di avanzamento per video non completati
- **Completamento flessibile**: Al termine del video o a percentuale personalizzata
- **Player responsive**: Si adatta automaticamente a tutti i browser e dispositivi
- **Sicurezza avanzata**: I pacchetti ZIP vengono eliminati dopo l'estrazione
- **Configurazione semplificata**: Impostazioni fisse ottimizzate per l'uso didattico

---

## 📦 Requisiti

- **Moodle**: 4.0 o superiore
- **PHP**: 7.4 o superiore
- **Modulo SCORM**: Deve essere installato e abilitato
- **Spazio disco**: Minimo per file temporanei (vengono cancellati automaticamente)

---

## 🚀 Installazione

### Metodo 1: Via interfaccia web

1. Scarica il file ZIP del plugin da GitHub
2. Accedi a Moodle come amministratore
3. Vai su **Amministrazione del sito > Plugin > Installa plugin**
4. Carica il file ZIP
5. Clicca su **Installa plugin da file ZIP**
6. Segui le istruzioni a schermo

### Metodo 2: Via terminale

```bash
cd /path/to/moodle/local
git clone https://github.com/ccomincini/moodle-local_scormvideomaker.git scormvideomaker
cd /path/to/moodle
php admin/cli/upgrade.php
php admin/cli/purge_caches.php
```

### Post-installazione

1. Vai su **Amministrazione del sito > Notifiche**
2. Clicca su **Aggiorna database di Moodle**
3. Il plugin è ora installato e pronto all'uso

---

## 👨‍💼 Guida per Amministratori

### Accesso al plugin

Il plugin è accessibile da:
- **Amministrazione del sito > Plugin > Plugin locali > SCORM Video Maker**
- URL diretto: `/local/scormvideomaker/index.php`

### Permessi e Capability

Il plugin introduce le seguenti capability:

| Capability | Descrizione | Ruoli predefiniti |
|-----------|-------------|-------------------|
| `local/scormvideomaker:create` | Creare pacchetti SCORM | Manager, Editing Teacher |
| `local/scormvideomaker:manage` | Gestire il plugin | Manager |

#### Assegnazione permessi personalizzati

Per consentire ad altri ruoli di creare pacchetti SCORM:

1. Vai su **Amministrazione del sito > Utenti > Permessi > Definisci ruoli**
2. Seleziona il ruolo da modificare
3. Cerca `local/scormvideomaker:create`
4. Imposta su **Permetti**
5. Salva

### Configurazione tecnica

#### Impostazioni SCORM predefinite

Il plugin utilizza impostazioni fisse ottimizzate:

```
Versione SCORM: 1.2
Voto massimo: 100
Metodo valutazione: Voto migliore
Tentativi massimi: 1
Navigazione: Salta sempre pagina iniziale
TOC: Sempre nascosto
Auto-continue: Disabilitato
```

#### Tracciamento e completamento

- **Voto**: Sempre uguale alla percentuale visualizzata (es. 75% = voto 75)
- **Completamento**: Attivato automaticamente quando si raggiunge la soglia impostata
- **Tracking**: SCORM 1.2 standard (cmi.core.lesson_status)

### Sicurezza

#### Protezione pacchetti ZIP

Il plugin implementa misure di sicurezza avanzate:

1. **Generazione in directory temporanea sicura**: `/tmp/scormvideomaker/` (non accessibile via web)
2. **Cancellazione immediata dopo upload**: Il file ZIP viene eliminato dal filesystem
3. **Rimozione dal file storage**: Dopo il parsing, il ZIP viene rimosso anche dalla filearea 'package'
4. **Solo contenuto estratto disponibile**: Rimane solo il contenuto nella filearea 'content'

**Risultato**: Non è possibile scaricare il pacchetto ZIP dalle impostazioni SCORM.

#### Monitoraggio

Per verificare che non ci siano file ZIP residui:

```bash
# Controlla directory temporanea
ls -la /tmp/scormvideomaker/

# Dovrebbe essere vuota o contenere solo directory di lavoro temporanee
```

### Manutenzione

#### Pulizia cache dopo aggiornamenti

Dopo ogni aggiornamento del plugin:

```bash
php admin/cli/purge_caches.php
```

Oppure via interfaccia: **Amministrazione del sito > Sviluppo > Svuota tutte le cache**

#### Log e debugging

Per attivare il debug:

1. Vai su **Amministrazione del sito > Sviluppo > Debug**
2. Imposta **Messaggi di debug** su **DEVELOPER**
3. Abilita **Visualizza informazioni di debug**

I log del plugin sono visibili nei log standard di Moodle.

### Risoluzione problemi comuni

#### Il modulo SCORM non è installato

**Errore**: "SCORM module not installed"

**Soluzione**:
```bash
cd /path/to/moodle
php admin/cli/upgrade.php
```

Verifica in **Amministrazione del sito > Plugin > Panoramica plugin** che il modulo SCORM sia presente.

#### Errore di permessi file

**Errore**: "Cannot create ZIP file"

**Soluzione**:
```bash
chmod 755 /tmp
chown www-data:www-data /tmp
```

#### Il JavaScript non si carica

**Problema**: La lista corsi non si popola quando si seleziona una categoria

**Soluzione**:
1. Svuota tutte le cache
2. Verifica che il file `amd/build/category_course_selector.min.js` esista
3. Controlla la console browser (F12) per errori JavaScript

---

## 👨‍🏫 Guida per Docenti

### Accesso rapido

Puoi accedere al creatore di pacchetti SCORM da:
- **Amministrazione del sito > SCORM Video Maker** (se hai i permessi di amministratore)
- URL diretto fornito dal tuo amministratore

### Creare un pacchetto SCORM

#### Passo 1: Scegliere il corso

1. **Seleziona la categoria**: Scegli la categoria che contiene il tuo corso
2. **Seleziona il corso**: La lista si aggiornerà automaticamente mostrando tutti i corsi della categoria
3. **Scegli la sezione**: Indica in quale sezione del corso inserire l'attività (0 = prima sezione)

💡 **Suggerimento**: I corsi nascosti sono visibili con l'etichetta "(nascosto)"

#### Passo 2: Configurare il video

##### Informazioni base

- **Titolo attività**: Il nome che vedranno gli studenti
- **Descrizione**: Testo introduttivo (opzionale, supporta formattazione HTML)

##### Scegliere il tipo di video

###### 🎬 Vimeo

**Cosa inserire**:
- URL completo: `https://vimeo.com/1098523964`
- Oppure solo ID: `1098523964`

**Dove trovare l'ID**: È il numero nell'URL del video Vimeo

###### 📺 YouTube

**Cosa inserire**:
- URL completo: `https://www.youtube.com/watch?v=dQw4w9WgXcQ`
- URL corto: `https://youtu.be/dQw4w9WgXcQ`
- Oppure solo ID: `dQw4w9WgXcQ`

**Dove trovare l'ID**: È la parte dopo `v=` nell'URL

###### 🎞️ HLS Stream

**Cosa inserire**: URL completo del file `.m3u8`
- Esempio: `https://example.com/video/playlist.m3u8`

**Nota**: Questo formato è per video hosting professionale (CDN)

#### Passo 3: Controlli di riproduzione

##### Barra di avanzamento (per video non completati)

- **Bloccata**: Lo studente non può spostarsi nel video
- **Solo indietro**: Può rivedere parti precedenti ma non saltare avanti
- **Libera**: Può spostarsi liberamente (⚠️ non consigliato per valutazione)

💡 **Consigliato**: "Solo indietro" - permette di rivedere ma garantisce visione completa

##### Autoplay

- ☑️ **Abilitato**: Il video parte automaticamente
- ☐ **Disabilitato**: Lo studente deve cliccare play

#### Passo 4: Criteri di completamento

##### Tipo di completamento

**🎯 Alla fine del video**
- Lo studente deve vedere il 100% del video
- Voto = 100 quando completa
- **Consigliato per**: Video di formazione obbligatoria

**📊 A percentuale personalizzata**
- Imposta una soglia (es. 75%)
- Lo studente completa al raggiungimento della soglia
- Il voto rimane = percentuale effettivamente vista

**Esempio pratico**:
```
Soglia impostata: 50%

Studente A guarda 50% → Stato: COMPLETATO, Voto: 50
Studente B guarda 75% → Stato: COMPLETATO, Voto: 75  
Studente C guarda 30% → Stato: INCOMPLETO, Voto: 30
```

💡 **Quando usare le percentuali**:
- Video molto lunghi (>30 minuti)
- Contenuto non critico
- Vuoi incentivare la visione senza obbligare al 100%

##### Percentuale di completamento

Visibile solo se scegli "A percentuale personalizzata"
- Inserisci un numero da 1 a 100
- Rappresenta la percentuale minima per completare

#### Passo 5: Creare il pacchetto

1. Clicca su **Crea pacchetto SCORM**
2. Attendi qualche secondo (viene mostrato un indicatore di caricamento)
3. Il pacchetto viene creato e aggiunto automaticamente al corso
4. Verrai reindirizzato al corso

### Dopo la creazione

#### Cosa succede automaticamente

✅ L'attività SCORM viene aggiunta alla sezione scelta  
✅ È visibile agli studenti  
✅ Il tracking è attivo  
✅ Il completamento è configurato  
✅ Il voto è collegato al registro

#### Verificare l'attività

1. Vai al corso
2. Cerca l'attività appena creata
3. Clicca su **Anteprima** per testarla
4. Verifica che il video si carichi correttamente

#### Modificare le impostazioni

Puoi modificare:
- Nome e descrizione
- Visibilità
- Date di apertura/chiusura (se necessarie)

**Non modificabili** (impostazioni fisse del plugin):
- Versione SCORM
- Numero tentativi (sempre 1)
- Metodo valutazione
- Comportamento navigazione

### Best practices per docenti

#### 📹 Scelta del formato video

| Formato | Quando usarlo | Vantaggi | Limiti |
|---------|--------------|----------|--------|
| **Vimeo** | Video didattici di qualità | Alta qualità, no pubblicità | Richiede account Vimeo |
| **YouTube** | Video pubblici, tutorial | Gratuito, sempre disponibile | Possibili pubblicità |
| **HLS** | Video su CDN professionale | Massima qualità, streaming | Richiede infrastruttura |

#### 🎯 Configurazione completamento

**Per formazione obbligatoria**:
- Tipo: Alla fine del video
- Barra: Solo indietro
- Autoplay: Disabilitato (lo studente deve essere attivo)

**Per contenuti di approfondimento**:
- Tipo: A percentuale (es. 70%)
- Barra: Solo indietro
- Autoplay: A scelta

**Per video brevi (<5 min)**:
- Tipo: Alla fine del video
- Barra: Bloccata
- Autoplay: Abilitato

#### 📊 Monitoraggio studenti

Per vedere chi ha completato:
1. Vai all'attività SCORM
2. Clicca su **Rapporti**
3. Visualizza la progressione di ogni studente

Puoi vedere:
- % visualizzata
- Voto ottenuto
- Stato completamento
- Tempo trascorso

---

## 🔒 Sicurezza

### Protezione dei contenuti

Il plugin implementa le seguenti misure di sicurezza:

1. **ZIP non scaricabile**: Dopo la creazione, il file ZIP viene eliminato
2. **Contenuto SCORM protetto**: Accessibile solo tramite player Moodle
3. **Autenticazione richiesta**: Solo utenti iscritti al corso possono vedere i video
4. **File temporanei sicuri**: Creati in directory non accessibili via web

### Privacy

Il plugin:
- ✅ Traccia solo dati SCORM standard (progressione, voto)
- ✅ Non memorizza dati personali aggiuntivi
- ✅ Rispetta le impostazioni privacy di Moodle
- ✅ Tutti i dati sono nel database Moodle standard

---

## 🔧 Risoluzione Problemi

### Per docenti

#### Il video non si carica

**Possibili cause**:
1. **ID video errato**: Verifica di aver copiato correttamente l'ID/URL
2. **Video privato**: Su Vimeo/YouTube, verifica che il video sia pubblico o "non in elenco"
3. **URL HLS non valido**: Il file .m3u8 deve essere accessibile pubblicamente

**Soluzione**: Prova ad aprire il video direttamente nel browser

#### La lista corsi non si popola

**Causa**: JavaScript non caricato o cache non svuotata

**Soluzione**:
1. Svuota la cache del browser (Ctrl+F5)
2. Controlla la console (F12) per errori
3. Contatta l'amministratore se persiste

#### Errore "Seleziona un corso dalla lista"

**Causa**: Non hai selezionato un corso dopo aver scelto la categoria

**Soluzione**: Clicca sulla lista "Corso" e seleziona un corso

### Per amministratori

#### Il parsing SCORM fallisce

**Sintomo**: L'attività si crea ma il video non funziona

**Debug**:
1. Attiva il debug
2. Riprova a creare un pacchetto
3. Controlla i log per errori nel parsing

**Possibile soluzione**:
```bash
# Verifica permessi directory temporanea
ls -ld /tmp/scormvideomaker
chmod 755 /tmp/scormvideomaker
```

#### La categoria/corso non compare

**Causa**: Permessi insufficienti

**Soluzione**:
1. Verifica che l'utente abbia `local/scormvideomaker:create`
2. Verifica che l'utente abbia `moodle/category:viewcourselist` sulla categoria

---

## 📞 Supporto

### Documentazione aggiuntiva

- **Developer Docs**: [Moodle Dev Docs](https://docs.moodle.org)
- **SCORM Spec**: [SCORM 1.2 Reference](https://scorm.com/scorm-explained/)
- **GitHub**: [Repository del plugin](https://github.com/ccomincini/moodle-local_scormvideomaker)

### Segnalazione bug

Per segnalare problemi:
1. Vai su GitHub Issues
2. Verifica che il bug non sia già stato segnalato
3. Crea una nuova issue includendo:
   - Versione Moodle
   - Versione plugin
   - Descrizione del problema
   - Passi per riprodurlo
   - Screenshot (se utile)

### Contributi

Contributi sono benvenuti! Invia una Pull Request su GitHub.

---

## 📄 Licenza

Questo plugin è rilasciato sotto licenza **GNU GPL v3** o successive.

## 👥 Crediti

**Autore**: Carlo Comincini  
**Email**: carlo@comincini.it  
**Copyright**: 2025 Carlo Comincini

---

## 🔄 Changelog

### Versione 1.0.4 (2025-11-20)

#### Novità
- ✅ Selezione corso per categoria con aggiornamento dinamico via AJAX
- ✅ Player video responsive funzionante in tutti i browser
- ✅ Supporto corretto per playerType (vimeo/youtube/videojs)

#### Sicurezza
- 🔒 Eliminazione automatica ZIP dopo parsing SCORM
- 🔒 Prevenzione download pacchetto dalle impostazioni

#### Fix
- ✅ Vimeo/YouTube usano solo ID nel config.js
- ✅ Completamento basato su percentuale funzionante
- ✅ Voto sempre = percentuale visualizzata

### Versione 1.0.3 (2025-11-16)

#### Novità
- ✅ Prima versione BETA pubblica
- ✅ Supporto Vimeo, YouTube, HLS

---

**Grazie per aver scelto SCORM Video Maker!** 🎉
