<?php

add_filter( 'plugin_row_meta', 'aiows_range_plu_plugin_meta', 99, 2 );

function aiows_range_plu_plugin_meta( $plugin_meta, $plugin_file ) {
	list( $slug ) = explode( '/', $plugin_file );


	$slug_hash = md5( $slug );
	$last_updated = get_transient( "range_plu_{$slug_hash}" );
	if ( false === $last_updated ) {
		$last_updated = aiows_range_plu_get_last_updated( $slug );
		set_transient( "range_plu_{$slug_hash}", $last_updated, 86400 );
	}

	if ( $last_updated )
		$plugin_meta['last_updated'] = 'Last Updated: ' . esc_html( $last_updated );

	return $plugin_meta;
}

function aiows_range_plu_get_last_updated( $slug ) {
	$request = wp_remote_post(
		'https://api.wordpress.org/plugins/info/1.0/',
		array(
			'body' => array(
				'action' => 'plugin_information',
				'request' => serialize(
					(object) array(
						'slug' => $slug,
						'fields' => array( 'last_updated' => true )
					)
				)
			)
		)
	);
	if ( 200 != wp_remote_retrieve_response_code( $request ) )
		return false;

	$response = unserialize( wp_remote_retrieve_body( $request ) );
	// Return an empty but cachable response if the plugin isn't in the .org repo
	if ( empty( $response ) )
		return '';
	if ( isset( $response->last_updated ) )
		return sanitize_text_field( $response->last_updated );

	return false;
}
