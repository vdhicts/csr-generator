<?php

namespace Vdhicts\CsrGenerator\Tests\Unit;

use OpenSSLCertificateSigningRequest;
use Vdhicts\CsrGenerator\Csr;
use Vdhicts\CsrGenerator\CsrGenerator;
use Vdhicts\CsrGenerator\PrivateKeyGenerator;
use Vdhicts\CsrGenerator\SubjectFields;
use Vdhicts\CsrGenerator\Tests\TestCase;

class CsrTest extends TestCase
{
    public function test_generate_private_key(): void
    {
        $privateKey = (new PrivateKeyGenerator())
            ->setAdditionalOptions($this->additionalOptions)
            ->generate();

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

        $generator = new CsrGenerator($subjectFields, $privateKey);

        $csr = $generator
            ->setAdditionalOptions($this->additionalOptions)
            ->generate();

        $this->assertInstanceOf(Csr::class, $csr);
        $this->assertInstanceOf(OpenSSLCertificateSigningRequest::class, $csr->openSSLCertificateSigningRequest);
    }

    public function test_export_private_key(): void
    {
        $privateKey = (new PrivateKeyGenerator())
            ->setAdditionalOptions($this->additionalOptions)
            ->generate();

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

        $generator = new CsrGenerator($subjectFields, $privateKey);

        $csr = $generator
            ->setAdditionalOptions($this->additionalOptions)
            ->generate();

        $csrContent = $csr->export();
        $this->assertIsString($csrContent);
        $this->assertStringStartsWith('-----BEGIN CERTIFICATE REQUEST-----', $csrContent);

        $csrContent = (string) $csr;
        $this->assertIsString($csrContent);
        $this->assertStringStartsWith('-----BEGIN CERTIFICATE REQUEST-----', $csrContent);
    }
}
