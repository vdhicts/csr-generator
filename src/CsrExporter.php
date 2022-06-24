<?php

namespace Vdhicts\CsrGenerator;

use OpenSSLCertificateSigningRequest;
use Stringable;

class CsrExporter implements Stringable
{
    private OpenSSLCertificateSigningRequest $certificateSigningRequest;

    public function __construct(OpenSSLCertificateSigningRequest $certificateSigningRequest)
    {
        $this->certificateSigningRequest = $certificateSigningRequest;
    }

    public function export(): ?string
    {
        $certificateSigningRequestContent = false;

        $result = openssl_csr_export($this->certificateSigningRequest, $certificateSigningRequestContent);

        return $result
            ? $certificateSigningRequestContent
            : null;
    }

    public function __toString(): string
    {
        return $this->export() ?? '';
    }
}
