# Security Policy

## Supported Versions
We actively support and patch security issues for the following versions:

| Version       | Supported          |
|---------------|--------------------|
| `main` (latest) | ✅ Fully supported |
| Older releases | ❌ No support      |

We recommend always using the latest version of `sodium-crypto` to receive security updates.

---

## Reporting a Vulnerability
If you discover a security vulnerability, please **do not open a public issue**.
Instead, follow the steps below:

1. **Email the maintainer directly** at:
   **you@example.com** (replace with your actual contact email)

2. Provide as much detail as possible:
   - A clear description of the vulnerability.
   - Steps to reproduce the issue.
   - Any relevant environment details (PHP version, OS, etc.).

3. Allow up to **72 hours** for an initial response.
   We will acknowledge your report and begin investigation immediately.

---

## Responsible Disclosure
We follow a **responsible disclosure policy**:
- Security vulnerabilities will be patched privately before public disclosure.
- Once resolved, a public advisory will be issued with mitigation details.
- Reporters will be credited (if desired) for responsible disclosure.

---

## Best Practices
While using this library:
- Always use **PHP 8.3+** with the **libsodium extension** enabled.
- Keep dependencies updated via Composer (`composer update`).
- Use **strong, randomly generated keys** (do not hard-code keys in code).
- Rotate keys periodically for long-term deployments.

---

## References
- [PHP Sodium Documentation](https://www.php.net/manual/en/book.sodium.php)
- [OWASP Cryptographic Security Guidelines](https://owasp.org/www-project-cheat-sheets/cheatsheets/Cryptographic_Storage_Cheat_Sheet.html)

---

## Contact
For urgent matters, email **irfan.manitechnest@gmail.com** directly.
