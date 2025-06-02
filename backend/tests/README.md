# Tests für Check24 MVC Anwendung

Diese Testsuite bietet umfassende Tests für die gesamte Anwendung, einschließlich Controller, Entities, Repositories und Forms.

## Test-Struktur

### 1. KategorieController Tests

#### Functional Tests (`tests/Controller/KategorieControllerTest.php`)
Diese Tests testen den kompletten Request/Response-Zyklus:

- **testIndex()**: Überprüft die Anzeige aller Kategorien
- **testIndexEmpty()**: Überprüft die Index-Seite ohne Kategorien
- **testNew()**: Testet das Anzeigen des "Neue Kategorie"-Formulars
- **testCreateKategorie()**: Testet das Erstellen einer neuen Kategorie
- **testCreateKategorieWithEmptyName()**: Testet Validierung bei leerem Namen
- **testShow()**: Testet die Anzeige einer einzelnen Kategorie
- **testShowNotFound()**: Testet 404-Fehler bei nicht existierender Kategorie
- **testEdit()**: Testet das Anzeigen des Edit-Formulars
- **testUpdateKategorie()**: Testet das Aktualisieren einer Kategorie
- **testEditNotFound()**: Testet 404-Fehler beim Bearbeiten
- **testDelete()**: Testet das Löschen einer Kategorie
- **testDeleteWithInvalidCsrfToken()**: Testet CSRF-Schutz
- **testDeleteNotFound()**: Testet 404-Fehler beim Löschen
- **testCompleteWorkflow()**: Testet den kompletten CRUD-Workflow

#### Unit Tests (`tests/Unit/Controller/KategorieControllerUnitTest.php`)
Diese Tests testen die Controller-Logik isoliert mit Mocks:

- **testIndexReturnsCorrectResponse()**: Testet Index-Methode mit gemockten Dependencies
- **testNewGetRequest()**: Testet GET-Request für neues Formular
- **testNewPostRequestValid()**: Testet POST-Request mit validen Daten
- **testShow()**: Testet Show-Methode isoliert
- **testEditGetRequest()**: Testet GET-Request für Edit-Formular
- **testEditPostRequestValid()**: Testet POST-Request zum Aktualisieren
- **testDeleteWithValidCsrfToken()**: Testet Delete mit validem CSRF-Token
- **testDeleteWithInvalidCsrfToken()**: Testet Delete mit invalidem CSRF-Token

### 2. APIController Tests

#### Functional Tests (`tests/Controller/api/APIControllerTest.php`)
Diese Tests testen die API-Endpoints mit echten HTTP-Requests:

- **testOptionsHotels()**: Testet CORS OPTIONS Request für Preflight
- **testGetHotelsEmpty()**: Testet GET Request mit leerer Datenbank
- **testGetHotelsWithData()**: Testet GET Request mit Hoteldaten
- **testGetHotelByIdSuccess()**: Testet erfolgreiches Abrufen eines Hotels by ID
- **testGetHotelByIdNotFound()**: Testet 404-Response für nicht existierende Hotels
- **testGetHotelByIdInvalidId()**: Testet ungültige ID-Formate
- **testJsonResponseFormat()**: Testet korrektes JSON-Format
- **testCorsHeadersConsistency()**: Testet CORS-Headers in allen Endpoints
- **testMultipleHotelsWithDifferentCategories()**: Testet mehrere Hotels mit verschiedenen Kategorien
- **testApiResponsePerformance()**: Testet API-Performance mit mehreren Hotels
- **testApiContentTypeHeaders()**: Testet Content-Type Headers
- **testErrorResponseFormat()**: Testet Fehler-Response Format

#### Unit Tests (`tests/Unit/Controller/api/APIControllerUnitTest.php`)
Diese Tests testen die API-Controller-Logik isoliert mit Mocks:

- **testOptionsHotels()**: Testet OPTIONS-Response mit Mocks
- **testGetHotelsEmpty()**: Testet leere Hotel-Liste mit Mock Repository
- **testGetHotelsWithData()**: Testet Hotel-Liste mit Mock-Daten
- **testGetHotelByIdSuccess()**: Testet erfolgreiches Hotel-Abrufen mit Mocks
- **testGetHotelByIdNotFound()**: Testet 404-Handling mit Mocks
- **testJsonResponseStructure()**: Testet JSON-Response-Struktur
- **testDataTypesInJsonResponse()**: Testet Datentypen im JSON-Response
- **testCorsHeadersInAllResponses()**: Testet CORS-Headers in allen Responses
- **testDateFormatting()**: Testet Datumsformatierung
- **testMultipleHotelsWithDifferentCategories()**: Testet verschiedene Kategorien
- **testEmptyResponseIsValidJson()**: Testet valides JSON bei leerer Response

### 3. Repository Tests

#### KategorieRepository Tests (`tests/Repository/KategorieRepositoryTest.php`)
Diese Tests überprüfen Kategorie-Datenbankoperationen:

- **testFindAll()**: Testet das Finden aller Kategorien
- **testFindAllEmpty()**: Testet leere Datenbank
- **testFindById()**: Testet Suche nach ID
- **testFindByIdNotFound()**: Testet nicht existierende ID
- **testFindOneBy()**: Testet Suche nach Kriterien
- **testFindOneByNotFound()**: Testet Suche nach nicht existierenden Kriterien
- **testCount()**: Testet Zählen von Einträgen
- **testPersistAndFlush()**: Testet Speichern neuer Entities
- **testRemove()**: Testet Löschen von Entities
- **testUpdate()**: Testet Aktualisieren von Entities

#### HotelRepository Tests (`tests/Repository/HotelRepositoryTest.php`)
Diese Tests überprüfen Hotel-Datenbankoperationen:

- **testFindAll()**: Testet das Finden aller Hotels
- **testFindAllEmpty()**: Testet leere Datenbank
- **testFindById()**: Testet Suche nach ID
- **testFindByIdNotFound()**: Testet nicht existierende ID
- **testFindByKategorie()**: Testet Suche nach Kategorie
- **testFindByPrice()**: Testet Suche nach Preis
- **testFindByLocation()**: Testet Suche nach Standort
- **testCount()**: Testet Zählen von Einträgen
- **testCountByKategorie()**: Testet Zählen nach Kategorie
- **testPersistAndFlush()**: Testet Speichern neuer Hotels
- **testRemove()**: Testet Löschen von Hotels
- **testUpdate()**: Testet Aktualisieren von Hotels
- **testFindByMultipleCriteria()**: Testet Suche mit mehreren Kriterien
- **testFindWithOrderBy()**: Testet Sortierung
- **testFindWithLimit()**: Testet Limitierung
- **testFindWithOffset()**: Testet Offset
- **testRepositoryClassName()**: Testet Repository-Klasse
- **testEntityManagerIntegration()**: Testet EntityManager-Integration

### 4. Entity Tests

#### Kategorie Entity Tests (`tests/Entity/KategorieTest.php`)
Diese Tests überprüfen die Kategorie-Entity-Logik:

- **testConstructor()**: Testet Entity-Konstruktor
- **testGetSetId()**: Testet ID-Getter/Setter
- **testGetSetName()**: Testet Name-Getter/Setter
- **testGetHotels()**: Testet Hotel-Collection
- **testAddHotel()**: Testet Hinzufügen von Hotels
- **testAddSameHotelTwice()**: Testet Duplikat-Behandlung
- **testRemoveHotel()**: Testet Entfernen von Hotels
- **testToString()**: Testet String-Repräsentation
- **testJsonSerialize()**: Testet JSON-Serialisierung
- **testHotelCollectionManagement()**: Testet komplette Collection-Verwaltung

#### Hotel Entity Tests (`tests/Entity/HotelTest.php`)
Diese Tests überprüfen die Hotel-Entity-Logik:

- **testConstructor()**: Testet Entity-Konstruktor
- **testGetSetId()**: Testet ID-Getter/Setter
- **testGetSetTitle()**: Testet Title-Getter/Setter
- **testGetSetLocation()**: Testet Location-Getter/Setter
- **testGetSetImage()**: Testet Image-Getter/Setter
- **testGetSetPrice()**: Testet Price-Getter/Setter
- **testGetSetDays()**: Testet Days-Getter/Setter
- **testGetSetPerson()**: Testet Person-Getter/Setter
- **testGetSetInfo()**: Testet Info-Getter/Setter
- **testGetSetDescription()**: Testet Description-Getter/Setter
- **testGetSetCreatedAt()**: Testet CreatedAt-Getter/Setter
- **testGetSetKategorie()**: Testet Kategorie-Beziehung
- **testSetKategorieWithNull()**: Testet Kategorie auf null setzen
- **testGetSetRating()**: Testet Rating-Getter/Setter
- **testGetSetStars()**: Testet Stars-Getter/Setter
- **testToString()**: Testet String-Repräsentation
- **testJsonSerialize()**: Testet JSON-Serialisierung
- **testJsonSerializeWithNullValues()**: Testet JSON-Serialisierung mit null-Werten
- **testJsonSerializeWithKategorieOnly()**: Testet JSON-Serialisierung nur mit Kategorie
- **testFluentInterface()**: Testet Fluent Interface (Method Chaining)
- **testValidDataTypes()**: Testet korrekte Datentypen
- **testEdgeCases()**: Testet Randfälle

### 5. Form Tests (`tests/Form/KategorieTypeTest.php`)

Diese Tests überprüfen das Formular:

- **testSubmitValidData()**: Testet Formular mit gültigen Daten
- **testSubmitEmptyData()**: Testet Formular mit leeren Daten
- **testFormHasCorrectFields()**: Testet Formular-Struktur
- **testFormView()**: Testet Formular-View
- **testFormWithSpecialCharacters()**: Testet Sonderzeichen
- **testFormSubmissionWithExistingModel()**: Testet Formular mit vorhandenen Daten

## Tests ausführen

### Alle Tests ausführen
```bash
cd backend
./vendor/bin/phpunit
```

### Tests nach Komponenten ausführen

#### Controller Tests
```bash
# KategorieController Functional Tests
./vendor/bin/phpunit tests/Controller/KategorieControllerTest.php

# KategorieController Unit Tests  
./vendor/bin/phpunit tests/Unit/Controller/KategorieControllerUnitTest.php

# APIController Functional Tests
./vendor/bin/phpunit tests/Controller/api/APIControllerTest.php

# APIController Unit Tests
./vendor/bin/phpunit tests/Unit/Controller/api/APIControllerUnitTest.php
```

#### Repository Tests
```bash
# KategorieRepository Tests
./vendor/bin/phpunit tests/Repository/KategorieRepositoryTest.php

# HotelRepository Tests
./vendor/bin/phpunit tests/Repository/HotelRepositoryTest.php
```

#### Entity Tests
```bash
# Kategorie Entity Tests
./vendor/bin/phpunit tests/Entity/KategorieTest.php

# Hotel Entity Tests
./vendor/bin/phpunit tests/Entity/HotelTest.php
```

#### Form Tests
```bash
# Form Tests
./vendor/bin/phpunit tests/Form/KategorieTypeTest.php
```

### Tests nach Typ ausführen
```bash
# Nur Functional Tests (WebTestCase)
./vendor/bin/phpunit --group functional

# Nur Unit Tests (TestCase)
./vendor/bin/phpunit --group unit

# Nur API Tests
./vendor/bin/phpunit tests/Controller/api/ tests/Unit/Controller/api/
```

### Einzelne Testmethoden ausführen
```bash
./vendor/bin/phpunit --filter testCompleteWorkflow tests/Controller/KategorieControllerTest.php
./vendor/bin/phpunit --filter testGetHotelsWithData tests/Controller/api/APIControllerTest.php
```

### Tests mit Coverage ausführen
```bash
./vendor/bin/phpunit --coverage-html coverage/
```

## Test-Konfiguration

Die Tests verwenden:
- **Test-Umgebung**: Konfiguriert in `phpunit.xml.dist`
- **Test-Datenbank**: Separate Datenbank für Tests
- **Fixtures**: Automatische Erstellung und Bereinigung von Testdaten
- **Mocking**: PHPUnit Mocks für Unit Tests
- **WebTestCase**: Für Functional Tests (echte HTTP-Requests)
- **KernelTestCase**: Für Repository Tests (Datenbank-Integration)
- **TestCase**: Für Entity und Unit Tests (isoliert)

## API Test Features

Die API-Tests sind speziell darauf ausgelegt:

### CORS Testing
- Überprüfung aller CORS-Headers in allen Endpoints
- OPTIONS Preflight Request Testing
- Konsistenz der CORS-Konfiguration

### JSON Response Testing
- Validierung der JSON-Struktur
- Überprüfung der Datentypen
- Konsistenz der Response-Formate
- Error Response Validierung

### Performance Testing
- Response-Zeit Messungen
- Handling großer Datenmengen
- Memory Usage Monitoring

### HTTP Status Code Testing
- 200 OK für erfolgreiche Requests
- 204 No Content für OPTIONS Requests
- 404 Not Found für nicht existierende Ressourcen
- Korrekte Content-Type Headers

## Best Practices

1. **Isolation**: Jeder Test ist unabhängig und bereinigt nach sich auf
2. **Arrange-Act-Assert**: Klare Teststruktur
3. **Descriptive Names**: Aussagekräftige Testnamen
4. **Edge Cases**: Tests für Randfälle und Fehlerszenarien
5. **Performance**: Tests laufen schnell und zuverlässig
6. **Mocking**: Proper Mocking für Unit Tests
7. **Database Cleanup**: Automatische Bereinigung zwischen Tests
8. **API Standards**: RESTful API Testing Standards
9. **CORS Compliance**: Vollständige CORS-Validierung
10. **JSON Validation**: Strenge JSON-Format Überprüfung

## Testdaten

Die Tests erstellen ihre eigenen Testdaten und bereinigen diese automatisch:
- `setUp()`: Bereitet Testumgebung vor
- `tearDown()`: Bereinigt nach jedem Test
- `cleanDatabase()`: Entfernt alle Test-Entities
- `createKategorie()`: Erstellt Test-Kategorien
- `createHotel()`: Erstellt Test-Hotels

## Assertion-Beispiele

### Controller Tests
```php
// Response-Tests
$this->assertResponseIsSuccessful();
$this->assertResponseRedirects('/kategorie');
$this->assertResponseStatusCodeSame(404);

// DOM-Tests
$this->assertSelectorTextContains('h1', 'Kategorie index');
$this->assertSelectorExists('form[name="kategorie"]');
```

### API Tests
```php
// JSON Response Tests
$this->assertJson($response->getContent());
$this->assertIsArray($data);
$this->assertArrayHasKey('id', $hotelData);

// HTTP Status Tests
$this->assertEquals(200, $response->getStatusCode());
$this->assertEquals(404, $response->getStatusCode());

// CORS Header Tests
$this->assertEquals('http://localhost:3000', 
    $response->headers->get('Access-Control-Allow-Origin'));

// Data Type Tests
$this->assertIsInt($hotelData['id']);
$this->assertIsString($hotelData['title']);
$this->assertIsFloat($hotelData['price']);
```

### Entity Tests
```php
// Entity-Tests
$this->assertNotNull($hotel->getId());
$this->assertEquals('Test Name', $hotel->getTitle());
$this->assertInstanceOf(Kategorie::class, $hotel->getKategorie());

// Collection-Tests
$this->assertCount(3, $hotels);
$this->assertContainsOnlyInstancesOf(Hotel::class, $hotels);

// Fluent Interface Tests
$this->assertSame($hotel, $hotel->setTitle('Test'));
```

### Repository Tests
```php
// Database Tests
$this->assertNotNull($foundHotel);
$this->assertEquals($id, $foundHotel->getId());
$this->assertNull($deletedHotel);

// Count Tests
$this->assertEquals(2, $count);
$this->assertCount(3, $hotels);
```

## Debugging

Bei fehlschlagenden Tests:
1. Überprüfen Sie die Fehlermeldung
2. Nutzen Sie `dump()` oder `dd()` für Debugging
3. Prüfen Sie die Test-Datenbank
4. Überprüfen Sie die API-Responses mit `var_dump($response->getContent())`
5. Überprüfen Sie CORS-Headers
6. Validieren Sie JSON-Format mit `json_decode()` + `json_last_error()`

## Erweiterungen

Um die Tests zu erweitern:
1. Neue Testmethoden zu bestehenden Klassen hinzufügen
2. Neue Testklassen für zusätzliche Komponenten erstellen
3. Performance-Tests für große Datenmengen
4. Integration-Tests zwischen verschiedenen Entities
5. API-Endpoint Tests für neue Routes
6. Security Tests für Authentication/Authorization
7. Load Testing für API Performance
8. Contract Testing für API Consistency 