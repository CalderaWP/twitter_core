<?php
/**
 * Get the save auth keys
 *
 * @package   twitter_core
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 Josh Pollock
 */

namespace calderawp\twitter_core;


class keys {

	/**
	 * Prefix for the option that stores these keys.
	 *
	 * @since 0.1.0
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected $prefix;

	/**
	 * The retrieved keys.
	 *
	 * @since 0.1.0
	 *
	 * @access protected
	 *
	 * @var array
	 */
	protected $auth_keys;

	/**
	 * Constructor for class.
	 *
	 * @since 0.1.0
	 *
	 * @param string $prefix Options prefix for storing/ getting keys.
	 */
	public function  __construct( $prefix ) {
		$this->set_prefix( $prefix );
		$this->set_auth_keys();
	}

	/**
	 * Get the retrieved auth keys.
	 *
	 * Note indexes may be empty if not saved.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_auth_keys() {
		return $this->auth_keys;
	}

	/**
	 * Set $prefix property.
	 *
	 * @since 0.1.0
	 *
	 * @access protected
	 *
	 * @param string $prefix
	 */
	protected function set_prefix( $prefix ) {
		$this->prefix = $prefix;
	}

	/**
	 * Set $auth_keys property.
	 *
	 * @since 0.1.0
	 *
	 * @access protected
	 */
	protected function set_auth_keys() {
		$keys = wp_parse_args(
			get_option( $this->prefix . 'keys', array() ),
			array(
				'consumer_key' => false,
				'consumer_secret' => false,
				'access_token' => false,
				'access_token_secret' => false,
			)
		);
		/**
		 * Filter author keys array.
		 *
		 * @since 0.1.0
		 *
		 * @param array $keys The auth keys.
		 * @param string $prefix Storage prefix, IE which set of keys are these.
		 */
		$this->auth_keys = apply_filters( 'cwp_tweet_core_auth_keys', $keys, $this->prefix );
	}

	/**
	 * Save auth keys.
	 *
	 * @since 0.1.0
	 *
	 * @param string $consumer_key
	 * @param string $consumer_secret
	 * @param string $access_token
	 * @param string $access_token_secret
	 */
	public function save_auth( $consumer_key, $consumer_secret, $access_token, $access_token_secret ) {
		$keys[ 'consumer_key' ] = $consumer_key;
		$keys[ 'consumer_secret' ]  = $consumer_secret;
		$keys[ 'access_token' ] = $access_token;
		$keys[ 'access_token_secret' ] = $access_token_secret;
		update_option( $this->prefix . 'keys', $keys );

	}

}
