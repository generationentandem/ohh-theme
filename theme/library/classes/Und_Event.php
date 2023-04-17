<?php


class Und_Event
{
	/**
	 * @var WP_Post
	 */
	public $post;

	/**
	 * @var bool
	 */
	public $is_festival;

	/**
	 * @var string
	 */
	public $location;

	/**
	 * @var WP_Term
	 */
	public $category;

	/**
	 * @var Und_Event_Instance[]
	 */
	public $instances;

	public $occurences = array();

	public $occurenceString = '';

	public $locationClass = '';

	private $locationIndicator = array(
		'Aussenbühne' => 'outside_stage',
		'Innenbühne' => 'inside_stage',
		'Pavillon' => 'pavillon',
		'Märit' => 'market',
		'Foodcorner' => 'food',
		'Spielbereich' => 'playarea'
	);

	/**
	 * Und_Tile constructor.
	 *
	 * @param WP_Post $post
	 */
	public function __construct(WP_Post $post, $festival = false)
	{
		$post->und_event = $this;
		$this->post = $post;
		$acf_fields = (object)get_fields($post->ID);
		if (!empty($acf_fields)) {
			foreach ($acf_fields->und_event_timetable as $instance) {
				$this->instances[] = new Und_Event_Instance($this, $instance);
			}

            $this->category = get_the_terms($post, 'und_eventcat')[0];

			$this->location = $acf_fields->und_event_place;
			$this->setLocationClass($acf_fields->und_event_place);

		}
		$this->is_festival = $festival;
	}

	public function getOccurence()
	{
		if (empty($this->occurenceString)) {
			foreach ($this->instances as $instance) {
				$this->occurences[] = $instance->formatTileInstance();
			}
			$this->occurences = array_unique($this->occurences);
			$this->occurenceString = preg_replace('/(?<=Fr|Sa)(eitag|mstag)(?=, \d?\d:\d\d & )|(?<=& (?:Fr|Sa))(eitag|mstag)(?=, \d\d?:\d\d)/', '', join(' & ', $this->occurences));
		}
		return $this->occurenceString. ' Uhr';
	}

	/**
	 * @param string $event_location
	 */
	public function setLocationClass($event_location): void
	{
		if (array_key_exists($event_location, $this->locationIndicator)) {
			if ($this->category->slug == 'food') {
				$this->locationClass = $this->category->slug;
			} elseif ($this->category->slug == 'market') {
				$this->locationClass = $this->category->slug;
			} else {
				$this->locationClass = $this->locationIndicator[$event_location];
			}
		} else {
			$this->locationClass = 'other';
		}
	}

}
