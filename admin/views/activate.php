<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>


<div class="modapi-box">
	<?php Moderation_Api::view( 'title' ); ?>
	<?php Moderation_Api::view( 'setup' );?>
</div>
<br/>
<div class="modapi-box">
	<?php Moderation_Api::view( 'enter' );?>
</div>