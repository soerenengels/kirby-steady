<?php
/**
 * Add this snippet to the <head>...</head>
 * of your HTML document.
 * Füge dieses Snippet in den <head>...</head>
 * deines HTML-Dokuments ein.
 */
if (option('soerenengels.kirby-steady.widget') == false) return;
?>
<script defer type="text/javascript" src="<?= steady()->publication()->js_widget_url ?>"></script>
