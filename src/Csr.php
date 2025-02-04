<?php

namespace Vdhicts\CsrGenerator;

use OpenSSLCertificateSigningRequest;
use Stringable;

class Csr implements Stringable
{
    public function __construct(public readonly OpenSSLCertificateSigningRequest $openSSLCertificateSigningRequest) {}

    public function export(): ?string
    {
        $certificateSigningRequestContent = false;

        $result = openssl_csr_export($this->openSSLCertificateSigningRequest, $certificateSigningRequestContent);

        return $result
            ? $certificateSigningRequestContent
            : null;
    }

    public function __toString(): string
    {
        return $this->export() ?? '';
    }
}
