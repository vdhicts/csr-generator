<?php

namespace Vdhicts\CsrGenerator;

use OpenSSLAsymmetricKey;
use Stringable;

class PrivateKeyExporter implements Stringable
{
    private OpenSSLAsymmetricKey $privateKey;
    private string $passPhrase = '';
    /** @var array<string, string> */
    private array $additionalOptions = [];

    public function __construct(OpenSSLAsymmetricKey $privateKey)
    {
        $this->privateKey = $privateKey;
    }

    public function setPassPhrase(string $passPhrase = ''): self
    {
        $this->passPhrase = $passPhrase;

        return $this;
    }

    /**
     * @param array<string, string> $additionalOptions
     */
    public function setAdditionalOptions(array $additionalOptions = []): self
    {
        $this->additionalOptions = $additionalOptions;

        return $this;
    }

    public function export(): ?string
    {
        $privateKeyContent = false;

        $result = openssl_pkey_export(
            $this->privateKey,
            $privateKeyContent,
            $this->passPhrase,
            $this->additionalOptions
        );

        return $result
            ? $privateKeyContent
            : null;
    }

    public function __toString(): string
    {
        return $this->export() ?? '';
    }
}
