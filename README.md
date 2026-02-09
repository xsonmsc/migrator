# Migration Studio

MySQL tabloları arasında veri taşımak için sürükle-bırak tabanlı bir migration aracı.

## Özellikler
- DB ve tablo listelerini otomatik çekme
- Kolonlar arası drag & drop mapping
- Otomatik datatype önerisi (gerekirse manuel değiştirme)
- Hedef kolon için custom PHP dönüşümü
- Satır bazlı global PHP kuralı (satır atlama dahil)
- Migration dosyası üretme ve çalıştırma

## Sayfalar
- `index.php`: Ana sayfa, mevcut migration dosyalarını listeler ve çalıştırır.
- `create.php`: Migration oluşturma ekranı.

## Kurulum
1. PHP 8+ önerilir
2. MySQL erişimi gerekir
3. `config.php` içinden DB bağlantılarını tanımla

## Hızlı Başlangıç
1. Tarayıcıdan `index.php` aç.
2. `+ Migration Oluştur` ile `create.php` sayfasına geç.
3. Kaynak DB/Tablo ve Hedef DB/Tablo seç.
4. Kolonları sürükle-bırak ile eşle.
5. Gerekirse datatype ve custom PHP ekle.
6. `Generate Migration` ile dosyayı üret.
7. Ana sayfaya dönüp migration dosyasına tıklayarak çalıştır.

## Örnek config.php
```php
return [
    "databases" => [
        "db1" => [
            "host" => "localhost",
            "dbname" => "db1",
            "user" => "USER",
            "pass" => "PASS",
            "charset" => "utf8mb4"
        ],
        "db2" => [
            "host" => "localhost",
            "dbname" => "db2",
            "user" => "USER",
            "pass" => "PASS",
            "charset" => "utf8mb4"
        ]
    ]
];
```

## Custom PHP Örnekleri
Kolon bazlı (mapping satırındaki `+ PHP`):
```php
// string temizleme
return trim($v);
```

```php
// fiyat üzerine KDV ekleme
return (float)$v * 1.20;
```

Global (sayfanın altındaki `Custom PHP (global)`):
```php
// pasif kayıtları atla
if (isset($row["status"]) && $row["status"] === "passive") {
    $skip = true;
}
```

```php
// telefon formatını normalize et
if (isset($row["phone"])) {
    $row["phone"] = preg_replace("/\\D+/", "", $row["phone"]);
}
```

## Dokümantasyon
Detaylı kullanım için: `UI_GUIDE.md`
