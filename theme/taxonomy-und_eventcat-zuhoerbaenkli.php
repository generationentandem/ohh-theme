<?php
/**
 * The template for displaying the Page Zuhörbänkli
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

$input_location = filter_input( INPUT_POST, 'location', FILTER_VALIDATE_INT );

$table_name  = '';
$pretty_name = '';
switch ( $input_location ) {
	case '2':
		$table_name  = 'Standort Thun Rathaus';
		$pretty_name = 'Rathaus Thun';
		break;
	case '3':
		$table_name  = 'Plaudertisch';
		$pretty_name = 'Plaudertisch Berner Generationenhaus';
		break;
	case '4':
		$table_name  = 'Standort Interlaken';
		$pretty_name = 'Interlaken Zentrum Artos';
		break;
	default:
		$table_name  = 'Standort Thun';
		$pretty_name = 'Bahnhofshalle Thun';
}

/**
 * Retrieving data from the Seatable API.
 * Prepare the respective header information for the Seatable API.
 */
$args = array(
	'method'  => 'GET',
	'headers' => array(
		'Accept'        => 'application/json; charset=utf-8; indent=4',
		'Authorization' => 'Token 89f2061117b292b0d7fe41269dad3ec30d4deeab',
	),
);


/**
 * Get the base's access token with its API token. The read/write permission depends on the API token.
 * https://api.seatable.io/#80381c6b-e86f-42cc-afc1-e8d05eb771a9
 */
$response = wp_remote_request( 'https://seatable.generationentandem.ch/api/v2.1/dtable/app-access-token', $args );
$api_base = json_decode( $response['body'] );

$args = array(
	'headers' => array(
		'method'        => 'GET',
		'Accept'        => 'application/json; charset=utf-8; indent=4',
		'Authorization' => 'Token ' . $api_base->access_token,
	),
);


/**
 * List Rows in A View in A Table
 * List all the rows and their content in a specific view in a table.
 * https://api.seatable.io/#c7caa77d-6214-4ca1-bb91-5c1d3d19c52d
 *
 * PARAMS:
 * table_name:  Standort Thun | Standort Thun Rathaus | Standort Bern | Standort Interlaken
 * view_name:   WordPress
 */
$response            = wp_remote_request( 'https://seatable.generationentandem.ch/dtable-server/api/v1/dtables/e9e6b4c8-7259-4a8d-8f9b-baf72aff462d/rows/?table_name=' . $table_name . '&view_name=Wordpress&convert_link_id=true&order_by=Datum&direction=asc&limit=365', $args );
$table_location_rows = json_decode( $response['body'], true );


/**
 * List Columns in A View in A Table
 * List all the visible columns in a certain view in a table.
 * https://api.seatable.io/#bc069ee4-95a3-4204-9ce7-ae7c65469377
 *
 * PARAMS:
 * table_name:  Standort Thun | Standort Thun Rathaus | Standort Bern | Standort Interlaken
 * view_name:   WordPress
 */
$response               = wp_remote_request( 'https://seatable.generationentandem.ch/dtable-server/api/v1/dtables/e9e6b4c8-7259-4a8d-8f9b-baf72aff462d/columns/?table_name=' . $table_name . '&view_name=WordPress', $args );
$table_location_columns = json_decode( $response['body'], true );


/**
 * List Rows in A View in A Table
 * List all the rows and their content in a specific view in a table.
 * https://api.seatable.io/#c7caa77d-6214-4ca1-bb91-5c1d3d19c52d
 *
 * PARAMS:
 * table_name:  HelferInnen
 * view_name:   WordPress
 */
$response              = wp_remote_request( 'https://seatable.generationentandem.ch/dtable-server/api/v1/dtables/e9e6b4c8-7259-4a8d-8f9b-baf72aff462d/rows/?table_name=HelferInnen&view_name=Wordpress', $args );
$table_volunteers_rows = json_decode( $response['body'], true );


/**
 * For earlier versions of PHP, polyfill of the str_contains function.
 */
if ( ! function_exists( 'str_contains' ) ) {
	function str_contains( string $haystack, string $needle ) {
		return '' !== $needle && mb_strpos( $haystack, $needle ) !== false;
	}
}

/**
 * Sets locale information.
 */
setlocale( LC_ALL, 'de_DE.UTF-8' );


/**
 * In which week is the respective day.
 */
$weekly_schedules = array_unique(
	array_map(
		function( $val ) {
			return date( 'W', strtotime( $val['Datum'] ) );
		},
		$table_location_rows['rows']
	)
);

/**
 * Anonymize the last names of the helpers.
 */
$helferinn = function( $name ) {
	return implode( ' ', explode( ' ', trim( $name ), -1 ) ) . ' ' . substr( end( explode( ' ', trim( $name ) ) ), 0, 1 ) . '.';
};

/**
 * Shorten date output
 */

$short_date = function( $string ) {
	return strftime( '%d. %B', strtotime( $string ) );
};

$start_of_week = function( $date ) {
	return strftime( '%d. %B', strtotime( '2022W' . str_pad( $date, 2, 0, STR_PAD_LEFT ) ) );

};

$end_of_week = function( $date ) {
	return strftime( '%d. %B', strtotime( '2022W' . str_pad( $date, 2, 0, STR_PAD_LEFT ) . ' +6 days' ) );
};


/**
 * Browse the Helper List from the Seatable API.
 */
function array_search_partial( $array, $keyword ) {
	$keyword = trim( $keyword );
	foreach ( $array as $index => $arr ) {
		foreach ( $arr as $string ) {
			if ( is_string( $string ) && str_contains( $string, $keyword ) !== false ) {
				return $array[ $index ]['_id'];
			}
		}
	}
}


/**
 * Landing Page Settings. Copied from script taxonomy-und_eventcat.php
 */


$selected_location = ( intval( $input_location ) > '0' && intval( $input_location ) < '5' ? intval( $input_location ) : '1' );


$input_week      = filter_input( INPUT_POST, 'week', FILTER_VALIDATE_INT );
$input_firstname = trim( filter_input( INPUT_POST, 'firstname', FILTER_SANITIZE_STRING ) );
$input_lastname  = trim( filter_input( INPUT_POST, 'lastname', FILTER_SANITIZE_STRING ) );
$input_row       = trim( filter_input( INPUT_POST, 'row', FILTER_SANITIZE_STRING ) );
$input_column    = filter_input( INPUT_POST, 'column', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );


$current_date  = new DateTime();
$current_week  = date( 'W', strtotime( $current_date->format( 'Y-m-d' ) ) );
$selected_week = ( intval( $input_week ) > '0' && intval( $input_week ) < '53' ? intval( $input_week ) : intval( $current_week ) );

$helper_search = '';
if ( ! empty( $input_firstname ) && ! empty( $input_lastname ) ) {
	$helper_search = trim( array_search_partial( $table_volunteers_rows['rows'], $input_firstname . ' ' . $input_lastname ) );
}



/**
 * Landing Page Settings. Copied from script taxonomy-und_eventcat.php
 */
$und_event_landingpage_settings_on = get_field( 'und_event_landingpage_settings_on' );

$und_event_landingpage_header_on     = get_field( 'und_event_landingpage_header_on' );
$und_event_landingpage_header_left   = get_field( 'und_event_landingpage_header_left' );
$und_event_landingpage_header_middle = get_field( 'und_event_landingpage_header_middle' );
$und_event_landingpage_header_right  = get_field( 'und_event_landingpage_header_right' );

$und_event_landingpage_next_events_on    = get_field( 'und_event_landingpage_next_events_on' );
$und_event_landingpage_title_next_events = get_field( 'und_event_landingpage_title_next_events' );
$und_event_landingpage_text_next_events  = get_field( 'und_event_landingpage_text_next_events' );

$und_event_landingpage_insights_on    = get_field( 'und_event_landingpage_insights_on' );
$und_event_landingpage_title_insights = get_field( 'und_event_landingpage_title_insights' );
$und_event_landingpage_text_insights  = get_field( 'und_event_landingpage_text_insights' );

$und_event_landingpage_archive_on    = get_field( 'und_event_landingpage_archive_on' );
$und_event_landingpage_title_archive = get_field( 'und_event_landingpage_title_archive' );
$und_event_landingpage_text_archive  = get_field( 'und_event_landingpage_text_archive' );

get_header();
?>

<?php
/**
 * Landing Page Header. Copied from script taxonomy-und_eventcat.php
 */
?>
<header class="archive-header" style=" border-top: 10px solid <?php echo get_taxonomy_color( $wp_query->get_queried_object_id() ); ?>; border-bottom: 10px solid <?php echo get_taxonomy_color( $wp_query->get_queried_object_id() ); ?>;
	<?php echo get_field( 'bild', $wp_query->get_queried_object() ) ? 'background: url(' . wp_get_attachment_image_src( get_field( 'bild', $wp_query->get_queried_object() ), 'und-large' )[0] . ') center;' : ''; ?>">
	<div class="row small-up-1 medium-up-1 large-up-3">
		<style>.lp-child {padding: 20px;margin: .2em .2em;}@media only screen and (min-width: 768px) {.lp-container {width: 100%;display: flex;align-items: stretch;justify-content: space-around;}.lp-child {width: 100%;margin: 0 .2em;min-height: 100%;padding: 20px;}}</style>
		<div class="lp-container">
			<div class="<?php echo 'bg-cat-' . $wp_query->get_queried_object_id(); ?> lp-child" style="background-color: <?php echo get_taxonomy_color( $wp_query->get_queried_object_id() ); ?>;"> 
				<?php echo $und_event_landingpage_header_left; ?>
			</div>
			<div class="<?php echo 'bg-cat-' . $wp_query->get_queried_object_id(); ?> lp-child" style="background-color: <?php echo get_taxonomy_color( $wp_query->get_queried_object_id() ); ?>;"> 
				<h2><?php echo single_term_title(); ?></h2>
				<?php echo $und_event_landingpage_header_middle; ?>
			</div>
			<div class="<?php echo 'bg-cat-' . $wp_query->get_queried_object_id(); ?> lp-child" style="background-color: <?php echo get_taxonomy_color( $wp_query->get_queried_object_id() ); ?>;"> 
				<?php echo $und_event_landingpage_header_right; ?>
			</div>
		</div>
	</div>
</header>


<?php
/**
 * Start of the the helper table and the input form.
 */
?>
<main style="background: #fff;">
	<div class="wrapper">
		<div id="plan" class="tile-container" style="padding-top:30px;">
			<div class="small-12">
				<form action='#plan' method='post'>
					<label for='location'>Wähle das gewünschte Zuhörbänkli:</label>
					<select name="location" id="location" onchange="this.form.submit()">
						<option <?php echo ( 1 === $selected_location ? 'selected="selected"' : '' ); ?> value="1">Thun Bahnhof</option>
						<option <?php echo ( 2 === $selected_location ? 'selected="selected"' : '' ); ?> value="2">Thun Rathaus</option>
						<option <?php echo ( 3 === $selected_location ? 'selected="selected"' : '' ); ?> value="3">Plaudertisch</option>
						<option <?php echo ( 4 === $selected_location ? 'selected="selected"' : '' ); ?> value="4">Interlaken Zentrum Artos</option>
					</select>
				</form>
			</div>
			<?php
			/**
			 * Print the form and helper table if the form is incomplete.
			 */
			if ( empty( $input_firstname ) && empty( $input_lastname ) && empty( $input_row ) && ! is_array( $input_column ) ) :
				?>

			<div class="small-12">
				<form action='#plan' method='post'>
					<label for='week'>Wähle den gewünschten Wochenplan aus:</label>
					<select name="week" id="week" onchange="this.form.submit()">
						<?php
							/**
							 * Create a dropdown for the respective weekly schedules.
							 */
						foreach ( $weekly_schedules as $row ) {
							/* Vars */
							$html        = '<option value="%d" %s> Wochenplan %d (%s &#x2013; %s)</option>';
							$is_selected = ( $row == $selected_week ? 'selected="selected"' : '' );

							/* Returns the produced string */
							echo sprintf( $html, $row, $is_selected, $row, $start_of_week( $row ), $end_of_week( $row ) );
						}
						?>
					</select>
					<input type="hidden" id="location" name="location" value="<?php echo $selected_location; ?>">
				</form>
			</div>

			<div class="small-12">
				<div style="background-color: #2f6eba; color: #FFFFFF; padding: .42105rem .52632rem;margin-right:-2px;">
					<h3 style="margin-bottom:0;"><?php echo $pretty_name; ?> &#x2013; Wochenplan &#x2013; <?php echo $start_of_week( $selected_week ); ?> <?php echo date( 'Y' ); ?></h3>
				</div>				
				<?php
				/**
				 * The following CSS makes the table scrollable on mobile devices, otherwise the table would be too wide for devices with a viewport below 600px.
				 */
				?>
				<style>@media (max-width: 600px) {#zhb {display: block; max-width: -moz-fit-content; max-width: fit-content; margin: 0 auto; overflow-x: auto; white-space: nowrap;}}</style>
				<table id="zhb" class="table-scroll" style="color: #2f6eba; font-weight:bold;">
					<thead style="color: #2f6eba">
						<tr>
							<th style="border: 2px solid #2f6eba">Tag</th>
							<th style="border: 2px solid #2f6eba">8-10 Uhr</th>
							<th style="border: 2px solid #2f6eba">10-12 Uhr</th>
							<th style="border: 2px solid #2f6eba">12-14 Uhr</th>
							<th style="border: 2px solid #2f6eba">14-16 Uhr</th>
							<th style="border: 2px solid #2f6eba">16-18 Uhr</th>
						</tr>
					</thead>
					<tbody>
						<?php
							/**
							 * Process all lines from Seatable.
							 */
						foreach ( $table_location_rows['rows'] as $row ) {
							/* Print only the data of the respective week */
							if ( date( 'W', strtotime( $row['Datum'] ) ) == $selected_week ) {
								/* Vars */
								$html = '<td style="border: 2px solid #2f6eba">%s</td>';

								/* Returns the produced string */
								echo '<tr>';
									echo sprintf( $html, $row['Wochentag'] . ' (' . $short_date( $row['Datum'] ) . ')' );
									echo sprintf( $html, ( empty( $row['8-10 Uhr'] ) ? ' ' : $helferinn( $row['8-10 Uhr'][0] ) ) );
									echo sprintf( $html, ( empty( $row['10-12 Uhr'] ) ? ' ' : $helferinn( $row['10-12 Uhr'][0] ) ) );
									echo sprintf( $html, ( empty( $row['12-14 Uhr'] ) ? ' ' : $helferinn( $row['12-14 Uhr'][0] ) ) );
									echo sprintf( $html, ( empty( $row['14-16 Uhr'] ) ? ' ' : $helferinn( $row['14-16 Uhr'][0] ) ) );
									echo sprintf( $html, ( empty( $row['16-18 Uhr'] ) ? ' ' : $helferinn( $row['16-18 Uhr'][0] ) ) );
								echo '</tr>';
							}
						}
						?>
					</tbody>
				</table>
			</div>

			<form style="margin: 0 auto;" action='#eingabe' method="post">
				<label for="firstname">Vorname</label>
				<input type="text" name="firstname" id="firstname" />

				<label for="lastname">Nachname</label>
				<input type="text" name="lastname" id="lastname" /> 

				<label for="commitment">Wähle den gewünschten Tag:</label>
				<select name="row" id="commitment">
					<?php
						/**
						 * Create a dropdown with the respective weekdays of the previously selected week.
						 */
					foreach ( $table_location_rows['rows'] as $row ) {
						if ( date( 'W', strtotime( $row['Datum'] ) ) == $selected_week ) {
							/* Vars */
							$html = '<option value="%s">%s (%s)</option>';

							/* Returns the produced string */
							echo sprintf( $html, $row['_id'], $row['Wochentag'], $short_date( $row['Datum'] ) );
						}
					}
					?>
				</select>
				<label>Wähle die gewünschte Zeit:</label>
					<?php
						/**
						 * Create the checkboxes with the respective time of day.
						 */
					foreach ( $table_location_columns['columns'] as $column ) {
						/* Print only the columns that are relevant */
						if ( in_array( $column['name'], array( '8-10 Uhr', '10-12 Uhr', '12-14 Uhr', '14-16 Uhr', '16-18 Uhr' ), true ) ) {
							/* Vars */
							$html = '<input id="%s" name="column[]" value="%s" type="checkbox">
										<label for="%s" class="font-medium text-gray-700">%s</label>';


							/* Returns the produced string */
							echo '<div>';
								echo sprintf( $html, $column['name'], $column['data']['link_id'], $column['name'], $column['name'] );
							echo '</div>';
						}
					}
					?>
				<input type="hidden" id="location" name="location" value="<?php echo $selected_location; ?>">
				<input type="hidden" id="week" name="week" value="<?php echo $selected_week; ?>">
				<input type="submit" id="submit" value="Speichern">
			</form>

			<?php endif; ?>

			<?php
			/**
			 * Send the data from the form to Seatable API.
			 * Process the inputs from the form only if they are complete.
			 */
			if ( ! empty( $input_firstname ) && ! empty( $input_lastname ) && ! empty( $input_row ) && is_array( $input_column ) ) {

				/* Is the helper really present in the helper list? */
				if ( ! empty( $helper_search ) ) {

					/* If multiple checkboxes have been selected. Process the respective time specification */
					foreach ( $input_column as $arr ) {

						/* Go through all the columns of the Seatable API */
						foreach ( $table_location_columns['columns'] as $column ) {

							/* Process only data that match the selection of the checkboxes */
							if ( $column['data']['link_id'] == $arr ) {

								/* Go through all the rows of the Seatable API */
								foreach ( $table_location_rows['rows'] as $row ) {

									/* If the day from the form matches the data from the Seatable API */
									if ( $row['_id'] == $input_row ) {

										/* If there is no entry in the row and column yet. Send the data to the Seatable API */
										if ( empty( $row[ $column['name'] ] ) ) {

											/* Prepare the respective header and body information for the Seatable API */
											$args = array(
												'headers' => array(
													'Authorization' => 'Token ' . $api_base->access_token,
												),
												'body' => array(
													'table_name' => $table_name,
													'other_table_name' => 'HelferInnen',
													'link_id' => $arr,
													'table_row_id' => $input_row,
													'other_table_row_id' => $helper_search,
												),
											);

											/* Send the data from the form to the Seatable API */
											$response   = wp_remote_post( 'https://seatable.generationentandem.ch/dtable-server/api/v1/dtables/e9e6b4c8-7259-4a8d-8f9b-baf72aff462d/links/', $args );
											$save_state = json_decode( $response['body'], true );

											/* Process the Seatable API response */
											if ( $save_state['success'] ) {
												/* Vars */
												$html = '<div class="small-12 success callout"><p><i class="fi-alert"></i> Dein Einsatz am %s zwischen %s wurde gespeichert.</p><p style="text-align: center;"><a href="' . get_permalink( get_the_ID() ) . '">Zurück zum Zuhörbänkli Wochenplan</a></p></div>';

												/* Returns the produced string */
												echo sprintf( $html, $row['Datum'], $column['name'] );
											} else {
												echo '<div class="small-12 alert callout"><p><i class="fi-alert"></i> Beim Reservieren ist ein Fehler aufgetreten. Bitte versuche es später nochmals</p><p style="text-align: center;"><a href="' . get_permalink( get_the_ID() ) . '">Zurück zum Zuhörbänkli Wochenplan</a></p></div>';
											}

											/* If there is already an entry in the row and column, no data will be sent to the API */
										} else {
											/* Vars */
											$html = '<div class="small-12 alert callout"><p><i class="fi-alert"></i> Das Zuhörbänkli ist am %s zwischen %s bereits besetzt.</p><p style="text-align: center;"><a href="' . get_permalink( get_the_ID() ) . '">Zurück zum Zuhörbänkli Wochenplan</a></p></div>';

											/* Returns the produced string */
											echo sprintf( $html, $row['Datum'], $column['name'] );
										}
									}
								}
							}
						}
					}

					/* If no helper was found in the helper list */
				} else {
					echo '<div class="small-12 alert callout">
						<p>
							<i class="fi-alert"></i> 
							<strong>Möchtest du mit machen?</strong><br /> Nach einer Einführung bist du als offizielleR ZuhörerIn dabei. Melde dich bei uns: <a href="mailto:zuhoeren@generationentandem.ch">zuhoeren@generationentandem.ch</a> | 079 836 09 37
						</p>
						<p style="text-align: center;">
							<a href="' . get_permalink( get_the_ID() ) . '">Zurück zum Zuhörbänkli Wochenplan</a>
						</p>
					</div>';
				}
			}
			?>

		</div>
	</div>

	<section class="entry-header" style="padding-top: 50px;padding-bottom: 50px;">
		<div class="post-wrapper">
			<h2><?php echo ( $und_event_landingpage_next_events_on && ! empty( $und_event_landingpage_title_next_events ) ) ? $und_event_landingpage_title_next_events : 'Nächste Events'; ?></h2>
			<?php echo $und_event_landingpage_text_next_events; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
