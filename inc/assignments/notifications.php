<?php

SC_Assignment_Notificatinos::get_instance();
class SC_Assignment_Notificatinos {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the SC_Assignment_Notificatinos
	 *
	 * @return SC_Assignment_Notificatinos
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof SC_Assignment_Notificatinos ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		$this->hooks();
	}

	protected function hooks() {
		add_action( 'sc_assignment_create', array( $this, 'schedule_reminder' ), 10, 2 );
		add_action( 'sc_assignment_create', array( $this, 'new_assignment' ),    10, 2 );
		add_action( 'sc_send_reminders',    array( $this, 'sc_send_reminders' ) );
	}

	public function reminder_cron() {
		if ( wp_next_scheduled( 'sc_reminder_assignments' ) ) {
			return;
		}

		wp_schedule_event( time(), 'daily', 'sc_send_reminders' );
	}

	public function new_assignment( $assignment, $group_id ) {
		$members = groups_get_group_members( array( 'group_id' => $group_id ) )['members'];

		// make sure we have members
		if ( empty( $members ) ) {
			return;
		}

		$ass = new SC_Assignments_Query();
		$ass->assignment = $assignment;

		$group = groups_get_group( 'group_id=' . $group_id );
		$subject = __( 'You have a new assignment in', 'sc' ) . ' ' . bp_get_group_name( $group );
		ob_start(); ?>

		<p><?php _e( 'You have a new assignment in', 'sc' ); ?> <?php echo bp_get_group_name( $group ); ?></p>
		<h4><?php _e( 'Assignment Due: ', 'sc' ); ?><?php $ass->the_date_formatted(); ?></h4>
		<?php $ass->the_lessons(); ?>
		<?php $ass->the_content(); ?>

		<?php
		$content = ob_get_clean();

		foreach( $members as $member ) {
			wp_mail( $member->user_email, $subject, $content );
		}

	}

	public function schedule_reminder( $assignment, $group_id ) {
		$scheduled_assignments = get_option( 'sc_scheduled_ass_reminders', array() );

		$scheduled_assignments[ $group_id ][] = $assignment;
		update_option( 'sc_scheduled_ass_reminders', $scheduled_assignments );
	}

	public function sc_send_reminders() {
		$scheduled_assignments = get_option( 'sc_scheduled_ass_reminders', array() );

		foreach( $scheduled_assignments as $group_id => $group_assignments ) {
			$assignments = array();

			foreach( $group_assignments as $assignment ) {
				$due = strtotime( $assignment['date'] );
				if ( $due > time() + DAY_IN_SECONDS ) {
					continue;
				}

				$assignments[] = $assignment;
			}

			if ( empty( $assignments ) ) {
				continue;
			}

			$ass = new SC_Assignments_Query();
			$group = groups_get_group( 'group_id=' . $group_id );
			$subject = __( 'You have a new assignment due soon', 'sc' ) . ' ' . bp_get_group_name( $group );

			$ass->assignment = $assignment;

			ob_start(); ?>

			<p><?php _e( 'You have a new assignment in', 'sc' ); ?> <?php echo bp_get_group_name( $group ); ?></p>
			<h4><?php _e( 'Assignment Due: ', 'sc' ); ?><?php $ass->the_date_formatted(); ?></h4>
			<?php $ass->the_lessons(); ?>
			<?php $ass->the_content(); ?>

			<?php
			$content = ob_get_clean();
			$members = groups_get_group_members( array( 'group_id' => $group_id ) )['members'];

			foreach( $members as $member ) {
				wp_mail( $member->user_email, $subject, $content );
			}

		}
	}

}