# Cozy Backend (EXT:cozy_backend)

[![TYPO3 14](https://img.shields.io/badge/TYPO3-14-orange.svg)](https://get.typo3.org/version/14)
[![PHP 8.5](https://img.shields.io/badge/PHP-8.5-blue.svg)](https://www.php.net/releases/8.5/en.php)

## Overview

**Cozy Backend** is a development-focused TYPO3 extension designed to demonstrate modern configuration techniques and advanced features in **TYPO3 v13**. It serves as a comprehensive playground for developers to explore the latest TYPO3 backend customization options, PHP 8 attributes, and modern architectural patterns.

> [!IMPORTANT]
> This extension is intended for educational and development purposes. It is not meant for production use.

Install it only as a development dependency:

```bash
composer req mblunck/cozy-backend --dev
```

---

## Key Features

### 1. Modern Configuration via SiteSets
Introduced in TYPO3 v14, **SiteSets** allow for a cleaner, more modular configuration:
- Centralized setup and reusable configuration.
- Simplified management of PageTS, UserTS, and TypoScript.
- Better separation of concerns in global project architecture.

### 2. TypoScript `PAGEVIEW` Syntax
Demonstrates the modern way of page rendering:
- Usage of the new `PAGEVIEW` content object.
- Clean and readable TypoScript configuration.
- Modular setup ready for modern TYPO3 projects.

### 3. Advanced Backend Customization
Shows how to tailor the TYPO3 Backend without relying on outdated patterns:
- **Backend UI:** Custom color schemes, logos, and login screen adjustments.
- **EventListeners:** Extending the PageLayout and other core components using the PSR-14 Event API (no XCLASS required).
- **PageTS/UserTS:** Pre-filling content elements and streamlining editor workflows.

### 4. PHP 8 Attributes & Interceptors
Innovative use of PHP 8 features within TYPO3:
- **Custom Attributes:** Using PHP Attributes (e.g., `#[TrackExport]`) to annotate controller actions.
- **Tracking Interceptor:** A PSR-14 EventListener that uses Reflection to detect attributes and trigger asynchronous tasks.

### 5. PDF Generation (headless Chrome)
Integrated PDF export functionality:
- Uses `chrome-php/chrome` to render high-quality PDFs from Fluid templates.
- Demonstrates service-based architecture for complex rendering tasks.

### 6. Web Push Notifications
Modern communication channel integration:
- Implements Web Push notifications using `minishlink/web-push`.
- Custom PSR-15 Middleware for handling subscription endpoints.
- Integration with Service Workers for frontend notifications.

### 7. Asynchronous Processing (Message Queue)
Example of background task handling:
- Uses the TYPO3/Symfony Messenger integration for asynchronous logging and tracking.
- Demonstrates how to dispatch and handle messages in a clean, decoupled way.

### 8. Lightweight Content Elements
Modern content element development without Extbase:
- TCA-only content elements.
- Pure Fluid-based rendering for maximum performance.
- Use of DataProcessors for flexible data preparation.


## Technical Highlights

- **PHP 8.5+** features (Attributes, Arrow Functions, etc.).
- **TYPO3 v14** Core APIs (SiteSets, Event API, Messenger).
- **Headless Chrome** integration for PDF rendering.
- **Web Push** protocol implementation.
- **Vite** integration for modern frontend asset handling.

---

## Installation

```bash
composer req mblunck/cozy-backend --dev
```

Once installed, include the **Cozy Backend** SiteSet in your site configuration to activate the examples.

---

## License

This project is licensed under the [GPL-2.0-or-later](LICENSE).
