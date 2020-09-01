<?php
/**
 * Renders the Campaign Creator metabox.
 *
 * @author    Eric Daams
 * @copyright Copyright (c) 2020, Studio 164a
 * @package   Charitable/Admin Views/Metaboxes
 * @since     1.2.0
 * @version   1.6.45
 */

global $post, $wpdb;

/* If no post_author is set yet, WP returns 1, but we need to know if no creator is set. */
$creator_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_author FROM $wpdb->posts WHERE ID = %d LIMIT 1", $post->ID ) );
$campaign   = new Charitable_Campaign( $post );
$authors    = get_users(
	[
		'orderby' => 'display_name',
		'fields'  => [ 'ID', 'display_name' ],
	]
);

?>
<div id="charitable-campaign-creator-metabox-wrap" class="charitable-metabox-wrap">
	<?php
	if ( $creator_id ) :
		$creator = new Charitable_User( $creator_id );
		?>
		<div id="campaign-creator" class="charitable-media-block">
			<div class="creator-avatar charitable-media-image">
				<?php echo $creator->get_avatar(); ?>
			</div><!--.creator-avatar-->
			<div class="creator-facts charitable-media-body">
				<h3 class="creator-name"><a href="<?php echo admin_url( 'user-edit.php?user_id=' . $creator->ID ); ?>"><?php printf( '%s (%s %d)', $creator->display_name, __( 'User ID', 'charitable-ambassadors' ), $creator->ID ); ?></a></h3>
				<p><?php printf( '%s %s', _x( 'Joined on', 'joined on date', 'charitable-ambassadors' ), date_i18n( 'F Y', strtotime( $creator->user_registered ) ) ); ?></p>
				<ul>
					<li><a href="<?php echo get_author_posts_url( $creator->ID ); ?>"><?php _e( 'Public Profile', 'charitable-ambassadors' ); ?></a></li>
					<li><a href="<?php echo admin_url( 'user-edit.php?user_id=' . $creator->ID ); ?>"><?php _e( 'Edit Profile', 'charitable' ); ?></a></li>
				</ul>
			</div><!--.creator-facts-->
		</div><!--#campaign-creator-->
	<?php endif; ?>
	<div id="charitable-post-author-wrap" class="charitable-metabox charitable-select-wrap">
		<label for="post_author"><?php _e( 'Change the campaign creator' ); ?></label>
		<select name="post_author">
			<option value="0" <?php selected( $creator_id, 0 ); ?>><?php _e( 'Select a user', 'charitable' ); ?></option>
			<?php foreach ( $authors as $author ) : ?>
			<option value="<?php echo esc_attr( $author->ID ); ?>" <?php selected( $creator_id, $author->ID ); ?>><?php echo $author->display_name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div><!--#charitable-campaign-description-metabox-wrap-->
