# Strenges PHP 8.3 Template

**Modernes Template für PHP-Projekte mit den neuesten Tools, bereits vorkonfiguriert.**

## Features

✅ **GitHub Actions CI Pipeline** (Parallelized Tests & Checks) 🚀
✅ PHP 8.3 (mit Docker)
✅ PHPUnit 12+ für Tests
✅ PHPStan (Level max) für statische Analyse
✅ Rector für automatische Code-Modernisierung
✅ PHP-CS-Fixer mit Risky Mode für sauberen Code
✅ Xdebug für Debugging in der IDE
✅ Docker & Nginx Setup
✅ Makefile für einfache Befehle
✅ Alle Konfigurationen sauber im `.qa`-Ordner

## Struktur

```
.qa/              # Alle Config-Dateien
├── phpunit/
│   └── phpunit.xml.dist
├── phpstan/
│   └── phpstan.neon
├── rector/
│   └── rector.php
├── php-cs-fixer/
│   └── .php-cs-fixer.dist.php

src/              # Quellcode
Tests/            # Tests (Unit, Spec, Behat, Infection)
nginx/            # Nginx Config
Dockerfile        # PHP-FPM mit Xdebug
docker-compose.yml
Makefile
composer.json
```

## Wichtige Kommandos

```bash
make setup         # Projekt installieren (Docker build + composer install)
make all-tests     # Alle Tests (PHPUnit, Specc, Behat, Infection) ausführen
make all-style-checks # Alle Code-Checks ausführen (CS, PHPStan, Deptrac, Rector)
make cs-fix        # Code-Stil automatisch korrigieren
make phpstan       # Statische Analyse
make rector        # Code automatisch modernisieren
make up            # Projekt mit Docker starten
make down          # Docker Container stoppen
make console       # In den PHP-Container springen
```

## Ziel

Ein sofort nutzbares Template für moderne PHP-Projekte, das alle wichtigen Tools vorkonfiguriert bereitstellt – maximal streng, maximal zukunftssicher.
