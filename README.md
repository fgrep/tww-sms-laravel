# TWW Notifications Channel for Laravel 5.3+

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fgrep/tww-sms-laravel.svg?style=flat-square)](https://packagist.org/packages/fgrep/tww-sms-laravel)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://poser.pugx.org/fgrep/tww-sms-laravel/downloads.png)](https://packagist.org/packages/fgrep/tww-sms-laravel)

This package makes it easy to send TWW SMS messages using TWW [Webservice](https://webservices2.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL) with Laravel 5.3+.

## Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
    - [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via composer:

``` bash
composer require fgrep/tww-sms-laravel
```

You must install the service provider (only required on Laravel 5.4 or lower):

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\Zenvia\ZenviaServiceProvider::class,
],
```

## Configuration

Configure your credentials: 

```php
// config/services.php
...
'tww' => [
    'from'    => env('TWW_FROM', 'Laravel Notification Channels'),
    'pretend' => env('TWW_PRETEND', false),
    'conta'   => env('TWW_CONTA', 'YOUR ACCOUNT'),
    'senha'   => env('TWW_SENHA', 'YOUR PASSWORD')
],
...
```

## Usage

You can now use the channel in your `via()` method inside the Notification class.

``` php
use NotificationChannels\Tww\TwwChannel;
use NotificationChannels\Zenvia\TwwMessage;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification
{
    public function via($notifiable)
    {
        return [TwwChannel::class];
    }

    public function toTww($notifiable)
    {
        return TwwMessage::create()
            ->from('Laravel') // optional
            ->to($notifiable->phone) // your user phone
            ->content('Your invoice has been paid')
            ->id('your-sms-id');
    }
}
```

### Routing a message

You can either send the notification by providing with the chat id of the recipient to the to($phone) method like shown in the above example or add a routeNotificationForTww() method in your notifiable model:

```php
...
/**
 * Route notifications for the Telegram channel.
 *
 * @return int
 */
public function routeNotificationForTww()
{
    return $this->phone;
}
...
```

### Available Message methods

- `to($phone)`: (integer) Recipient's phone.
- `content('message')`: (string) SMS message.
- `from('Sender')`: (string) Sender's name.
- `id('sms-id')`: (string) SMS ID.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email cesar.fazan@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Luís Dalmolin](https://github.com/luisdalmolin)
- [Cesar Fazan](https://github.com/fgrep)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
