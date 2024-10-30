<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//phpcs:disable VariableAnalysis
// There are "undefined" variables here because they're defined in the code that includes this file as a template.

?>
<div id="modapi-plugin-container">
	<div class="modapi-masthead">
		<div class="modapi-masthead__inside-container">
			<?php Moderation_Api::view( 'logo' ); ?>
		</div>
	</div>
	<div class="modapi-lower">
		<div class="modapi-boxes">
			<?php

				Moderation_Api::view( 'activate' );

			?>
		</div>
	</div>
</div>