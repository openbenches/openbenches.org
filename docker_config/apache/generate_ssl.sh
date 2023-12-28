#!/bin/sh
openssl genrsa -out domain.key 3072
openssl req -new -out openBenchesWebsite.csr -sha256 -key domain.key -subj "/O=OpenBenches/CN=localhost"
openssl x509 -req -in openBenchesWebsite.csr -days 365 -signkey domain.key -out apache-selfsigned.crt -outform PEM
 