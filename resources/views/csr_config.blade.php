[ req ]
distinguished_name = req_distinguished_name
req_extensions = v3_req

[ req_distinguished_name ]

[ v3_req ]
basicConstraints = CA:FALSE
keyUsage = digitalSignature, nonRepudiation, keyEncipherment
subjectAltName = @san

[ san ]
@foreach($subjectAlternativeNames as $subjectAlternativeName)
DNS.{{ $loop->index }} = {{ $subjectAlternativeName }}
@endforeach
