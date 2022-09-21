# CSR generator

PHP offers several methods to help generate a CSR and private key. Unfortunately, some CSR parts (i.e. subject 
alternative names) are not easily usable. This Laravel package aims to make the procedure easier within your Laravel 
application.

## Requirements

This Laravel package requires PHP 8.1 or higher, Laravel 9+ and you will need the openssl extension as that's required 
for the `openssl_*` php functions used by this package.

## Installation

This package can be used in any Laravel project.

You can install the package via composer:

`composer require vdhicts/csr-generator`

## Usage

This package is an easy-to-use wrapper around the PHP functions.

### Getting started

All steps can be performed individually to suit all your needs.

```php
// Generate the private key
$privateKey = (new PrivateKeyGenerator())->generate();

// Collect the subject fields
$subjectFields = new SubjectFields(
    'example.com',
    'security@example.com',
    'NL',
    'Zuid-Holland',
    'Den Haag',
    'Example',
    'DevOps',
    ['www.example.com']
);

// Generate the csr
$csr = (new CsrGenerator($subjectFields, $privateKey))->generate();
$csrContent = $csr->export();
```

### Generate private key

The private key can be generated with the `PrivateKeyGenerator`. It's possible to manually determine the key bits and 
type. Additional options can be provided too. The generator will return null when failed or an instance of `PrivateKey`.

```php
$privateKey = (new PrivateKeyGenerator())
    ->setPrivateKeyBits(8196)
    ->setPrivateKeyType(OPENSSL_KEYTYPE_RSA)
    ->setAdditionalOptions(['config' => 'your-config-file'])
    ->generate();
```

You can access the `OpenSSLAsymmetricKey` as a property.

### Export private key as string

To convert the private key to a string, use the `export` method on the `PrivateKey` object or cast the object to a 
string:

```php
$privateKeyContent = $privateKey
    ->setPassPhrase('test-1234!')
    ->setAdditionalOptions(['config' => 'path-to-your-config-file'])
    ->export();
```

### Generate CSR

To generate the CSR, generate the private key and create the subject fields first. The generator will return null when 
failed or an instance of `Csr`.

```php
$subjectFields = new SubjectFields(
    'example.com',
    'security@example.com',
    'NL',
    'Zuid-Holland',
    'Den Haag',
    'Example',
    'DevOps',
    ['www.example.com']
);
$csr = (new CsrGenerator($subjectFields, $privateKey))
    ->setAdditionalOptions(['config' => 'path-to-your-config-file'])
    ->generate();
```

You can access the `OpenSSLCertificateSigningRequest` as a property.

#### Subject alternative names & your own config

When providing subject alternative names, the config file from the additional options will be **overwritten**. This is 
required to provide the subject alternative names as those can't be provided directly to the `openssl_` functions 
built in PHP. If you need to provide subject alternative names and a custom config, leave the subject alternative names 
in the `SubjectFields` empty and provide your config with the SAN section:

```php
$subjectFields = new SubjectFields(
    'example.com',
    'security@example.com',
    'NL',
    'Zuid-Holland',
    'Den Haag',
    'Example',
    'DevOps' // so not providing the subject alternative names here
);

// Create your config file with the subject alternative names
..

// Provide your config file to the generator
$csr = (new CsrGenerator($subjectFields, $privateKey))
    ->setAdditionalOptions(['config' => 'path-to-your-config-file'])
    ->generate();
```

To help you create the config file, it's possible to publish the view for the config file. This view is used by default 
for generating the config with the subject alternative names.

```
php artisan vendor:publish --provider="Vdhicts\CsrGenerator\CsrGeneratorServiceProvider" --tag=csr-generator-views
```

### Export CSR as string

To convert the CSR to a string, use the `export` method on the `Csr` object or cast the object to a string:

```php
$csrContent = $csr->export();
```

### Custom configuration

Some defaults are set which are used by the generators. To change those defaults, publish the configuration file with:

```
php artisan vendor:publish --provider="Vdhicts\CsrGenerator\CsrGeneratorServiceProvider" --tag=csr-generator-config
```

## Tests

Unit tests are available in the `tests` folder. Run with:

`composer test`

When you want a code coverage report which will be generated in the `build/report` folder. Run with:

`composer test-coverage`

## Contribution

Any contribution is welcome, see the [Contributing guidelines](CONTRIBUTING.md).

## Security

If you discover any security-related issues in this or other packages of Vdhicts, please email security@vdhicts.nl 
instead of using the issue tracker.

## License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

## About Vdhicts

[Vdhicts](https://www.vdhicts.nl) is the name of my company for which I work as a freelancer. Vdhicts develops and 
implements IT solutions for businesses and educational institutions.
