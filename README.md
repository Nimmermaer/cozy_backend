# Cozy Backend (EXT:cozy_backend)

[![TYPO3 13](https://img.shields.io/badge/TYPO3-13-orange.svg)](https://get.typo3.org/version/13)
[![PHP 8.3](https://img.shields.io/badge/PHP-8.3-blue.svg)](https://www.php.net/releases/8.3/en.php)

## Overview

**Cozy Backend** is a development-focused TYPO3 extension designed to demonstrate modern configuration techniques in TYPO3 v13.  
It is not meant for production use. Instead, it provides a clean, isolated playground for developers to learn and understand new TYPO3 backend customization options.

Install it only as a development dependency:

```
composer req mblunck/cozy-backend --dev
```

---

## Features

### 1. Backend Configuration using PageTS & UserTS

This extension includes examples showing how to use:

- **PageTS** to pre-fill content elements  
- **UserTS** to simplify backend usage for editors  
- Improving editor workflows with small but effective configuration tweaks

---

### 2. Backend Appearance Customization via PHP

Demonstrates backend customization without Extbase, including:

- Custom backend color schemes  
- Adding a project logo  
- Custom login screen adjustments  
- Small UX improvements for clients

Implemented using the **TYPO3 Backend API** directly in PHP.

---

### 3. New Site Configuration via SiteSets

Shows how to structure global configuration using TYPO3 **SiteSets**, introduced in v13:

- Centralized setup  
- Reusable configuration  
- Cleaner project architecture  

Perfect for large TYPO3 installations or when working with multiple sites.

---

### 4. Extending PageLayout via EventListener

Demonstrates how to use TYPO3 Core **EventListeners** to:

- Add new markers to the PageLayout  
- Adjust how elements are displayed  
- Enhance the visual structure using TYPO3-native mechanisms

No XCLASS, no Extbase — pure Event API.

---

### 5. Content Elements without Extbase

Provides simple examples of **non-Extbase content elements**, including:

- TCA-only content elements  
- Pure Fluid-based rendering  
- Examples for lightweight CE development

Ideal for performance-focused or small-scale features.

---

### 6. TypoScript using the New `PAGEVIEW` Syntax

Modern TYPO3 v13 TypoScript examples showing:

- How to use the new `PAGEVIEW`  
- Clean, readable page rendering configs  
- Full examples ready to copy/paste

---

### 7. Backend UI Enhancements Using Core Features Only

Demonstrates small backend UI enhancements using TYPO3’s own tools:

- Column overrides  
- Enhanced element previews  
- Editor-friendly adjustments  
- No third-party libraries

---

## Installation

```
composer req mblunck/cozy-backend --dev
```

Once installed, TYPO3 automatically loads all configuration examples.

---

## Purpose

This extension is **educational** and intended for developers who want to explore TYPO3’s capabilities in v13 — from backend UI styling to new configuration paradigms and lightweight content elements.

---

## License

MIT
