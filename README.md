# Discord Webhook Builder

A simple discord webhook builder.

This package only provides a fluent way of building the payload, you still have to send it yourself currently.

> [!NOTE]
> Components that are not supported by the simple discord webhook endpoint may be missing or limited currently as
> they where not the primary goal of this package

you can install it via composer:

```bash
composer require justinkluever/discord-webhook-builder
```

## Usage

Using this package should be simple:

```php
use JustinKluever\DiscordWebhookBuilder\Components\TextDisplay;
use JustinKluever\DiscordWebhookBuilder\Enums\Support\MessageFlag;
use JustinKluever\DiscordWebhookBuilder\Support\Webhook\Embed;
use JustinKluever\DiscordWebhookBuilder\Webhook;

// Components V2
$webhook = Webhook::make()
    ->flag(MessageFlag::IS_COMPONENTS_V2) // Required for Components V2 by Discord
    ->component(
        TextDisplay::make('This is a Text Display!'),
    );

// Classic Webhooks
$webhook = Webhook::make()
    ->content('Embed Test webhook')
    ->embed(
        Embed::make()->description('This is a Embed description'),
    )
```

After creating your webhook payload you can use your preferred way of POSTing:

```php
// Laravel HTTP Client
use Illuminate\Support\Facades\Http;
Http::post('https://discord.com/api/webhooks/...', $webhook); // The Webhook builder implements Stringable for ease of use

// Guzzle
$client->post('https://discord.com/api/webhooks/...', [
    'json' => $webhook
]);

// cURL
$ch = curl_init('https://discord.com/api/webhooks/...');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $webhook);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);
```

For more details on how to use specific Components check the Source or look at Discord's Documentation:

- [Discord Webhook Documentation](https://docs.discord.com/developers/resources/webhook)
- [Discord Components Documentation](https://docs.discord.com/developers/components/reference#component-object)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
