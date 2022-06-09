<?php

namespace Vdhicts\CsrGenerator;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use OpenSSLAsymmetricKey;
use OpenSSLCertificateSigningRequest;

class CsrGenerator
{
    private string $digestAlg;
    private SubjectFields $subjectFields;
    private OpenSSLAsymmetricKey $privateKey;
    /** @var array<string, string> */
    private array $additionalOptions = [];

    public function __construct(SubjectFields $subjectFields, OpenSSLAsymmetricKey $privateKey)
    {
        $this->digestAlg = Config::get('csr-generator.digest_alg', 'sha256');

        $this->subjectFields = $subjectFields;
        $this->privateKey = $privateKey;
    }

    /**
     * @param array<string, string> $additionalOptions
     */
    public function setAdditionalOptions(array $additionalOptions = []): self
    {
        $this->additionalOptions = $additionalOptions;

        return $this;
    }

    private function hasAlternativeSubjects(): bool
    {
        return count($this->subjectFields->getAlternativeSubjects()) !== 0;
    }

    private function generateConfigFile(): string
    {
        $subjectAlternativeNames = $this
            ->subjectFields
            ->getAlternativeSubjects();

        return view('csr-generator::csr_config', ['subjectAlternativeNames' => $subjectAlternativeNames])->render();
    }

    /**
     * Generate the certificate signing request.
     */
    public function generate(): OpenSSLCertificateSigningRequest|false
    {
        $options = [
            'digest_alg' => $this->digestAlg,
        ];

        // Generate the config file
        $configContentFile = false;
        if ($this->hasAlternativeSubjects()) {
            $configContentFile = tempnam(sys_get_temp_dir(), 'csr_');

            File::put($configContentFile, $this->generateConfigFile());

            $options['config'] = $configContentFile;
        }

        $csr = openssl_csr_new(
            $this->subjectFields->toArray(),
            $this->privateKey,
            array_merge($options, $this->additionalOptions)
        );

        if ($configContentFile) {
            File::delete($configContentFile);
        }

        return $csr;
    }
}
