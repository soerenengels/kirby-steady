<?php
/**
 * Add this snippet to the <head>...</head>
 * of your HTML document.
 * Füge dieses Snippet in den <head>...</head>
 * deines HTML-Dokuments ein.
 */
if (!steady()->widgets()->enabled()) return;
?>
<script type="text/javascript" src="<?= steady()->publication()->js_widget_url ?>"></script>
