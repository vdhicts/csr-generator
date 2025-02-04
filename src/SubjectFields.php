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
     * Returns the alternative subjects and make sure the common name isn't part of the alternative subjects.
     *
     * @return string[]
     */
    public function getAlternativeSubjects(): array
    {
        return array_filter($this->alternativeSubjects, function ($alternativeSubject) {
            return $alternativeSubject !== $this->commonName;
        });
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
