<?php
/**
 * Tweet a post
 *
 * @package   twitter_thing
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 Josh Pollock
 */

namespace calderawp\twitter_core;


class tweet_post extends core {

	/**
	 * The post to tweet
	 *
	 * @since 0.0.2
	 *
	 * @access protected
	 *
	 * @var object|\WP_Post
	 */
	protected $post;

	/**
	 * Text of message to send.
	 *
	 * @since 0.0.2
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected $message_text;

	/**
	 * Constructor for class
	 *
	 * @since 0.0.2
	 *
	 * @param object|\WP_Post|int $post Post object or ID of post to tweet.
	 * @param bool|string $message_field Optional. Meta field to pull tweet text from. If false, the default, post_title is used. Message will be trimmed to 100 characters and then the link will be added.
	 */
	public function __construct( $post, $message_field = false ){
		parent::__construct();
		if ( ! is_null( $this->api ) ) {
			$this->set_post( $post );
			$this->set_message_text( $message_field );
			if ( ! is_null( $this->post ) ) {
				$this->send( $message_field );
			}
		}
	}

	/**
	 * Send the tweet
	 *
	 * @since 0.0.2
	 *
	 * @access protected
	 *
	 */
	protected function send() {
		$data = $this->prepare_data(  );
		$this->send_tweet( $data[ 'message' ], $data[ 'media' ] );
	}

	/**
	 * Prepare data to be tweeted
	 *
	 * @since 0.0.2
	 *
	 * @access protected
	 *
	 * @return array Data to tweet.
	 */
	protected function prepare_data() {
		$url = get_permalink( $this->post->ID );

		/**
		 * Override which image to send.
		 *
		 * @since 0.0.2
		 *
		 * @param int $img The image ID to send
		 * @param int $id The post ID.
		 */
		$img = apply_filters( 'cwp_tweet_core_tweet_media', false, $this->post->ID );

		if ( ! $img ) {
			$img = get_post_thumbnail_id( $this->post->ID, 'large' );
		}

		if ( $img ) {
			$data['media'][] = $img;
		} else {
			$data['media'] = array();
		}

		$data[ 'message' ] = sprintf( '%1s %2s', substr( $this->message_text, 0, 100 ), $url );

		return $data;

	}

	/**
	 * Set the post property
	 *
	 * @since 0.0.2
	 *
	 * @access protected
	 *
	 * @param object|\WP_Post|int $post Post object or ID of post to tweet.
	 */
	protected function set_post( $post ) {
		if ( ! is_object( $post ) && 0 < absint( $post ) ) {
			$post = get_post( $post );
		}

		if ( is_a( $post, '\\WP_POST' ) ) {
			$this->post = $post;
		}
	}

	/**
	 * Set the message_text property
	 *
	 * @since 0.0.2
	 *
	 * @access protected
	 *
	 * @param bool|string $message_field Optional. Meta field to pull tweet text from. If false, the default, post_title is used.
	 */
	protected function set_message_text( $message_field ) {
		if ( is_string( $message_field ) && ! empty( $_message = get_post_meta( $this->post->ID, $message_field, true ) ) ) {
			$this->message_text = $_message;
		}else{
			$this->message_text = $this->post->post_title;
		}

	}

}
