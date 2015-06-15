<?php
/**
 * Tweet a post based on WP_Query Args
 *
 * @package   tweet_thing
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 Josh Pollock
 */

namespace calderawp\twitter_core;


class tweet_by_query {

	/**
	 * Tweet the first post from a WP_Query query
	 *
	 * @since 0.0.2
	 *
	 * @param array $args WP_Query arguments
	 * @param string|bool $message_field Optional. Field argument for tweet_post class
	 */
	public function __construct( $args, $message_field = false ) {
		$post = $this->first_post_from_query( $args );
		if ( is_object( $post ) ) {
			new tweet_post( $post, $message_field );
		}
	}

	/**
	 * Do the query for one post.
	 *
	 * Note: If $args[ 'orderby' ] is not set. Query will be random, probably should leave it that way. Limit is set to 1.
	 *
	 * @param array $args WP_Query arguments
	 *
	 * @return \WP_Post|void The first post, if there were any matching the post
	 */
	protected function first_post_from_query( $args ) {
		if ( ! isset( $args[ 'orderby' ] ) ){
			$args[ 'orderby' ] = 'rand';
		}

		$args[ 'posts_per_page' ] = 1;

		$query = new \WP_Query( $args );
		if ( $query->have_posts() ) {
			return $query->posts[0];
		}

	}

}
