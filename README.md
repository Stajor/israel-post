# PHP API for Israel Post service.

## Installation

    $ composer require stajor/israel-post
    
## Usage

```php
<?php
# Any cache driver supports psr/cache
$cache = new Symfony\Component\Cache\Adapter\ArrayAdapter();

$israelPost = new IsraelPost\IsraelPost($cache);

# Tracking delivery
$response = $israelPost->deliveryTracking()->track('EE123456789IL');

# Get rate for abroad letter delivery
$country = 'RU';
$amount = 3;
$weight = 200;
$rate = $israelPost->deliveryRate()->abroad()->letter()->standardAirDeliveryRate($country, $amount, $weight);
```

## Contributing

Bug reports and pull requests are welcome on GitHub at https://github.com/Stajor/israel-post. This project is intended to be a safe, welcoming space for collaboration, and contributors are expected to adhere to the [Contributor Covenant](http://contributor-covenant.org) code of conduct.

## License

The gem is available as open source under the terms of the [MIT License](https://opensource.org/licenses/MIT).
