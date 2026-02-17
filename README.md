# Serra Popup Eklentisi

Bu WordPress eklentisi, özelleştirilebilir pop-up'lar oluşturmanızı sağlar. Eklenti ile sitenizdeki kullanıcılarınıza özel mesajlar gösterebilir, pop-up'ların görünümünü ve içeriğini düzenleyebilirsiniz.

## Özellikler

- **Özelleştirilebilir Başlık ve Açıklama:** Pop-up'ınızın başlık ve açıklama metnini kolayca düzenleyebilirsiniz.
- **Logo ve Arka Plan Resmi Desteği:** Pop-up'ınıza bir logo ve arka plan resmi ekleyebilirsiniz.
- **Harekete Geçirici Mesaj (CTA) Butonu:** Özelleştirilebilir buton metni ve bağlantısı ile kullanıcıları istediğiniz sayfaya yönlendirebilirsiniz.
- **Gelişmiş Gösterim Kontrolü:** 
  - **3 Günlük Kapatma Hafızası:** Kapat butonuna basıldığında pop-up 3 gün boyunca tekrar gösterilmez.
  - **Daha Sonra Hatırlat:** Kullanıcı bu seçeneği seçtiğinde pop-up 3 sayfa gezinmesinden sonra tekrar çıkar.
- **Modern ve Premium Tasarım:** Responsive ve şık arayüz.
- **Durum Yönetimi:** Eklentiyi aktif veya pasif hale getirebilirsiniz.

## Kurulum

1. **Eklentiyi İndir:** Bu projeyi [GitHub](https://github.com/kullaniciadi/serra-popup) üzerinden indirin veya zip dosyası olarak indirin.
2. **Eklentiyi Yükleyin:** `wp-content/plugins` klasörüne yükleyin.
3. **WordPress'te Eklentiyi Etkinleştirin:** WordPress panelinize giriş yapın ve `Eklentiler > Yüklü Eklentiler` sayfasında eklentiyi etkinleştirin.
4. **Ayarlar:** `Ayarlar > Serra Popup` sayfasına giderek pop-up içeriğinizi ve tasarımınızı düzenleyin.

## Kullanım

1. **Pop-up Oluşturma:** `Ayarlar > Serra Popup` sayfasından pop-up ayarlarınızı yapın.
2. **Logo ve Arka Plan Resmi Ekleme:** Medya Yükleyici'yi kullanarak bir logo ve arka plan resmi seçin.
3. **Başlık ve Açıklama Metni Düzenleme:** Pop-up için başlık ve açıklama metnini girin.
4. **Pop-up Durumu:** Pop-up'ı aktif veya pasif olarak ayarlayın.

## Ekran Görüntüleri

Eklentinin kullanımını daha iyi anlamak için aşağıdaki ekran görüntülerine göz atabilirsiniz:

### Eklenti Ayarları Sayfası

![Eklenti Ayarları Sayfası](https://github.com/kullaniciadi/serra-popup/images/settings_page.png)

### Örnek Pop-up Görünümü

![Örnek Pop-up Görünümü](https://github.com/kullaniciadi/serra-popup/images/usage.png)

## Yapılandırma

Eklentinin ayarları `Ayarlar > Serra Popup` sayfasında yer almaktadır. Bu sayfada:

- **Pop-up Başlık Metni:** Başlık metnini girin.
- **Pop-up Açıklama Metni:** Açıklama metnini girin.
- **Logo URL:** Medya yükleyici ile logonuzu seçin.
- **Arka Plan Resmi URL:** Medya yükleyici ile arka plan resminizi seçin.
- **Durum:** Pop-up'ı aktif veya pasif olarak ayarlayın.

## Geliştirme

Bu eklenti, WordPress'in `wp_enqueue_script` ve `wp_enqueue_style` işlevlerini kullanarak özel JavaScript ve CSS dosyalarını yükler. `popup.php` ana dosyasını düzenleyerek eklentiyi daha fazla özelleştirebilirsiniz.

## Katkıda Bulunma

Bu projeye katkıda bulunmak isterseniz, lütfen `issues` bölümünden bir konu açın veya bir `pull request` gönderin. Her türlü katkınız değerlidir!

## Lisans

Bu proje [MIT Lisansı](LICENSE) altında lisanslanmıştır.

## Değişim Günlüğü (Changelog)

### v1.1.0 (17 Şubat 2026)
- **Yeni:** Aksiyon Butonu (CTA) özelliği eklendi. Buton metni ve linki panelden ayarlanabiliyor.
- **Yeni:** "Daha sonra hatırlat" özelliği eklendi (3 sayfa sonra tekrar gösterim).
- **Yeni:** Kapat butonuna basıldığında 3 gün boyunca tekrar göstermeme özelliği eklendi.
- **Geliştirme:** UI/UX tasarımı tamamen modernize edildi.
- **Hata Giderimi:** Sayfa yenilemelerinde pop-up'ın tekrar tekrar çıkması sorunu giderildi.

## İletişim

Herhangi bir sorunuz veya öneriniz varsa, lütfen [info@serra.org.tr](mailto:info@serra.org.tr) adresinden bizimle iletişime geçin.
