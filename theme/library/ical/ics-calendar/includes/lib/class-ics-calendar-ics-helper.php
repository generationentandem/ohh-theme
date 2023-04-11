<?php

class ICS_Calendar_ICS_Helper {

	private $temp_file;
	private $css_colors;

	public function __construct() {
		$this->temp_file = dirname( dirname( __DIR__ ) ) . '/temp';
	}


	private function download_ics( $cal ) {
		//$ics_file = get_option('ics_calendar_ics_url');
		//$ics_timeout = get_option('ics_calendar_ics_timeout');
		$ics_file    = $cal['ical_url'];
		$ics_timeout = 10;
		$filetime    = filemtime( $this->temp_file . '/' . $cal['name'] . '.ics' );
		$now         = time();
		if ( ( $filetime + ( $ics_timeout * 60 ) ) < $now || ! file_exists( $this->temp_file . '/' . $cal['name'] . '.ics' ) ) {
			$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
			$ch    = curl_init();
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch, CURLOPT_VERBOSE, true );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_USERAGENT, $agent );
			curl_setopt( $ch, CURLOPT_URL, $ics_file );
			$result = curl_exec( $ch );
			file_put_contents( $this->temp_file . '/' . $cal['name'] . '.ics', $result );
		}
	}

	/**
	 * @return array
	 */
	public function get_ics() {
		$calendars        = [];
		$flatevents       = [];
		$this->css_colors = "";
		$ical_urls        = get_field( 'ical_urls', 'option', true );
		foreach ( $ical_urls as $cal ) {
			$this->download_ics( $cal );
			$calendars[ $cal['name'] ] = new ical( $this->temp_file . '/' . $cal['name'] . '.ics' );
			foreach ( $calendars[ $cal['name'] ]->cal['VEVENT'] as $event ) {
				date_default_timezone_set( $calendars[ $cal['name'] ]->cal['VCALENDAR']['X-WR-TIMEZONE'] );
				$event['und_calendar'] = $cal['name'];
				$event['und_start']    = new DateTime( $event['DTSTART'], new DateTimeZone( 'UTC' ) );
				$event['LOCATION']     = str_replace( '\\', '', $event['LOCATION'] );
				if ( empty( $event['DTEND'] ) || strlen( $event['DTEND'] ) == 8 ) {
					$event['und_allday'] = true;
				} else {
					$event['und_allday'] = false;
					$event['und_end']    = new DateTime( $event['DTEND'] );
				}
				$diff = $event['und_start']->diff( new DateTime( get_field( 'ical_start', 'option', true ) ) );
				if ( $diff->days >= 0 && $diff->invert == 1 || $diff->days == 0 && $diff->invert == 0 ) {
					array_push( $flatevents, $event );
				}
			}
			$this->css_colors .= ".calendar-" . $cal['name'] . "{background:" . $cal['farbe'] . ";}";
		}
		usort( $flatevents, 'sortEvent' );

		return array_slice( $flatevents, 0, get_field( 'ical_eventcount', 'option', true ) );
	}

	public function splitDate( $all_events ) {
		$year = [];
		foreach ( $all_events as $event ) {

			$year[ $event['und_start']->format( 'Y' ) ][ $event['und_start']->format( 'm' ) ][ $event['und_start']->format( 'd' ) ][] = $event;
		}

		return $year;
	}

	public function renderCalendar() {
		$events_years = $this->splitDate( $this->get_ics() );
		echo '<style>
' . $this->css_colors . '
        body {
            font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
        }
        ul{
            list-style: none;
            margin: 0;
            padding: 0;
        }

        ul.daylist li.day ,
        ul.daylist li.year {
            display: flex;
            padding: 5px 0;
            margin-bottom: 0;
        }

        ul.daylist li.year {
            background: #282828;
            color: #ffffff;
        }

        ul.daylist li header {
            min-width: 100px;
            padding: 0 10px;
            font-weight: 600;
        }
        ul.daylist li header .weekday {
            font-weight: 400;
        }

        ul.daylist li + li.day {
            border-top: 1px solid rgba(0,0,0,0.2);
        }

        ul.daylist li.day:nth-child(2n) {
            background: rgba(0,0,0,0.05);
        }

        ul.eventlist {
            flex: auto;
        }

        ul.eventlist li time {
            min-width: 65px;
            -webkit-font-feature-settings: "tnum";
            font-feature-settings: "tnum";
            color: rgba(0,0,0,0.81);
        }

        ul.eventlist li {
            display: flex;
            padding: 5px;
            border-radius: 5px;
            margin-right: 5px;
            margin-bottom: 0;
            border: 1px solid rgba(0,0,0,0.2);
        }

        ul.eventlist li + li {
            margin-top: 5px;
        }
        h3  {
            font-size: 16px;
            padding: 0;
            margin: 0;
            font-weight: 600!important;
            color: black;
        }
        .daylist .description h3 {
            margin: 0!important;
        }
        .daylist .description span {
            color: rgba(0,0,0,0.8);
        }
        /*.daylist div.calendercat {
		position: relative;
		width:20px;
        }
        .daylist div.calendercat span{
		transform: rotate(90deg) translateX(-50%) translateY(-50%);
    color: #00000069;
    display: block;
    position: absolute;
    top: 50%;
    left: -50%;
    line-height: 2;
    height: 20px;
        }*/
        ul.daylist li.hidden {
            display: none;
        }
    </style>';
		echo '<ul class="daylist">';
		foreach ( $events_years as $year => $months ) {
			foreach ( $months as $month => $days ) {
				foreach ( $days as $day => $events ) {
					$daystring = $year . '-' . $month . '-' . $day;
					$date      = DateTime::createFromFormat( 'Y-m-d', $daystring );
					echo '<li class="day">
				                <header>
					                <time><span class="weekday">' . i18ndate( $date, '%A' ) . '</span><br>' . i18ndate( $date, '%e. %b.' ) . '</time>
					            </header>
					            <ul class="eventlist">';
					foreach ( $events as $event ) {
						$this->renderEvent( $event );
					}
					echo '</ul></li>';
				}
			}

		}
		echo '</li>';
	}

	public function renderYear() {
		$events_year = $this->splitDate( $this->get_ics() );
		var_dump( $events_year );
	}

	public function renderDay() {
		$events_year = $this->splitDate( $this->get_ics() );
		var_dump( $events_year );
	}

	public function renderEvent( $event ) {
		if ( $event['und_allday'] ) {
			echo '<li class="event allday calendar-' . $event['und_calendar'] . '"><time></time><div class="description"><h3>' . $event['SUMMARY'] . '</h3>';
			if ( ! empty( $event['LOCATION'] ) ) {
				echo '<span>' . $event['LOCATION'] . '</span>';
			}
			echo '</div></li>';
			//echo '</div><div class="calendercat"><span>'.$event['und_calendar'].'</span></div></li>';
		} else {
			echo '<li class="event calendar-' . $event['und_calendar'] . '"><time>' . i18ndate( $event['und_start'], '%H:%M' ) . '-<br>' . i18ndate( $event['und_end'], '%H:%M' ) . '</time><div class="description"><h3>' . $event['SUMMARY'] . '</h3>';
			if ( ! empty( $event['LOCATION'] ) ) {
				echo '<span>' . $event['LOCATION'] . '</span>';
			}
			echo '</div></li>';
			//echo '</div><div class="calendercat"><span>'.$event['und_calendar'].'</span></div></li>';
		}
	}

}

function sortEvent( $a, $b ) {
	return $a["und_start"]->getTimestamp() - $b["und_start"]->getTimestamp();
}

function i18ndate( $datetime, $pattern ) {
	return strftime($pattern, $datetime->getTimestamp());
}
