<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.
/**
 * Language strings for SCORM Video Maker (Italian).
 *
 * @package local_scormvideomaker
 * @copyright 2025 Carlo Comincini <carlo@comincini.it>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

// Plugin information.
$string['pluginname'] = 'SCORM Video Maker';
$string['plugindesc'] = 'Crea pacchetti SCORM da URL video (Vimeo, YouTube, HLS)';

// Admin menu.
$string['createscrorm'] = 'Crea pacchetto video SCORM';
$string['createscorm'] = 'Crea pacchetto SCORM';

// Settings.
$string['setting_enabled'] = 'Abilita SCORM Video Maker';
$string['setting_enabled_desc'] = 'Abilita o disabilita il plugin SCORM Video Maker';
$string['setting_default_autoplay'] = 'Autoplay predefinito';
$string['setting_default_autoplay_desc'] = 'Abilita l\'autoplay per impostazione predefinita per i nuovi video';
$string['setting_default_completion_type'] = 'Tipo di completamento predefinito';
$string['setting_default_completion_type_desc'] = 'Seleziona il tipo di completamento predefinito per i nuovi video';
$string['setting_default_completion_percentage'] = 'Percentuale completamento predefinita';
$string['setting_default_completion_percentage_desc'] = 'Percentuale predefinita di video da guardare prima di contrassegnarlo come completato (0-100)';

// Form labels - Video Configuration.
$string['form_course'] = 'Corso';
$string['form_course_help'] = 'Seleziona il corso in cui verrà creata l\'attività SCORM';
$string['form_title'] = 'Titolo attività';
$string['form_title_help'] = 'Inserisci il titolo dell\'attività SCORM';
$string['form_description'] = 'Descrizione';
$string['form_description_help'] = 'Inserisci una descrizione dell\'attività SCORM (supporta la formattazione del testo)';
$string['form_videotype'] = 'Tipo di video';
$string['form_videotype_help'] = 'Seleziona il tipo di sorgente video: Vimeo, YouTube o HLS Stream';
$string['form_videourl'] = 'URL/Codice video';
$string['form_videourl_help'] = 'Per Vimeo: inserisci l\'ID video (es. 123456789). Per YouTube: inserisci l\'ID video (es. dQw4w9WgXcQ). Per HLS: inserisci l\'URL completo del file M3U8';
$string['form_seekbar'] = 'Comportamento barra di avanzamento (video incompleti)';
$string['form_seekbar_help'] = 'Controlla come gli utenti possono navigare nei video non ancora completati';
$string['form_completion_type'] = 'Tipo di completamento';
$string['form_completion_type_help'] = 'Seleziona come l\'attività deve essere contrassegnata come completata';
$string['form_completion_percentage'] = 'Percentuale di completamento';
$string['form_completion_percentage_help'] = 'Inserisci la percentuale di video che deve essere guardata (0-100). Usato solo quando è selezionato "Al raggiungimento di una percentuale"';
$string['form_autoplay'] = 'Autoplay';
$string['form_autoplay_help'] = 'Abilita la riproduzione automatica quando il lettore video viene caricato';

// Video types.
$string['videotype_vimeo'] = 'Vimeo';
$string['videotype_youtube'] = 'YouTube';
$string['videotype_hls'] = 'HLS Stream';

// Seek bar options.
$string['seekbar_locked'] = 'Bloccata (navigazione non consentita)';
$string['seekbar_free'] = 'Libera (navigazione completa consentita)';
$string['seekbar_backward'] = 'Solo indietro (può riavvolgere ma non saltare avanti)';

// Completion types.
$string['completion_end'] = 'Alla fine del video';
$string['completion_percentage'] = 'Al raggiungimento di una percentuale';

// SCORM settings section.
$string['section_scorm_settings'] = 'Impostazioni attività SCORM';
$string['section_scorm_settings_help'] = 'Configura le impostazioni standard dell\'attività SCORM come faresti aggiungendo manualmente un\'attività SCORM a un corso';

// SCORM options.
$string['scorm_version'] = 'Versione SCORM';
$string['scorm_version_12'] = 'SCORM 1.2';
$string['scorm_version_2004'] = 'SCORM 2004';
$string['scorm_maxgrade'] = 'Voto massimo';
$string['scorm_grademethod'] = 'Metodo di valutazione';
$string['scorm_grademethod_high'] = 'Voto più alto ottenuto';
$string['scorm_grademethod_avg'] = 'Voto medio ottenuto';
$string['scorm_grademethod_sum'] = 'Somma dei voti ottenuti';
$string['scorm_grademethod_first'] = 'Voto primo tentativo';
$string['scorm_grademethod_last'] = 'Voto ultimo tentativo';
$string['scorm_maxattempt'] = 'Numero massimo di tentativi';
$string['scorm_maxattempt_unlimited'] = 'Illimitati';
$string['scorm_whatgrade'] = 'Quale voto riportare';
$string['scorm_whatgrade_high'] = 'Tentativo con voto più alto';
$string['scorm_whatgrade_first'] = 'Primo tentativo';
$string['scorm_whatgrade_last'] = 'Ultimo tentativo';
$string['scorm_whatgrade_avg'] = 'Tentativo con voto medio';
$string['scorm_displaycoursestructure'] = 'Visualizza struttura corso';
$string['scorm_skipview'] = 'Salta pagina di visualizzazione';
$string['scorm_skipview_never'] = 'Mai';
$string['scorm_skipview_first'] = 'Primo accesso';
$string['scorm_skipview_always'] = 'Sempre';
$string['scorm_hidebrowse'] = 'Nascondi pulsante sfoglia';
$string['scorm_hidetoc'] = 'Nascondi tabella dei contenuti';
$string['scorm_hidetoc_never'] = 'Mostra';
$string['scorm_hidetoc_structure'] = 'Nascondi solo struttura';
$string['scorm_hidetoc_all'] = 'Nascondi completamente';
$string['scorm_nav'] = 'Mostra barra di navigazione';
$string['scorm_auto'] = 'Auto commit';

// Messages and status.
$string['creating'] = 'Creazione del pacchetto SCORM in corso...';
$string['success'] = 'Pacchetto SCORM creato con successo! L\'attività è stata aggiunta al corso.';
$string['error'] = 'Errore durante la creazione del pacchetto SCORM';
$string['error_invalid_video_url'] = 'URL video o formato codice non valido';
$string['error_invalid_course'] = 'Corso selezionato non valido';
$string['error_course_not_found'] = 'Corso non trovato o non hai il permesso di accedervi';
$string['error_scorm_creation_failed'] = 'Impossibile creare l\'attività SCORM nel corso';
$string['error_invalid_completion_percentage'] = 'La percentuale di completamento deve essere tra 0 e 100';

// Permissions.
$string['scormvideomaker:create'] = 'Crea pacchetti video SCORM';
$string['scormvideomaker:manage'] = 'Gestisci pacchetti video SCORM';

// Events.
$string['event_scorm_created'] = 'Pacchetto video SCORM creato';
$string['event_scorm_created_desc'] = 'L\'utente ha creato un nuovo pacchetto video SCORM nel corso {courseid}';
$string['event_scorm_updated'] = 'Pacchetto video SCORM aggiornato';
$string['event_scorm_updated_desc'] = 'L\'utente ha aggiornato un pacchetto video SCORM nel corso {courseid}';

// Privacy.
$string['privacy:metadata'] = 'Il plugin SCORM Video Maker non memorizza alcun dato personale. Tutti i tracking sono gestiti dal modulo attività SCORM standard.';
