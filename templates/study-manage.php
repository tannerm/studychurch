<?php

/*
 * Template Name: Study Manage
 */

$action       = sc_get( 'action', 'edit' );
$study_id     = absint( sc_get( 'study', 0 ) );
$item_id      = absint( sc_get( 'item', $study_id ) );
$item         = ( $item_id ) ? get_post( $item_id ) : new WP_Post( array() );

$types = array(
	'question_short' => __( 'Short Answer Question', 'sc' ),
	'question_long'  => __( 'Long Answer Question', 'sc' ),
	'content'        => __( 'Content', 'sc' ),
	'assignment'     => __( 'Assignment', 'sc' ),
);

get_header(); ?>
	<div id="studyapp" class="row" data-study="<?php echo esc_attr( $study_id ); ?>" data-user="<?php echo get_current_user_id(); ?>">

		<?php if ( sc_study_edit_step_has_sidebar() ) : ?>
			<?php get_sidebar( 'study-edit' ); ?>
		<?php endif; ?>

		<div class="small-8 column <?php echo ( ! sc_study_edit_step_has_sidebar() ) ? 'small-centered' : ''; ?>" role="main">
			<?php get_template_part( 'partials/study-edit', sc_study_edit_get_current_step( $study_id ) ); ?>
		</div>
	</div>

	<div class="study-map small">
		<div class="row">
			<div class="column small-12">
				<a href="#" class="right"><?php _e( 'Save & Exit', 'sc' ); ?></a>
				<?php echo implode( ' > ', sc_study_get_manage_map( $study_id, sc_study_edit_get_current_step( $study_id ) ) ); ?>
			</div>
		</div>
	</div>
<?php get_footer(); ?>