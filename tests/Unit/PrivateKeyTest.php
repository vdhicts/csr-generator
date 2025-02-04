<?php

namespace Vdhicts\CsrGenerator\Tests\Unit;

use OpenSSLAsymmetricKey;
use Vdhicts\CsrGenerator\PrivateKey;
use Vdhicts\CsrGenerator\PrivateKeyGenerator;
use Vdhicts\CsrGenerator\Tests\TestCase;

class PrivateKeyTest extends TestCase
{
    public function test_generate_private_key(): void
    {
        $generator = new PrivateKeyGenerator();

        $privateKey = $generator
            ->setPrivateKeyBits(2048)
            ->setPrivateKeyType(OPENSSL_KEYTYPE_RSA)
            ->setAdditionalOptions($this->additionalOptions)
            ->generate();

        $this->assertInstanceOf(PrivateKey::class, $privateKey);
        $this->assertInstanceOf(OpenSSLAsymmetricKey::class, $privateKey->openSSLAsymmetricKey);
    }

    public function test_export_private_key_with_passphrase(): void
    {
        $privateKey = (new PrivateKeyGenerator())
            ->setAdditionalOptions($this->additionalOptions)
            ->generate();

        $privateKey
            ->setAdditionalOptions($this->additionalOptions)
            ->setPassPhrase('test1234');

        $privateKeyContent = $privateKey->export();
        $this->assertIsString($privateKeyContent);
        $this->assertStringStartsWith('-----BEGIN ENCRYPTED PRIVATE KEY-----', $privateKeyContent);

        $privateKeyContent = (string) $privateKey;
        $this->assertIsString($privateKeyContent);
        $this->assertStringStartsWith('-----BEGIN ENCRYPTED PRIVATE KEY-----', $privateKeyContent);
    }

    public function test_export_private_key_without_passphrase(): void
    {
        $privateKey = (new PrivateKeyGenerator())
            ->setAdditionalOptions($this->additionalOptions)
            ->generate();

        $privateKey->setAdditionalOptions($this->additionalOptions);

        $privateKeyContent = $privateKey->export();
        $this->assertIsString($privateKeyContent);
        $this->assertStringStartsWith('-----BEGIN PRIVATE KEY-----', $privateKeyContent);

        $privateKeyContent = (string) $privateKey;
        $this->assertIsString($privateKeyContent);
        $this->assertStringStartsWith('-----BEGIN PRIVATE KEY-----', $privateKeyContent);
    }
}
