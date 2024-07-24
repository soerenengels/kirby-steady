<?php
/**
 * Add this snippet to the <head>...</head>
 * of your HTML document.
 */
if (!steady()?->widgets()?->enabled()) return;
?>
<script>
	<?= steady()->widgets()->getWidgetLoaderContent(); ?>
</script>
