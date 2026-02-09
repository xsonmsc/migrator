# Migration Studio

MySQL tabloları arasında veri taşımak için sürükle‑bırak tabanlı bir migration aracı.

## Kurulum
- PHP 8+ önerilir
- MySQL erişimi gerekir
- `config.php` içinden DB bağlantılarını tanımla

## Kullanım
1. Tarayıcıdan `index.php` aç.
2. Kaynak DB/Tablo ve Hedef DB/Tablo seç.
3. Kolonları sürükle‑bırak ile eşle.
4. Gerekirse datatype ve custom PHP ekle.
5. `Generate Migration` ile dosyayı üret.
6. `run.php?file=dosya_adi` ile çalıştır.

## Dokümantasyon
Detaylı kullanım için: `UI_GUIDE.md`

