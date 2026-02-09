# Migration Studio

MySQL tabloları arasında veri taşımak için sürükle‑bırak tabanlı bir migration aracı.

## Özellikler
- DB ve tablo listelerini otomatik çekme
- Kolonlar arası drag & drop mapping
- Otomatik datatype önerisi (gerekirse manuel değiştirme)
- Hedef kolon için custom PHP dönüşümü
- Satır bazlı global PHP kuralı (satır atlama dahil)
- Migration dosyası üretme ve çalıştırma

## Kurulum
1. PHP 8+ önerilir
2. MySQL erişimi gerekir
3. `config.php` içinden DB bağlantılarını tanımla

## Hızlı Başlangıç
1. Tarayıcıdan `index.php` aç.
2. Kaynak DB/Tablo ve Hedef DB/Tablo seç.
3. Kolonları sürükle‑bırak ile eşle.
4. Gerekirse datatype ve custom PHP ekle.
5. `Generate Migration` ile dosyayı üret.
6. `run.php?file=dosya_adi` ile çalıştır.

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

## Custom PHP
- Kolon bazlı: `+ PHP` ile açılır, `return` ile değer üretir.
- Global: tüm satırlar için çalışır; `$skip = true;` ile satır atlanır.

## Çalıştırma
- `run.php?file=dosya_adi`
- Progress bar ile ilerleme gösterilir.

## Dokümantasyon
Detaylı kullanım için: `UI_GUIDE.md`
