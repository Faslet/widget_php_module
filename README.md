# Faslet Widget Composer Plugin

### Usage
To use this project in your own, pull down this plugin with 
```bash
compooser install faslet/faslet
```

### Examples

To run the built in examples, the easiest way is to run the following command in the root project folder:

```bash
php -S localhost:5002
```

And then navigate to:

http://localhost:5002/examples/faslet-widget.php

for the Widget example and

http://localhost:5002/examples/faslet-order-tracking.php

for the Order Tracking example. Note that Order tracking only sends events, which you would see in the network tab of your browser's dev-tools.


### Development

This project uses composer. First run install before starting development:

```bash
composer install
```

While developing, you may need to regenerate the autoload:
```bash
composer dump-autoload
```

### Testing

This project uses PHP  Unit for testing. To run, simply run the following command:
```bash
./vendor/bin/phpunit --testdox tests
```