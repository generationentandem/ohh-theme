<?php
/** @var Und_Event_Instance $instance */
$instance = get_query_var('und_event_instance');
?>
<article <?php post_class( 'tile image-tile ' . $instance['slug'] ) ?>
	style="background-image: url('<?php echo $instance['_embedded']['wp:featuredmedia'][0]['source_url'] ?>')">
	<a class="tile-inner" href="/aktueller-event?event=<?php echo $instance['id'] ?>">
		<h3 class="tile-title"><?php echo $instance['title']['rendered'] ?></h3>
		<?php
        echo '<h5 class="tile-location tile-info tile-cat-' . $instance['slug'] . '" style="background-color: #a20303; color: white">Offenes Höchhus</h5>';

		$today = time();
		$time = strtotime($instance['acf']['und_event_timetable'][0]['und_event_timetable_instancestart']);
		$format = '%A, %d.%m.%y';
		/*if (($time - (10*60*60*24)) < $today && $time >= $today){
			$format .= '%R';
		}*/
		$format .= ', %H';
        if (strftime("%M", $time) != 00) {
            $format .= '.%M';
        }

		$start = strftime($format, $time);

		echo '<h5 class="tile-time tile-info">' . $start . ' Uhr' . '</h5>';
		unset($instance);
		?>
	</a>
</article>
