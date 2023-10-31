<?php
/**
 * Add this snippet to the <head>...</head>
 * of your HTML document.
 */
if (!steady()?->widgets()?->enabled()) return;
?>
<script type="text/javascript" src="<?= steady()->publication()->js_widget_url ?>"></script>
