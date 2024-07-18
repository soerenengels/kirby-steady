<template>
	<k-grid variant="columns">
		<k-column width="1/2">
			<k-section headline="Setup">
				<k-text
					>If you want to use the OAuth feature of the kirby-steady plugin, you
					need to get the OAuth credentials from the Steady backend and add them
					to your sites config file.</k-text
				>
				<pre>
<k-code language="php">&#60;?php
	return [
		'soerenengels.steady.oauth' =&#62; [
			'key' =&#62; '...',
			'secret' =&#62; '...'
		]
	];
</k-code></pre>
			</k-section>
		</k-column>
		<k-column width="1/2">
			<k-section headline="User Flow">
				<k-text
					><ol>
						<li>
							To use the Steady OAuth Flow, the user needs to visit the
							`/oauth/steady/authorize` route. At this route a session cookie
							gets stored and the user will be redirected to steady, to
							authorize.
						</li>
						<li>
							When the user authorized the application, they will be redirected
							to the `/oauth/steady/callback` route. The verification string
							will be checked and the access token will be stored.
						</li>
					</ol>
				</k-text>
			</k-section>
		</k-column>
		<k-column width="1/2">
			<k-section headline="$steady->oauth->user()">
				<k-code language="json">{ user }</k-code>
			</k-section>
		</k-column>
		<k-column width="1/2">
			<k-section headline="$steady->oauth->subscription()">
				<k-code language="json">{ subscription }</k-code>
			</k-section>
		</k-column>
	</k-grid>
</template>
