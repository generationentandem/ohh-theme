<?php
/*
Template Name: Einsatzplan
*/

get_header();

$input_location = filter_input( INPUT_POST, 'location', FILTER_VALIDATE_INT );

/**
 * Retrieving data from the Seatable API.
 * Prepare the respective header information for the Seatable API.
 */
$args = array(
    'method'  => 'GET',
    'headers' => array(
        'Accept'        => 'application/json; charset=utf-8; indent=4',
        'Authorization' => 'Token 8245c78ec16e93d8e508d80fb860885a9c7dfb8c',
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
$response            = wp_remote_request( 'https://seatable.generationentandem.ch/dtable-server/api/v1/dtables/45bdb836-036f-44f8-a615-d33ebdd52a43/rows/?table_name=Einsatzplan&view_name=Wordpress&convert_link_id=true&order_by=Datum&direction=asc&limit=365', $args );
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
$response               = wp_remote_request( 'https://seatable.generationentandem.ch/dtable-server/api/v1/dtables/45bdb836-036f-44f8-a615-d33ebdd52a43/columns/?table_name=Einsatzplan&view_name=WordPress', $args );
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
$response              = wp_remote_request( 'https://seatable.generationentandem.ch/dtable-server/api/v1/dtables/45bdb836-036f-44f8-a615-d33ebdd52a43/rows/?table_name=HelferInnen&view_name=Wordpress', $args );
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

$selected_location = ( intval( $input_location ) > '0' && intval( $input_location ) < '5' ? intval( $input_location ) : '1' );

$input_week      = filter_input( INPUT_POST, 'week', FILTER_VALIDATE_INT );

$current_date  = new DateTime();
$current_week  = date( 'W', strtotime( $current_date->format( 'Y-m-d' ) ) );
$selected_week = ( intval( $input_week ) > '0' && intval( $input_week ) < '53' ? intval( $input_week ) : intval( $current_week ) );

?>

<main style="background: #fff;">
    <div class="wrapper">
        <div id="plan" class="title-container" style="padding-top: 30px;">
            <div class="small-12">
                <form action='#plan' method='post'>
                    <label for="shift">Wähle die gewünschte Schicht:</label>
                    <select name="shift" id="shift" onchange="this.form.submit()">
                        <option <?php echo ( 1 === $selected_location ? 'selected="selected"' : '' ); ?> value="1">Vormittag</option>
                        <option <?php echo ( 2 === $selected_location ? 'selected="selected"' : '' ); ?> value="2">Nachmittag</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="small-12">
            <form action="#plan" method="post">
                <label for='week'>Wähle den gewünschten Wochenplan aus:</label>
                <select name="week" id="week" onchange="this.form.submit()">
                    <?php
                        foreach($weekly_schedules as $row) {
                            $html = '<option value="%d" %s> Wochenplan %d (%s &#x2013; %s)</option>';
                            $is_selected = ($row == $selected_week ? 'selected="selected"' : '');
                        }
                    ?>
                </select>
                <input type="hidden" id="location" name="location" value="<?php echo $selected_location; ?>">
            </form>
        </div>

        <div class="small-12">
            <div style="background-color: #2f6eba; color: #FFFFFF; padding: .42105rem .52632rem;margin-right:-2px;">
                <h3 style="margin-bottom:0;">Höchhus &#x2013; Wochenplan &#x2013; <?php //echo $start_of_week( $selected_week ); ?> <?php echo date( 'Y' ); ?></h3>
            </div>
            <style>@media (max-width: 600px) {#zhb {display: block; max-width: -moz-fit-content; max-width: fit-content; margin: 0 auto; overflow-x: auto; white-space: nowrap;}}</style>
            <table id="zhb" class="table-scroll" style="color: #2f6eba; font-weight:bold;">
                <thead style="color: #2f6eba">
                <tr>
                    <th style="border: 2px solid #2f6eba">Tag</th>
                    <th style="border: 2px solid #2f6eba">Vormittag (08:00 - 13:00)</th>
                    <th style="border: 2px solid #2f6eba">Nachmittag (12:45 - 17:30)</th>
                </tr>
                </thead>
                <tbody>

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

            </select>

            <label></label>

            <input type="hidden" id="location" name="location" value="<?php echo $selected_location; ?>">
            <input type="hidden" id="week" name="week" value="<?php echo $selected_week; ?>">
            <input type="submit" id="submit" value="Speichern">
        </form>
    </div>
</main>

<?php
get_footer();
?>

// 45bdb836-036f-44f8-a615-d33ebdd52a43
