<?php


namespace calderawp\twitter_core;

use Abraham\TwitterOAuth\TwitterOAuth;

class core {

	/**
	 * The API
	 *
	 * @access protected
	 *
	 * @since 0.0.1
	 *
	 * @var object|\Abraham\TwitterOAuth\TwitterOAuth
	 */
	protected $api;

	/**
	 * The options for this plugin
	 *
	 * @access protected
	 *
	 * @since 0.0.1
	 *
	 * @var object|\calderawp\twitter_thing\options
	 */
	protected $options;

	/**
	 * Auth keys for twitter app
	 *
	 * @access protected
	 *
	 * @since 0.0.1
	 *
	 * @var array
	 */
	protected $auth_keys;

	/**
	 * Constructor for class
	 *
	 * @since 0.0.1
	 */
	public function __construct() {
		$this->set_options();
		$this->set_api();
	}

	/**
	 * Set options and auth key properties
	 *
	 * @access protected
	 *
	 * @since 0.0.1
	 */
	protected function set_options() {
		$this->options = new keys( 'cwp_tweet_core' );
		$this->auth_keys = $this->options->get_auth_keys();
	}

	/**
	 * Connect to Twitter and set api property if connected
	 *
	 * @access protected
	 *
	 * @since 0.0.1
	 */
	protected function set_api() {

		$twitter           = new TwitterOAuth(
			$this->auth_keys[ 'consumer_key' ],
			$this->auth_keys[ 'consumer_secret' ],
			$this->auth_keys[ 'access_token' ],
			$this->auth_keys[ 'access_token_secret' ]
		);

		$connected = $twitter->get("account/verify_credentials");
		if ( $connected ) {
			$this->api = $twitter;
		}

	}

	/**
	 * Send a tweet with pictures of my cats to see if this works.
	 *
	 * @access protected
	 *
	 * @since 0.0.1
	 *
	 * @param string $message Text of tweet.
	 * @param array $images Optional. An array of image IDs.
	 */
	protected function send_tweet( $message, $images = array() ) {
		$media_ids = array();
		if ( ! empty( $images ) ) {
			foreach ( $images as $image ) {
				$img = wp_get_attachment_image_src( $image, 'large' );
				if ( is_array( $img ) ) {
					$media = $this->api->upload('media/upload', array('media' => $img[0] ) );
					$media_ids[] = $media->media_id_string;
				}
			}
		}

		$params= array(
			'status' => $message,
		);

		if ( ! empty( $media_ids ) ) {
			$params ['media_ids'] = implode( ',', $media_ids );
		}

		$result = $this->api->post( "statuses/update", $params );

		/**
		 * Runs after a tweet is sent
		 *
		 * @since 0.0.1
		 *
		 * @param object $result Result from API call.
		 * @param array $params parameters for sending the tweet.
		 */
		do_action( 'calderawp_twitter_thing_tweet_sent', $result, $params );

	}

}
