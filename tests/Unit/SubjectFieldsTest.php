<?php

namespace Vdhicts\CsrGenerator\Tests\Unit;

use Illuminate\Support\Arr;
use Vdhicts\CsrGenerator\SubjectFields;
use Vdhicts\CsrGenerator\Tests\TestCase;

class SubjectFieldsTest extends TestCase
{
    public function test_subject_fields(): void
    {
        $commonName = 'example.com';
        $emailAddress = 'security@example.com';
        $countryName = 'NL';
        $stateOrProvinceName = 'Zuid-Holland';
        $localityName = 'Den Haag';
        $organizationName = 'Example';
        $organizationalUnit = 'DevOps';
        $alternativeNames = ['www.example.com', 'hello.example.com'];

        $subjectFields = new SubjectFields(
            $commonName,
            $emailAddress,
            $countryName,
            $stateOrProvinceName,
            $localityName,
            $organizationName,
            $organizationalUnit,
            $alternativeNames
        );

        $this->assertInstanceOf(SubjectFields::class, $subjectFields);
        $this->assertIsArray($subjectFields->toArray());
        $this->assertArrayHasKey('commonName', $subjectFields->toArray());
        $this->assertSame($commonName, Arr::get($subjectFields->toArray(), 'commonName'));
        $this->assertSame($emailAddress, Arr::get($subjectFields->toArray(), 'emailAddress'));
        $this->assertSame($countryName, Arr::get($subjectFields->toArray(), 'countryName'));
        $this->assertSame($stateOrProvinceName, Arr::get($subjectFields->toArray(), 'stateOrProvinceName'));
        $this->assertSame($localityName, Arr::get($subjectFields->toArray(), 'localityName'));
        $this->assertSame($organizationName, Arr::get($subjectFields->toArray(), 'organizationName'));
        $this->assertSame($organizationalUnit, Arr::get($subjectFields->toArray(), 'organizationalUnitName'));

        $this->assertContains('www.example.com', $subjectFields->alternativeSubjects);
        $this->assertContains('hello.example.com', $subjectFields->alternativeSubjects);
        $this->assertNotContains('example.com', $subjectFields->alternativeSubjects);

        $this->assertContains('example.com', $subjectFields->getSubjects());
        $this->assertContains('www.example.com', $subjectFields->getSubjects());
        $this->assertContains('hello.example.com', $subjectFields->getSubjects());
    }
}
