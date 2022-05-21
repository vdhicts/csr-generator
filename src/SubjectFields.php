<?php

namespace Vdhicts\CsrGenerator;

use Illuminate\Contracts\Support\Arrayable;

class SubjectFields implements Arrayable
{
    private string $commonName;
    private string $emailAddress;
    private string $countryName;
    private string $stateOrProvinceName;
    private string $localityName;
    private string $organizationName;
    private string $organizationalUnit;
    /** @var string[] */
    private array $alternativeSubjects;

    /**
     * @param string[] $alternativeSubjects
     */
    public function __construct(
        string $commonName,
        string $emailAddress,
        string $countryName,
        string $stateOrProvinceName,
        string $localityName,
        string $organizationName,
        string $organizationalUnit = '',
        array $alternativeSubjects = []
    ) {
        $this->countryName = $countryName;
        $this->stateOrProvinceName = $stateOrProvinceName;
        $this->localityName = $localityName;
        $this->organizationName = $organizationName;
        $this->organizationalUnit = $organizationalUnit;
        $this->commonName = $commonName;
        $this->emailAddress = $emailAddress;
        $this->alternativeSubjects = $alternativeSubjects;
    }

    /**
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
        $data = [
            'countryName' => $this->countryName,
            'stateOrProvinceName' => $this->stateOrProvinceName,
            'localityName' => $this->localityName,
            'organizationName' => $this->organizationName,
            'commonName' => $this->commonName,
            'emailAddress' => $this->emailAddress,
        ];

        if (!empty($this->organizationalUnit)) {
            $data['organizationalUnitName'] = $this->organizationalUnit;
        }

        return $data;
    }
}
