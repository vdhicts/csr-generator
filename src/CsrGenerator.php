<?php

namespace Vdhicts\CsrGenerator;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class CsrGenerator
{
    private string $digestAlg;
    private SubjectFields $subjectFields;
    private PrivateKey $privateKey;
    /** @var array<string, string> */
    private array $additionalOptions = [];

    public function __construct(SubjectFields $subjectFields, PrivateKey $privateKey)
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
    public function generate(): ?Csr
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
            $this->privateKey->openSSLAsymmetricKey,
            [...$options, ...$this->additionalOptions]
        );

        if ($configContentFile) {
            File::delete($configContentFile);
        }

        return $csr
            ? new Csr($csr)
            : null;
    }
}
