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

    public function export(): string|false
    {
        $certificateSigningRequestContent = false;

        $result = openssl_csr_export($this->certificateSigningRequest, $certificateSigningRequestContent);
        if (!$result) {
            return false;
        }

        return $certificateSigningRequestContent;
    }

    public function __toString(): string
    {
        return $this->export();
    }
}
