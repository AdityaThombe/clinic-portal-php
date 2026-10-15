# clinic-portal-php

Patient portal helpers for a small clinic: staff accounts, patient lookup (SQLite via PDO) and a lab-results integration.

**Demo target for ProofPatch: intentionally contains security bugs. Never deploy this code.**

| Area | Vulnerability | OWASP |
|---|---|---|
| `src/Patients.php` | SQL injection in patient search (CWE-89) | A03 Injection |
| `src/Staff.php` | Staff passwords hashed with MD5 (CWE-916) | A02 Cryptographic Failures |
| `src/LabClient.php` | Hardcoded lab-provider API secret (CWE-798) | A07 Identification and Authentication Failures |

Run the tests: `composer install && vendor/bin/phpunit`.
