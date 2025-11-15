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
 * @package scormvideomaker
 * @copyright 2025 Carlo Comincini <carlo@comincini.it>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

// Plugin information.
$string['pluginname'] = 'SCORM Video Maker';
$string['plugindesc'] = 'Crea pacchetti SCORM da URL video (Vimeo, YouTube, HLS)';

// Admin menu.
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
$string['setting_scorm_settings_help'] = 'Configura le impostazioni standard dell\'attività SCORM che verrebbero aggiunte manualmente a un corso';

// Form labels - Video Configuration.
$string['form_course'] = 'Corso';
$string['form_course_help'] = 'Seleziona il corso in cui verrà creato il pacchetto SCORM';
$string['form_title'] = 'Titolo attività';
$string['form_title_help'] = 'Inserisci il titolo del pacchetto SCORM';
$string['form_description'] = 'Descrizione';
$string['form_description_help'] = 'Inserisci una descrizione del pacchetto SCORM (supporta la formattazione del testo)';
$string['form_videotype'] = 'Tipo di video';
$string['form_videotype_help'] = 'Seleziona il tipo di sorgente video: Vimeo, YouTube o HLS Stream';
$string['form_videourl'] = 'URL video';
$string['form_videourl_help'] = 'Per Vimeo: inserisci l\'URL completo o l\'ID video. Per YouTube: inserisci l\'URL completo o l\'ID video (ad es., dQw4w9WgXcQ). Per HLS: inserisci l\'URL completo del file M3U';
$string['form_videowidth'] = 'Larghezza video';
$string['form_videowidth_help'] = 'Larghezza del lettore video in pixel';
$string['form_videoheight'] = 'Altezza video';
$string['form_videoheight_help'] = 'Altezza del lettore video in pixel';

// Video types.
$string['videotype_vimeo'] = 'Vimeo';
$string['videotype_youtube'] = 'YouTube';
$string['videotype_hls'] = 'HLS Stream';

// SCORM options.
$string['scorm_version'] = 'Versione SCORM';
$string['scorm_version_12'] = 'SCORM 1.2';
$string['scorm_maxgrade'] = 'Voto massimo';
$string['scorm_grademethod'] = 'Metodo di valutazione';
$string['scorm_whatgrade_high'] = 'Voto più alto';
$string['scorm_whatgrade_first'] = 'Primo tentativo';
$string['scorm_whatgrade_last'] = 'Ultimo tentativo';
$string['scorm_whatgrade_avg'] = 'Voto medio';
$string['scorm_grademethod_high'] = 'Voto più alto';
$string['scorm_grademethod_last'] = 'Ultimo tentativo';
$string['scorm_grademethod_first'] = 'Primo tentativo';
$string['scorm_grademethod_avg'] = 'Voto medio';
$string['scorm_maxattempt'] = 'Massimi tentativi';
$string['scorm_maxattempt_unlimited'] = 'Illimitati';
$string['scorm_whatgrade'] = 'Quale voto da reportare';
$string['scorm_whatgrade_high'] = 'Voto più alto';
$string['scorm_whatgrade_first'] = 'Primo tentativo';
$string['scorm_whatgrade_last'] = 'Ultimo tentativo';
$string['scorm_whatgrade_avg'] = 'Voto medio';
$string['scorm_displaycoursestructure'] = 'Visualizza struttura corso';
$string['scorm_skipview'] = 'Salta pagina di visualizzazione';
$string['scorm_skipview_never'] = 'Mai';
$string['scorm_skipview_first'] = 'Primo accesso';
$string['scorm_skipview_always'] = 'Sempre';
$string['scorm_hidebrowse'] = 'Nascondi pulsante sfoglia';
$string['scorm_hidetoc'] = 'Nascondi tabella dei contenuti';
$string['scorm_sidetoc_never'] = 'Mostra';
$string['scorm_sidetoc_structure'] = 'Solo struttura';
$string['scorm_sidetoc_all'] = 'Nascondi completamente';
$string['scorm_nav_bar'] = 'Mostra barra di navigazione';
$string['scorm_auto'] = 'Auto commit';

// Completion types.
$string['completion_end'] = 'Fine del video';
$string['completion_percentage'] = 'Al raggiungimento di una % di video';

// Messages and status.
$string['creating'] = 'Creazione del pacchetto SCORM in corso...';
$string['success'] = 'Pacchetto SCORM creato con successo! L\'attività è stata aggiunta al corso.';
$string['error'] = 'Errore';
$string['error_invalid_video_url'] = 'URL video non valido o formato non supportato';
$string['error_invalid_course'] = 'Corso non trovato o non hai il permesso di accedervi';
$string['error_scorm_creation_failed'] = 'Impossibile creare l\'attività SCORM nel corso';
$string['error_invalid_completion_percentage'] = 'La percentuale di completamento deve essere tra 0 e 100';

// Permissions.
$string['scormvideomaker:create'] = 'Crea pacchetti SCORM Video Maker';
$string['scormvideomaker:manage'] = 'Gestisci pacchetti SCORM Video Maker';

// Events.
$string['event_scorm_created'] = 'Pacchetto SCORM Video Maker creato';
$string['event_scorm_created_desc'] = 'L\'utente ha creato un nuovo pacchetto SCORM Video Maker nel corso {$a->courseid}';
$string['event_scorm_updated'] = 'Pacchetto SCORM Video Maker aggiornato';
$string['event_scorm_updated_desc'] = 'L\'utente ha aggiornato un pacchetto SCORM Video Maker nel corso {$a->courseid}';

// Privacy.
$string['privacy:metadata'] = 'Il plugin SCORM Video Maker non memorizza alcun dato personale. Tutti i tracking e il completamento sono gestiti dal modulo SCORM standard di Moodle.'
