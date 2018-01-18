<?php

namespace PMPro_Customizer\Register_Helper;

class Register_Helper_Customize_Control extends \WP_Customize_Control {

	public $type = 'register_helper';

	protected function render_content() {}

	protected function content_template() {
		?>
		<span class="customize-control-title">{{ data.label }}</span>
		<div class="customize-control-notifications-container"></div>
		<button type="button" class="button-secondary toggle-trashed">
			<span class="trash"><?php esc_html_e( 'Traaaash', 'default' ); ?></span>
			<span class="untrash"><?php esc_html_e( 'Untrash', 'default' ); ?></span>
		</button>
		<?php
	}
}

