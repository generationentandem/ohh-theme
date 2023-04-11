<?php
/**
 * Created by PhpStorm.
 * User: manuelmeister
 * Date: 20.08.17
 * Time: 17:28
 */

// require_once ('ical/ics-calendar/includes/lib/class-ics-calendar-ics-helper.php');
// require_once( 'ical/ics-calendar/includes/lib/class.iCalReader.php' );

add_action( 'wp_dashboard_setup', 'und_custom_dashboard_widgets');

function und_custom_dashboard_widgets() {
	if ( have_rows( 'und_dashboard_blocks', 'option' ) ) {
		$rows = get_field( 'und_dashboard_blocks', 'option', true );
		foreach($rows as $key => $row) {
			wp_add_dashboard_widget('und_custom_dashboard-'. $row['title'],$row['title'],'und_custom_dashboard_content',null,$key);
		}
	}
	wp_add_dashboard_widget('und_custom_dashboard-calendar','UND Kalender','und_dashboard_calendar',null);
	if ( have_rows( 'und_ical_urls', 'option' ) ) {
		$rows = get_field( 'und_ical_urls', 'option', true );

	}
}

function und_custom_dashboard_content($object,$args) {
	$row = get_field( 'und_dashboard_blocks', 'option', true )[$args['args']];
	echo $row['content'];
}

function und_dashboard_calendar($object,$args) {
	// (new ICS_Calendar_ICS_Helper())->renderCalendar();
	echo '<a href="https://cloud.generationentandem.ch/apps/calendar/p/arcLttDAwEYtY3eW" target="_blank">UND-Büro (Nextcloud)</a><br/>';
	echo '<a href="https://cloud.generationentandem.ch/apps/calendar/p/EJ9B2dCHmm8QPedY" target="_blank">UND-Raum (Nextcloud)</a><br/>';
	echo '<a href="https://cloud.generationentandem.ch/apps/calendar/p/GzR9ZjoJB3DYicLT" target="_blank">UND-Veranstaltungen (Nextcloud)</a><br/>';
	echo '<a href="https://cloud.generationentandem.ch/apps/calendar/p/ays4znQBg2LCRAiw" target="_blank">UND-Workflow (Nextcloud)</a><br/>';
	echo '<a href="https://cloud.generationentandem.ch/apps/calendar/p/oDRxfcYHiJiNqHS3" target="_blank">UND-Ferien (Nextcloud)</a><br/>';


}
