<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$events_label_singular = tribe_get_event_label_singular();
$events_label_plural = tribe_get_event_label_plural();

$event_id = get_the_ID();

?>

<div class="container">
    <div class="row">
        <div class="col-sm-12">
                <p class="tribe-events-back">
                <a href="<?php echo esc_url( tribe_get_events_link() ); ?>" class="btn btn-primary"> <?php printf( __( '&laquo; All %s', 'tribe-events-calendar' ), $events_label_plural ); ?></a>
            </p>
        </div>
    </div>
	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
        	<!-- Notices -->
            <?php tribe_events_the_notices() ?>
            <?php the_title( '<h1>', '</h1>' ); ?>
            <?php echo tribe_events_event_schedule_details( $event_id, '<h4>', '</h4>' ); ?>
	
        	<!-- #tribe-events-header -->
        	<?php while ( have_posts() ) :  the_post(); ?>
        		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        			<div>
                        <img src="<?php echo custom_feature_image('full', 600); ?>" class="img-responsive">
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
        			     <?php the_content(); ?>
                        </div>
                        <div class="col-sm-4">
            <!-- Event meta -->
                    <?php
                    /**
                     * The tribe_events_single_event_meta() function has been deprecated and has been
                     * left in place only to help customers with existing meta factory customizations
                     * to transition: if you are one of those users, please review the new meta templates
                     * and make the switch!
                     */
                    if ( ! apply_filters( 'tribe_events_single_event_meta_legacy_mode', false ) ) {
                        tribe_get_template_part( 'modules/meta' );
                    } else {
                        echo tribe_events_single_event_meta();
                    }
                    ?>
        </div>

                    </div>
        		</article> 
        	<?php endwhile; ?>
            <p class="tribe-events-back">
                <a href="<?php echo esc_url( tribe_get_events_link() ); ?>" class="btn btn-primary"> <?php printf( __( '&laquo; All %s', 'tribe-events-calendar' ), $events_label_plural ); ?></a>
            </p>

        </div>
        
    </div>
</div>
