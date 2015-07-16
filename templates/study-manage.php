<?php

/*
 * Template Name: Study Manage
 */

$study_id = ( empty( $_GET['study'] ) ) ? 0 : absint( $_GET['study'] );
$item_id = ( empty( $_GET['item'] ) ) ? $study_id : absint( $_GET['item'] );
$item    = get_post( $item_id );

$types = array(
	'question_short' => __( 'Short Answer Question', 'sc' ),
	'question_long'  => __( 'Long Answer Question', 'sc' ),
	'content'        => __( 'Content', 'sc' ),
	'assignment'     => __( 'Assignment', 'sc' ),
);

get_header(); ?>

	<div class="row">

		<div id="secondary" class="widget-area large-4 small-12 columns" role="complementary">
			<a href="<?php echo add_query_arg( array( 'action' => 'edit', 'study' => absint( $study_id ) ), get_the_permalink() ); ?>" class="right"><i class="fa fa-pencil"></i></a>
			<h3><?php echo get_the_title( $study_id ); ?></h3>

			<ul class="table-of-contents">
				<?php sc_study_manage_index( $study_id ); ?>
			</ul>

		</div><!-- #secondary large-3-->

		<div class="small-8 column" role="main">
			<label for="study-title"><?php _e( 'Study Title', 'sc' ); ?></label>
			<input type="text" id="study-title" name="study-title" value="<?php echo esc_attr( get_the_title( $item_id ) ) ; ?>" placeholder="<?php _e( 'Enter study title here...', 'sc' ); ?>" />

			<label for="study-thesis"><?php _e( 'Study Thesis', 'sc' ); ?></label>
			<textarea id="study-thesis" name="study-thesis" rows="5"><?php echo esc_textarea( $item->post_excerpt ); ?></textarea>

			<label for="study-description"><?php _e( 'Study Description', 'sc' ); ?></label>
			<textarea id="study-description" name="study-description" rows="15"><?php echo esc_textarea( $item->post_content ); ?></textarea>


			<?php foreach( get_pages( 'post_type=sc_study&child_of=' . $item_id ) as $item ) : ?>
				<div class="panel">
					<?php wp_editor( $item->post_content, 'content_' . $item->ID, array( 'teeny' => true ) ); ?>
					<select>
						<?php foreach( $types as $type => $label ) : ?>
							<option value="<?php echo esc_attr( $type ); ?>" <?php selected( sc_get_data_type( $item->ID ), $type ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endforeach; ?>

			<input type="submit" value="Save" />

		</div>
	</div>


<?php get_footer(); ?>