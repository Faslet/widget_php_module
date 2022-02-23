# Faslet Widget Composer Plugin

### Usage
To use this project in your own, pull down this plugin with 
```bash
compooser install faslet/faslet
```


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