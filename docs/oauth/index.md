# OAuth

The `$steady->oauth()` method enables you to create an OAuth flow with Steady. First of all you have to set the plugins oauth options.

## The Flow

0. Set plugins options in `config/config.php` for `client-id` and `client-secret`.
1. Set `redirect-uri`in the Steady Backend to `https://insertyourdomainname.com/oauth/steady/callback`. Use your domain name instead.
2. Create Link with your domain name to `https://insertyourdomainname.com/oauth/steady/authorize` and insert it in your page.
3. When the user clicks the link, they will be redirected to the Steady Website to grant your application access. After that, the user gets redirected to the `redirect_uri`.
3. The callback will be processed in the plugins callback route. With the received authentication an access token is requested and saved.
4. Now you have the oportunity to get the current User `$steady->oauth()->user()` or the current users Subscription `$steady->oauth()->subscription()` data.

## Privacy

If you use the OAuth feautures you have to tell your users about storing cookies at their computers.
