# LEA-DOKUMENTATION

**Portfolio-Website mit integriertem Backend-System**

---

**Joline Elizabeth Panagiotaris**

**26.09.2025**

vorgelegt bei  
Stefan Schlichting

---

## Erklärung

Hiermit wird versichert, dass die vorliegende Arbeit selbständig und ohne unerlaubte Hilfe Dritter angefertigt wurde. Alle Stellen, die inhaltlich oder wörtlich aus anderen Veröffentlichungen stammen sind kenntlich gemacht.

---

Joline Elizabeth Panagiotaris

---

## Inhaltsverzeichnis

1. [Einleitung](#1-einleitung)
2. [Hauptteil](#2-hauptteil)
   - 2.1 [Einrichten der Entwicklungsumgebung](#21-einrichten-der-entwicklungsumgebung)
   - 2.2 [Produktbeschreibung](#22-produktbeschreibung)
   - 2.3 [Model](#23-model)
   - 2.4 [View (GUI)](#24-view-gui)
   - 2.5 [Control](#25-control)
   - 2.6 [Problemlösungen](#26-problemlösungen)
3. [Fazit](#3-fazit)
4. [Anhang](#4-anhang)
   - 4.1 [Quellenverzeichnis](#41-quellenverzeichnis)
   - 4.2 [Abbildungsverzeichnis](#42-abbildungsverzeichnis)

---

## 1 Einleitung

### Aufgabenstellung

Das Projekt umfasst die Entwicklung einer vollständigen Portfolio-Website mit integriertem Backend-System. Die Anwendung soll eine professionelle Darstellung kreativer Arbeiten ermöglichen und gleichzeitig eine administrative Verwaltung der Inhalte bieten.

### Zielsetzung

Die entwickelte Lösung soll folgende Anforderungen erfüllen:

- Responsive Frontend mit modernen Webtechnologien
- Datenbankgestütztes Backend für dynamische Inhalte
- Benutzerfreundliche Administrationsoberfläche
- Containerisierte Deployment-Lösung
- Skalierbare Architektur für Erweiterungen

### Projektrahmen

Die Implementierung erfolgt als vollständige Web-Anwendung mit Frontend, Backend und Datenbank. Das System wird als Docker-Container-Lösung bereitgestellt und ermöglicht eine einfache Installation und Wartung.

---

## 2 Hauptteil

### 2.1 Einrichten der Entwicklungsumgebung

#### Systemvoraussetzungen

Die Entwicklungsumgebung erfordert folgende Komponenten:

- Docker Engine Version 20.0 oder höher
- Docker Compose Version 2.0 oder höher
- Verfügbare Ports: 3000, 3001, 3307

#### Installation und Konfiguration

Die Einrichtung erfolgt über die bereitgestellte Docker Compose-Konfiguration. Das System startet drei Services:

**Tabelle 1: Service-Übersicht**
| Service | Port | Funktion |
|---------|------|----------|
| web | 3000 | Apache Webserver mit PHP 8.1 |
| db | 3307 | MySQL 8.0 Datenbank |
| phpmyadmin | 3001 | Datenbankadministration |

Die Initialisierung erfolgt durch das Ausführen des Befehls `docker compose up -d` im Projektverzeichnis.

### 2.2 Produktbeschreibung

#### Funktionsumfang

Das Portfolio-System bietet eine umfassende Lösung für die Präsentation kreativer Arbeiten. Die Anwendung unterstützt sechs Portfolio-Kategorien:

1. **Zeichnungen**: Digitale und traditionelle Kunstwerke
2. **3D Modelle**: Dreidimensionale Renderings und Modellierungen
3. **Fotografie**: Fotografische Arbeiten mit kinematografischem Stil
4. **Logos**: Corporate Design und Markenentwicklung
5. **Charaktere**: Karikaturhafte Figurendarstellungen
6. **Grafiken**: Plakate und visuelle Kommunikation

#### Technische Spezifikationen

Das System basiert auf einer modernen Web-Architektur mit folgenden Technologien:

- **Frontend**: HTML5, JavaScript ES6, CSS3, GSAP, Three.js
- **Backend**: PHP 8.1, PDO für Datenbankzugriff
- **Datenbank**: MySQL 8.0 mit UTF-8 Unterstützung
- **Containerisierung**: Docker mit Apache 2.4

### 2.3 Model

#### Datenbankarchitektur

Die Datenstruktur folgt einem relationalen Design mit vier Haupttabellen:

**Abbildung 1: Datenbankschema**

```
categories
├── id (Primary Key)
├── name (VARCHAR)
├── cover_image (VARCHAR)
├── description (TEXT)
└── friendly_url (VARCHAR)

galleries
├── id (Primary Key)
├── category_id (Foreign Key)
├── name (VARCHAR)
├── description (TEXT)
└── cover_image (VARCHAR)

images
├── id (Primary Key)
├── gallery_id (Foreign Key)
├── src (VARCHAR)
├── th (VARCHAR)
└── ord (INT)

admin_users
├── id (Primary Key)
├── username (VARCHAR)
├── password_hash (VARCHAR)
└── email (VARCHAR)
```

#### Datenmodellierung

Die Implementierung erfolgt über PHP Data Objects (PDO) mit vorbereiteten Statements. Dies gewährleistet SQL-Injection-Schutz und optimale Performance. Die Datenbankverbindung wird über Umgebungsvariablen konfiguriert.

### 2.4 View (GUI)

#### Frontend-Architektur

Das Frontend implementiert eine Single Page Application (SPA) mit folgenden Komponenten:

**Parallax-Hintergrunde**: Mehrschichtige Scrolling-Effekte für visuelle Tiefe
**3D-Modelle**: Interactive Three.js-Objekte (cat und floppy)
**Responsive Design**: Optimierung für Desktop und mobile Geräte
**Animationen**: GSAP-basierte Übergänge und Effekte

#### Benutzeroberfläche

Die Navigation erfolgt über ein intuitives Menüsystem mit Kategorieauswahl. Jede Kategorie präsentiert eine Galerie-Übersicht mit Thumbnail-Vorschau. Die Vollbildansicht ermöglicht eine detaillierte Betrachtung der Portfolioinhalte.

**Abbildung 2: Navigationsstruktur**

- Startseite mit Parallax-Effekt
- Kategorieauswahl über Swiper-Komponente
- Galerie-Ansicht mit Lightbox-Funktion
- Administrationspanel für Content-Management

### 2.5 Control

#### API-Architektur

Das Backend stellt eine REST-API mit folgenden Endpunkten bereit:

**Tabelle 2: API-Endpunkte**
| Methode | Pfad | Funktion |
|---------|------|----------|
| GET | /api.php | Vollständige Portfolio-Daten |
| GET | /api.php?category=ID | Spezifische Kategorie |
| GET | /api.php?gallery=ID | Spezifische Galerie |
| POST | /api.php | Upload/Update (Admin) |

#### Authentifizierung

Die Administratorfunktionen sind über Bearer Token Authentication geschützt. Die Implementierung verwendet Bcrypt für Password-Hashing und validiert Anfragen über HTTP-Header.

#### Datenverarbeitung

Die Datenausgabe erfolgt im JSON-Format mit UTF-8 Encoding. CORS-Header ermöglichen Frontend-Backend-Kommunikation. Fehlerbehandlung erfolgt über HTTP-Status-Codes.

### 2.6 Problemlösungen

#### Deployment-Herausforderungen

**Problem**: Konsistente Entwicklungsumgebung  
**Lösung**: Docker Compose-Konfiguration mit definierten Service-Abhängigkeiten

**Problem**: Datenpersistenz bei Container-Updates  
**Lösung**: Volume-Mounting für Datenbank und Upload-Verzeichnisse

#### Performance-Optimierungen

**Problem**: Große Bilddateien verlangsamen Ladezeiten  
**Lösung**: Thumbnail-Generierung und WebP-Format für optimierte Übertragung

**Problem**: Mobile Responsivität bei komplexen Animationen  
**Lösung**: Conditional Loading basierend auf Viewport-Größe und Device-Capabilities

#### Sicherheitsaspekte

**Problem**: Unautorisierten Zugriff auf Admin-Funktionen  
**Lösung**: Token-basierte Authentifizierung mit Ablaufzeiten

**Problem**: SQL-Injection-Vulnerabilitäten  
**Lösung**: Prepared Statements und Input-Validierung

---

## 3 Fazit

### Projektergebnis

Das entwickelte Portfolio-System erfüllt alle definierten Anforderungen. Die Anwendung bietet eine professionelle Präsentationsplattform mit vollständiger administrativer Kontrolle. Die containerisierte Architektur gewährleistet eine einfache Deployment- und Wartungsstrategie.

### Technische Bewertung

Die gewählte Technologie-Kombination aus PHP, MySQL und Docker hat sich als optimal erwiesen. Die REST-API-Architektur ermöglicht eine klare Trennung von Frontend und Backend. Die Verwendung moderner JavaScript-Frameworks wie GSAP und Three.js schafft eine ansprechende Benutzererfahrung.

### Erweiterungsmöglichkeiten

Das System bietet verschiedene Ansatzpunkte für zukünftige Entwicklungen:

- Integration von Content Delivery Networks (CDN)
- Implementierung von Benutzerkommentaren
- Mehrsprachige Unterstützung
- Social Media Integration
- Analytics und Tracking-Funktionen

### Lessons Learned

Die Projektdurchführung verdeutlichte die Wichtigkeit einer durchdachten Architektur. Die frühe Entscheidung für containerisierte Deployment-Lösungen erwies sich als vorteilhaft für Development und Production Environments.

---

## 4 Anhang

### 4.1 Quellenverzeichnis

Docker Inc.: "Docker Compose Documentation", https://docs.docker.com/compose/, abgerufen am 25.09.2025

Mozilla Developer Network: "Web APIs", https://developer.mozilla.org/en-US/docs/Web/API, abgerufen am 25.09.2025

MySQL AB: "MySQL 8.0 Reference Manual", https://dev.mysql.com/doc/refman/8.0/en/, abgerufen am 25.09.2025

Pexels: "Free Stock Photos", https://www.pexels.com/, abgerufen am 25.09.2025

PHP Group: "PHP Manual", https://www.php.net/manual/en/, abgerufen am 25.09.2025

Pixabay: "Stunning free images & royalty free stock", https://pixabay.com/, abgerufen am 25.09.2025

Unsplash: "The internet's source for visuals", https://unsplash.com/, abgerufen am 25.09.2025

### 4.2 Abbildungsverzeichnis

Abbildung 1: Datenbankschema......................................... Seite 4  
Abbildung 2: Navigationsstruktur..................................... Seite 5

### 4.3 Tabellenverzeichnis

Tabelle 1: Service-Übersicht.......................................... Seite 4  
Tabelle 2: API-Endpunkte.............................................. Seite 5

---

_Dokumentation erstellt nach LEA-Standards_  
_Format: DIN A4, Arial 12pt, Zeilenabstand 1,5_
