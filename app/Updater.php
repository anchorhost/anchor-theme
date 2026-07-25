<?php
namespace AnchorTheme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Self-updater. Reads manifest.json from the GitHub repo and offers the
 * release zip to WordPress' normal theme update flow.
 */
class Updater {

	public $theme_slug;
	public $version;
	public $cache_key;
	public $cache_allowed;
	public $manifest_url;

	public function __construct() {
		$this->theme_slug    = 'anchor-theme';
		$this->version       = ANCHOR_THEME_VERSION;
		$this->cache_key     = 'anchor_theme_updater';
		$this->cache_allowed = true;
		$this->manifest_url  = 'https://raw.githubusercontent.com/anchorhost/anchor-theme/main/manifest.json';

		add_filter( 'site_transient_update_themes', [ $this, 'update' ] );
		add_action( 'upgrader_process_complete', [ $this, 'purge' ], 10, 2 );
	}

	public function request() {
		$manifest_file  = dirname( __DIR__ ) . '/manifest.json';
		$local_manifest = null;
		if ( file_exists( $manifest_file ) ) {
			$local_manifest = json_decode( file_get_contents( $manifest_file ) );
		}
		if ( ! is_object( $local_manifest ) ) {
			$local_manifest = new \stdClass();
		}

		$remote = get_transient( $this->cache_key );

		if ( false === $remote || ! $this->cache_allowed ) {
			$remote_response = wp_remote_get(
				$this->manifest_url,
				[
					'timeout' => 30,
					'headers' => [ 'Accept' => 'application/json' ],
				]
			);

			if ( is_wp_error( $remote_response )
				|| 200 !== wp_remote_retrieve_response_code( $remote_response )
				|| empty( wp_remote_retrieve_body( $remote_response ) ) ) {
				return $local_manifest;
			}

			$remote = json_decode( wp_remote_retrieve_body( $remote_response ) );
			set_transient( $this->cache_key, $remote, DAY_IN_SECONDS );
		}

		if ( is_object( $remote ) ) {
			return $remote;
		}

		return $local_manifest;
	}

	public function update( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$remote = $this->request();

		if ( $remote && isset( $remote->version ) && version_compare( $this->version, $remote->version, '<' ) ) {
			$transient->response[ $this->theme_slug ] = [
				'theme'        => $this->theme_slug,
				'new_version'  => $remote->version,
				'url'          => $remote->homepage ?? '',
				'package'      => $remote->download_url ?? '',
				'requires'     => $remote->requires ?? '',
				'requires_php' => $remote->requires_php ?? '',
			];
		}

		return $transient;
	}

	public function purge( $upgrader, $options ) {
		if ( $this->cache_allowed
			&& isset( $options['action'], $options['type'] )
			&& 'update' === $options['action']
			&& 'theme' === $options['type'] ) {
			delete_transient( $this->cache_key );
		}
	}
}
