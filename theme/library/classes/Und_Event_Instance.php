<?php


class Und_Event_Instance {

	/**
	 * @var Und_Event
	 */
	public $event;

	/**
	 * @var DateTime
	 */
	public $start;

	/**
	 * @var DateTime
	 */
	public $end;

	public $allday = false;

	/**
	 * Und_Event_Instance constructor.
	 *
	 * @param $instance array
	 */
	public function __construct($event, $instance) {
		$this->event = $event;
		if(!empty($instance['und_event_timetable_instancestart'])){
			$this->start = DateTime::createFromFormat('U',$instance['und_event_timetable_instancestart']);
		}
		if(!empty($instance['und_event_timetable_instanceend'])){
			$this->end = DateTime::createFromFormat('U',$instance['und_event_timetable_instanceend']);
		}
	}

	public function formatTimetableInstance( ) {
		return $this->start->format( 'H:i' ) . '</span> - <span>' . $this->end->format( 'H:i' );
	}

	public function formatTileInstance() {
		setlocale(LC_ALL, 'de_CH');
		
		if ($this->event->is_festival) {
			if ($this->allday) {
				return strftime('%A', $this->start->getTimestamp());
			} else {
				if (str_ends_with($this->start->format( 'H:i' ), '00')) {
					return strftime('%A, %H', $this->start->getTimestamp());
				} else {
					return strftime('%A, %H.%M', $this->start->getTimestamp());
				}
			}
		} else {
			return strftime('%A, %e. %b', $this->start->getTimestamp());
		}
	}

	public function getTime() {
		if($this->event->is_festival){
			return $this->event->getOccurence();
		} else {
			return $this->formatTileInstance();
		}
	}

	public function start(  ) {
		return $this->start->getTimestamp();
	}

	public function end(  ) {
		return $this->end->getTimestamp();
	}
}
