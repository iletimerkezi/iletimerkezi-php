# İleti Merkezi PHP SDK

İleti Merkezi PHP SDK, İleti Merkezi API'si ile kolayca etkileşimde bulunmanızı sağlar. Bu SDK, SMS gönderimi, raporlama, kara liste yönetimi ve daha fazlasını yapmanıza olanak tanır.

## Kurulum

Bu SDK'yı projenize eklemek için composer kullanabilirsiniz:

```bash
composer require iletimerkezi/sdk
```

## Desteklenen PHP Versiyonları

Bu SDK, PHP 7.4 ve üzeri sürümlerle uyumludur.

## Kullanım

### Hızlı Başlangıç

```php
use IletiMerkezi\IletiMerkeziClient;

$client = new IletiMerkeziClient('API_KEY', 'API_HASH', 'BASLIK');

$smsService = $client->sms();
$resp = $smsService
    ->disableIysConsent()
    ->send('5057023100', 'Hello World - 5');

if($resp->ok()) {
    echo 'SMS Gönderildi: ' . $resp->orderId() . "\n";
} else {
    echo 'Hata: ' . $resp->code() . ':' . $resp->message() . "\n";
}
```


### Başlangıç

İleti Merkezi API'sine bağlanmak için bir `IletiMerkeziClient` örneği oluşturmanız gerekir:

```php
use IletiMerkezi\IletiMerkeziClient;
$client = new IletiMerkeziClient('API_KEY', 'API_HASH', 'BASLIK');
```

### SMS Gönderimi

SMS göndermek için `sms()` servisini kullanabilirsiniz:

```php
$smsService = $client->sms();
$response = $smsService->send(['5057023100'], 'Merhaba Dünya');
if ($response->ok()) {
    echo 'Sipariş ID: ' . $response->orderId();
} else {
    echo 'Hata: ' . $response->message();
}
```

### Raporlama

Rapor almak için `reports()` servisini kullanabilirsiniz:

```php
$reportService = $client->reports();
$response = $reportService->get(123456);
if ($response->ok()) {
    echo 'Sipariş Durumu: ' . $response->orderStatus();
} else {
    echo 'Hata: ' . $response->message();
}
```

### Kara Liste Yönetimi

Kara listeye numara eklemek veya çıkarmak için `blacklist()` servisini kullanabilirsiniz:

```php
$blacklistService = $client->blacklist();
$response = $blacklistService->create('5057023100');
if ($response->ok()) {
    echo 'Başarıyla eklendi';
} else {
    echo 'Hata: ' . $response->message();
}
```

## Katkıda Bulunma

Katkıda bulunmak isterseniz, lütfen bir pull request gönderin veya bir issue açın.

## Lisans

Bu proje MIT lisansı altında lisanslanmıştır. Daha fazla bilgi için `LICENSE` dosyasına bakın.