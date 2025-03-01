<?php

namespace Vdhicts\CsrGenerator;

use Illuminate\Contracts\Support\Arrayable;

class SubjectFields implements Arrayable
{
    /**
     * @param string[] $alternativeSubjects
     */
    public function __construct(
        public string $commonName,
        public string $emailAddress,
        public string $countryName,
        public string $stateOrProvinceName,
        public string $localityName,
        public string $organizationName,
        public string $organizationalUnit = '',
        public array $alternativeSubjects = []
    ) {}

    /**
     * Returns the list of all checked subjects, which means the common name and alternative subjects combined.
     *
     * @return string[]
     */
    public function getSubjects(): array
    {
        return array_unique(array_merge([$this->commonName], $this->alternativeSubjects));
    }

    public function toArray(): array
    {
        return array_filter([
            'countryName' => $this->countryName,
            'stateOrProvinceName' => $this->stateOrProvinceName,
            'localityName' => $this->localityName,
            'organizationName' => $this->organizationName,
            'commonName' => $this->commonName,
            'emailAddress' => $this->emailAddress,
            'organizationalUnitName' => $this->organizationalUnit,
        ]);
    }
}
