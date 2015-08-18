<?php

class SC_Assignments_Query {

	public static $_key = 'sc_assignments';

	public $count = 0;

	public $current = 0;

	public $assignments = array();

	public $assignment = null;

	public $query_args = array();

	public function __construct( $args = array() ) {

		$this->query_args = wp_parse_args( $args, array(
			'group_id'    => bp_get_current_group_id(),
			'date_start'  => time(),
			'date_finish' => null,
			'count'       => -1,
		) );

		if ( empty( $this->query_args['group_id'] ) ) {
			return $this;
		}

		return $this->parse_query();
	}

	/**
	 * Get the assignments and return those that are valid
	 *
	 * @return $this
	 */
	protected function parse_query() {

		/** Get the assignment meta */
		if ( ! is_array( $this->query_args['group_id'] ) ) {
			$this->assignments = groups_get_groupmeta( (int) $this->query_args['group_id'], self::$_key, true );
		} else {
			foreach( $this->query_args['group_id'] as $group_id ) {
				$this->assignments = groups_get_groupmeta( (int) $this->query_args['group_id'], self::$_key, true );
			}
		}

		if ( empty( $this->assignments ) ) {
			$this->assignments = array();
		}

		ksort( $this->assignments );

		$count = 0;
		foreach( $this->assignments as $key => $assignment ) {
			$timestamp = strtotime( $assignment['date'] );

			/** Test start date */
			if ( ! empty( $this->query_args['date_start'] ) && absint( $this->query_args['date_start'] ) > $timestamp ) {
				unset( $this->assignments[ $key ] );
				continue;
			}

			/** Test finish date */
			if ( ! empty( $this->query_args['date_finish'] ) && absint( $this->query_args['date_finish'] ) < $timestamp ) {
				unset( $this->assignments[ $key ] );
				continue;
			}

			if ( $this->query_args['count'] > 0 && ++$count > $this->query_args['count'] ) {
				unset( $this->assignments[ $key ] );
			}

		}

		$this->count = count( $this->assignments );

		return $this;
	}

	/**
	 * Does this query have assignments?
	 *
	 * @return bool
	 */
	public function have_assignments() {
		return (bool) reset( $this->assignments );
	}

	public function the_assignment() {
		$this->assignment = current( $this->assignments );
		next( $this->assignments );

		return $this->assignment;
	}

	/**
	 * echo the current assignments key
	 */
	public function the_key() {
		echo $this->get_the_key();
	}

		/**
		 * Get the current assignments key
		 * @return mixed
		 */
		public function get_the_key() {
			return $this->assignment['key'];
		}

	/**
	 * Print the assignment content
	 */
	public function the_content() {
		echo apply_filters( 'the_content', wp_kses_post( $this->get_the_content() ) );
	}
		/**
		 * Get the assignment content
		 *
		 * @return mixed
		 */
		public function get_the_content() {
			return $this->assignment['content'];
		}

	/**
	 * Print the assignment due date
	 * @param string $d
	 */
	public function the_date( $d = 'l, F j' ) {
		echo $this->get_the_date( $d );
	}
		/**
		 * Get the assignment due date
		 *
		 * @param string $d
		 *
		 * @return int
		 */
		public function get_the_date( $d = 'l, F j' ) {
			return date( $d, strtotime( $this->assignment['date'] ) );
		}

	/**
	 * Print the formatted date
	 */
	public function the_date_formatted() {
		$date = $this->get_the_date( 'U' );
		printf( '%s <span class="day">%s</span>', date( 'l, F', $date ), date( 'j', $date ) );
	}

	/**
	 * Print the lessons for this assignment
	 */
	public function the_lessons() {
		if ( ! $this->get_the_lessons() ) {
			return;
		} ?>

		<ul class="assignment-lessons">
			<?php foreach( $this->get_the_lessons() as $lesson ) : ?>
				<li><a href="<?php echo get_the_permalink( $lesson ); ?>"><?php echo get_the_title( $lesson ); ?></a></li>
			<?php endforeach; ?>
		</ul>
		<?php
	}

		/**
		 * Get the lessons for this assignment
		 *
		 * @return array|bool
		 */
		public function get_the_lessons() {
			if ( empty( $this->assignment['lessons'] ) ) {
				return false;
			}

			return array_map( 'absint', $this->assignment['lessons'] );
		}
}

/**
 * Print the assignment permalink for the current group
 *
 * @param $group
 */
function sc_the_assignment_permalink( $group = false ) {
	echo esc_url( sc_get_the_assignment_permalink( $group ) );
}
	/**
	 * Get the permalink for assignments
	 *
	 * @param $group
	 *
	 * @return string
	 */
	function sc_get_the_assignment_permalink( $group = false ) {
		global $groups_template;

		if ( empty( $group ) ) {
			$group =& $groups_template->group;
		}

		return esc_url( trailingslashit( bp_get_group_permalink( $group ) . 'assignments' ) );
	}