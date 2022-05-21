<?php

namespace Vdhicts\CsrGenerator\Tests\Unit;

use OpenSSLAsymmetricKey;
use Vdhicts\CsrGenerator\PrivateKeyExporter;
use Vdhicts\CsrGenerator\PrivateKeyGenerator;
use Vdhicts\CsrGenerator\Tests\TestCase;

class PrivateKeyTest extends TestCase
{
    public function testGeneratePrivateKey(): void
    {
        $generator = new PrivateKeyGenerator();

        $privateKey = $generator
            ->setPrivateKeyBits(2048)
            ->setPrivateKeyType(OPENSSL_KEYTYPE_RSA)
            ->setAdditionalOptions($this->additionalOptions)
            ->generate();

        $this->assertInstanceOf(OpenSSLAsymmetricKey::class, $privateKey);
    }

    public function testExportPrivateKey(): void
    {
        $privateKey = (new PrivateKeyGenerator())
            ->setAdditionalOptions($this->additionalOptions)
            ->generate();

        $privateKeyExporter = (new PrivateKeyExporter($privateKey))
            ->setAdditionalOptions($this->additionalOptions)
            ->setPassPhrase('test1234');

        $privateKeyContent = $privateKeyExporter->export();
        $this->assertIsString($privateKeyContent);
        $this->assertStringStartsWith('-----BEGIN ENCRYPTED PRIVATE KEY-----', $privateKeyContent);

        $privateKeyContent = (string)$privateKeyExporter;
        $this->assertIsString($privateKeyContent);
        $this->assertStringStartsWith('-----BEGIN ENCRYPTED PRIVATE KEY-----', $privateKeyContent);
    }
}
