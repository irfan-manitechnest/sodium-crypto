# Contributing to SodiumCrypto

Thank you for considering contributing to **SodiumCrypto**!
We welcome contributions of all kinds: bug reports, fixes, features, tests, and documentation improvements.

---

## 🛠 How to Contribute

### 1️⃣ Fork & Clone the Repository
    git clone https://github.com/irfan-manitechnest/sodium-crypto.git
    cd sodium-crypto

### 2️⃣ Create a Branch
Always work on a feature branch:
    git checkout -b feature/my-new-feature

### 3️⃣ Install Dependencies
    composer install

### 4️⃣ Run Tests
Ensure all tests pass before committing:
    vendor/bin/phpunit

### 5️⃣ Submit a Pull Request (PR)
Push your branch and open a PR:
    git push origin feature/my-new-feature

Then open a PR on GitHub. Please include a clear description of your changes.

---

## ✅ Code Style Guidelines
- Follow **PSR-12** coding standards.
- Use **strict typing** (`declare(strict_types=1)`).
- Prefer **immutability and stateless design**.
- Avoid unnecessary comments—write **self-documenting code**.

---

## 🧪 Testing
- Write PHPUnit tests for all new code.
- Place tests in the `tests/` directory.
- Run tests locally using:
      vendor/bin/phpunit

---

## 🔐 Security Vulnerabilities
If you discover a security issue, **do NOT open a public issue**.
Instead, email the maintainer at **irfan.manitechnest@gmail.com** for responsible disclosure.

---

## 🤝 Code of Conduct
All contributors must follow our [Code of Conduct](CODE_OF_CONDUCT.md).

---

## 📜 License
By contributing, you agree that your contributions will be licensed under the **MIT License**.
