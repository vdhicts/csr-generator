<?php

namespace Vdhicts\CsrGenerator;

use OpenSSLAsymmetricKey;
use Stringable;

class PrivateKey implements Stringable
{
    private string $passPhrase = '';

    /** @var array<string, string> */
    private array $additionalOptions = [];

    public function __construct(public OpenSSLAsymmetricKey $openSSLAsymmetricKey)
    {
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
            $this->openSSLAsymmetricKey,
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
