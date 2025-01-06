# Example: Comment with Steady

_**User Story:** A user visits your website. Depending on their login status they are able to comment an article._

The plugin comes with a bouncer snippet, that manages the logic for you, to check if a user is a member of your publication, a logged in steady user or just a visitor.

:::code-group

```php [templates/article.php]
<?php snippet('steady/bouncer', slots: true): ?>
<?php slot('member') ?>
  <!-- Comment form -->
  <?php snippet('comments', compact('comments')) ?>
<?php endslot ?>
<?php slot('user') ?>
  <p>To comment this article you must first <a href="/subscribe">subscribe to <?= steady()->publication()->title() ?></a>.</p>
  <?php snippet('comments', compact('comments')) ?>
<?php endslot ?>
<?php slot('visitor') ?>
  <p>To comment this article you must first <a href="/subscribe">subscribe to <?= steady()->publication()->title() ?></a>. You can see the comments, when you are <a href="">logged into Steady</a>.</p>
<?php endslot ?>
<?php endsnippet ?>
```

```php [snippets/comment.php]
<section class="k-steady-comment">
  <?= $comment->text() ?>
  <footer>
    wrote <span><?= $comment->nameOrEmail() ?></span> at <date><?= $comment->datetime() ?></date>
  </footer>
</section>
```

```php [snippets/comments.php]
<section class="k-steady-comments">
  <h2>Comments</h2>
  <?php foreach ($comments as $comment): ?>
    <?php snippet('comment', compact('comment')) ?>
  <?php endforeach ?>
</section>
```

:::

Before you setup the OAuth flow, make sure you have [set the oauth config](/oauth/setup) in your `config.php` and added the redirect uri in the Steady Backend to `https://{your-site-url.com}/oauth/steady/callback`.

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
