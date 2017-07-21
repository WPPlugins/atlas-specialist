<style>
.atlas-specialist-admin .dashicons-editor-help:before {
    vertical-align: middle;
}
</style>

<div class="wrap atlas-specialist-admin">
    <h1><?= esc_html( get_admin_page_title() ); ?><a target="_blank" href="https://wordpress.org/plugins/atlas-specialist/"><span class="dashicons-before dashicons-editor-help"></span></a></h1>
    <form action="options.php" method="post">
        <?php
		// output security fields for the registered setting "atlas_specialist".
		settings_fields( 'atlas_specialist' );
		// (sections are registered for "atlas_chat", each field is registered to a specific section).
		do_settings_sections( 'atlas_specialist' );
		// output save settings button.
		submit_button( esc_html( __( 'Save settings', 'atlas-specialist' ) ) );
	?>
	</form>
</div>