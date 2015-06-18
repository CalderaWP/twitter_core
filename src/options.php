<?php
/**
 * @TODO What this does.
 *
 * @package   @TODO
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 Josh Pollock
 */

namespace calderawp\twitter_core;


class options {

	protected  $prefix = 'cwp_twthing_';

	protected $auth_keys;

	public function  __construct() {
		$this->set_auth_keys();
	}

	public function get_auth_keys() {
		return $this->auth_keys;
	}

	protected function set_auth_keys() {
		$this->auth_keys = wp_parse_args(
			get_option( $this->prefix . 'keys', array() ),
			array(
				'consumer_key' => false,
				'consumer_secret' => false,
				'access_token' => false,
				'access_token_secret' => false,
			)
		);

	}

	public  function save_auth( $consumer_key, $consumer_secret, $access_token, $access_token_secret ) {
		$keys[ 'consumer_key' ] = $consumer_key;
		$keys[ 'consumer_secret' ]  = $consumer_secret;
		$keys[ 'access_token' ] = $acces_token;
		$keys[ 'access_token_secret' ] = $access_token_secret;
		update_option( $this->prefix . 'keys', $keys );

	}
}
