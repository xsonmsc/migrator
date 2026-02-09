# Migration Studio Kullanım Rehberi

Bu proje, bir DB’den tablo seçip başka bir DB’deki tabloya kolon eşlemesiyle veri taşımak için yapılmıştır. Arayüzde sürükle‑bırak ile kolon eşlemesi, datatype seçimi ve isteğe bağlı custom PHP dönüşümleri desteklenir.

## 1) DB ve Tablo Seçimi
- Sayfa açıldığında `config.php` içindeki tüm DB’ler otomatik listelenir.
- `Kaynak DB` ve `Hedef DB` seç.
- `Kaynak Tablo` ve `Hedef Tablo` seç.
- Seçimden sonra kolonlar otomatik gelir.

## 2) Drag & Drop Mapping
- Sol taraftaki kaynak kolonları sağdaki hedef kolonların üstüne sürükle‑bırak yap.
- Bıraktığında:
  - Hedef satır otomatik olarak kaynak kolon adını alır.
  - Kaynak kolonun MySQL tipi algılanır ve `Datatype` otomatik seçilir.
- Yanlış drop yaptıysan `Kaldır` ile eşlemeyi temizleyebilirsin.

## 3) Datatype Seçimi
- Sistem MySQL tipini algılar ve uygun seçeneği otomatik işaretler.
- Gerekirse kendin değiştirebilirsin.
- Desteklenen örnekler: `int`, `bigint`, `varchar`, `text`, `date`, `datetime`, `json`, `boolean`, `enum`, `set` vb.
- Özel dönüşüm tipi: `int_to_date`

## 4) Custom PHP (Satır Bazında)
- Her hedef kolon satırında `+ PHP` butonuna tıklayınca input açılır.
- Tekrar tıklayınca input kapanır ve içerik temizlenir.
- Bu PHP kodu **o kolon için** `return` ile değer üretmelidir.

### Örnek (kolon bazlı)
```php
return strtoupper($v);
```
Burada `$v`, mevcut mapping’den gelen değerdir.

## 5) Global Custom PHP (Satır Bazında Toplu)
- Sayfanın altındaki `Custom PHP (global)` alanı tüm satırlar için çalışır.
- Burada `$row` değişkeni kullanılabilir.
- Eğer satırı atlamak istersen:
```php
$skip = true;
```

### Örnek (global)
```php
// status pasifse satırı atla
if (isset($row["status"]) && $row["status"] === "passive") {
    $skip = true;
}
```

## 6) Yeni Hedef Kolonlar
- `+ Yeni Kolon` ile hedef tablonun yeni kolonlarını tanımlayabilirsin.
- `Hedef listesine uygula` dersen bu kolonlar hedef listesine eklenir.
- Mevcut hedef kolonlar silinmez (merge yapılır).

## 7) Migration Oluşturma
- `Migration Name` gir.
- `Generate Migration` butonuna bas.
- `migrations/` klasöründe PHP dosyası üretilir.

## 8) Migration Çalıştırma
- `run.php?file=dosya_adi` şeklinde çalıştırılır.
- Progress bar ile ilerleme gösterilir.

## Sık Sorunlar
- DB seçili değilse tablo/kolon listesi gelmez.
- Kolon eşlemesi yoksa hedefe veri yazılmaz.

