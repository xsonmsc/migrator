# Migration Studio Kullanım Rehberi

Bu proje, bir DB’den tablo seçip başka bir DB’deki tabloya kolon eşlemesiyle veri taşımak için yapılmıştır. Arayüzde sürükle-bırak ile kolon eşlemesi, datatype seçimi ve custom PHP dönüşümleri desteklenir.

## 1) Sayfalar
- `index.php`: Ana sayfa. Mevcut migration dosyalarını listeler ve tıklayınca çalıştırır.
- `create.php`: Migration oluşturma ekranı.

## 2) DB ve Tablo Seçimi
- Sayfa açıldığında `config.php` içindeki tüm DB’ler otomatik listelenir.
- `Kaynak DB` ve `Hedef DB` seç.
- `Kaynak Tablo` ve `Hedef Tablo` seç.
- Seçimden sonra kolonlar otomatik gelir.

## 3) Drag & Drop Mapping
- Sol taraftaki kaynak kolonları sağdaki hedef kolonların üstüne sürükle-bırak yap.
- Bıraktığında:
  - Hedef satır otomatik olarak kaynak kolon adını alır.
  - Kaynak kolonun MySQL tipi algılanır ve `Datatype` otomatik seçilir.
- Yanlış drop yaptıysan `Kaldır` ile eşlemeyi temizleyebilirsin.

## 4) Datatype Seçimi
- Sistem MySQL tipini algılar ve uygun seçeneği otomatik işaretler.
- Gerekirse kendin değiştirebilirsin.
- Özel dönüşüm tipi: `int_to_date`

## 5) Custom PHP (Kolon Bazlı)
- Her hedef kolon satırında `+ PHP` butonuna tıklayınca input açılır.
- Tekrar tıklayınca input kapanır ve içerik temizlenir.
- Bu PHP kodu **o kolon için** `return` ile değer üretmelidir.
- Bu alanda `$v`, eşlenen kaynaktan gelen değerdir.

### Örnekler (kolon bazlı)
```php
// string temizleme
return trim($v);
```

```php
// boşsa default değer ver
return $v === null || $v === "" ? "unknown" : $v;
```

```php
// sayısal değer üzerinde işlem
return (float)$v * 1.20; // KDV ekle
```

```php
// JSON stringi decode edip belirli alanı al
$arr = json_decode($v, true);
return $arr["id"] ?? null;
```

## 6) Global Custom PHP (Satır Bazında)
- Sayfanın altındaki `Custom PHP (global)` alanı tüm satırlar için çalışır.
- Burada `$row` kullanılabilir. Bu alan **mapping’den önce** çalışır.
- İstersen `$row` içindeki değerleri değiştirerek mapping sonucunu etkileyebilirsin.
- Satırı atlamak için `$skip = true;` kullan.

### Örnekler (global)
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

```php
// ad ve soyadı birleştirip mevcut kolonu güncelle
if (isset($row["first_name"], $row["last_name"])) {
    $row["full_name"] = trim($row["first_name"] . " " . $row["last_name"]);
}
```

```php
// tarih alanını UNIX timestamp'ten okunur hale getir
if (isset($row["created_at"])) {
    $row["created_at"] = date("Y-m-d H:i:s", (int)$row["created_at"]);
}
```

## 7) Yeni Hedef Kolonlar
- `+ Yeni Kolon` ile hedef tablonun yeni kolonlarını tanımlayabilirsin.
- `Hedef listesine uygula` dersen bu kolonlar hedef listesine eklenir.
- Mevcut hedef kolonlar silinmez (merge yapılır).

## 8) Migration Oluşturma
- `Migration Name` gir.
- `Generate Migration` butonuna bas.
- `migrations/` klasöründe PHP dosyası üretilir.

## 9) Migration Çalıştırma
- Ana sayfada migration adına tıkla.
- `run.php` üzerinden migration çalışır.
- Progress bar ile ilerleme gösterilir.

## Sık Sorunlar
- DB seçili değilse tablo/kolon listesi gelmez.
- Kolon eşlemesi yoksa hedefe veri yazılmaz.
