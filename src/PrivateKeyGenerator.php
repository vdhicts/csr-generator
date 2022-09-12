<?php

namespace Vdhicts\CsrGenerator;

use Illuminate\Support\Facades\Config;

class PrivateKeyGenerator
{
    private string $digestAlg;
    private int $privateKeyBits;
    private int $privateKeyType;
    /** @var array<string, string> */
    private array $additionalOptions = [];

    public function __construct()
    {
        $this->digestAlg = Config::get('csr-generator.digest_alg', 'sha256');
        $this->privateKeyBits = Config::get('csr-generator.key_bits', 4096);
        $this->privateKeyType = Config::get('csr-generator.key_type', OPENSSL_KEYTYPE_RSA);
    }

    public function setPrivateKeyBits(int $privateKeyBits = 4096): self
    {
        $this->privateKeyBits = $privateKeyBits;

        return $this;
    }

    public function setPrivateKeyType(int $privateKeyType = OPENSSL_KEYTYPE_RSA): self
    {
        $this->privateKeyType = $privateKeyType;

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

    public function generate(): ?PrivateKey
    {
        $privateKey = openssl_pkey_new(array_merge(
            [
                'digest_alg' => $this->digestAlg,
                'private_key_bits' => $this->privateKeyBits,
                'private_key_type' => $this->privateKeyType,
            ],
            $this->additionalOptions
        ));
        if (!$privateKey) {
            return null;
        }

        return new PrivateKey($privateKey);
    }
}
