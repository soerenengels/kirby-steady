# The OAuth Flow

Before you setup the OAuth flow, make sure you have [set the oauth config](/oauth/config) in your `config.php` and added the redirect uri in the Steady Backend to `https://{your-site-url.com}/oauth/steady/callback`.

## 1. Add a Login link to your site.

```php [Template]
<?= steady()->oauth()->link() ?>
```

When the user clicks the link, they will be redirected to the Steady Website to grant your application access. After that, the user gets redirected to the `redirect_uri`.

## 2. Process Callback

The callback will be processed in the plugins callback route. With the received authentication an access token is requested and saved to a newly created Kirby $user with an client.yml blueprint (or as configured in the options). The $user gets automatically logged in.

## 3. Work with the authenticated Steady user

Now you have the oportunity to get the current User `$steady->oauth()->user()` or the current users Subscription `$steady->oauth()->subscription()` data.

## 4. Logout user

The user can be manually logged out by calling `steady()->oauth()->logout()` or via the logout link.
