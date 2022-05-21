<?php

namespace Vdhicts\CsrGenerator\Tests\Unit;

use OpenSSLCertificateSigningRequest;
use Vdhicts\CsrGenerator\CsrExporter;
use Vdhicts\CsrGenerator\CsrGenerator;
use Vdhicts\CsrGenerator\PrivateKeyGenerator;
use Vdhicts\CsrGenerator\SubjectFields;
use Vdhicts\CsrGenerator\Tests\TestCase;

class CsrTest extends TestCase
{
    public function testGeneratePrivateKey(): void
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

        $this->assertInstanceOf(OpenSSLCertificateSigningRequest::class, $csr);
    }

    public function testExportPrivateKey(): void
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

        $csrExporter = new CsrExporter($csr);

        $csrContent = $csrExporter->export();
        $this->assertIsString($csrContent);
        $this->assertStringStartsWith('-----BEGIN CERTIFICATE REQUEST-----', $csrContent);

        $csrContent = (string)$csrExporter;
        $this->assertIsString($csrContent);
        $this->assertStringStartsWith('-----BEGIN CERTIFICATE REQUEST-----', $csrContent);
    }
}
