# Serra Popup Eklentisi

Bu WordPress eklentisi, özelleştirilebilir pop-up'lar oluşturmanızı sağlar. Eklenti ile sitenizdeki kullanıcılarınıza özel mesajlar gösterebilir, pop-up'ların görünümünü ve içeriğini düzenleyebilirsiniz.

## Özellikler

- **Özelleştirilebilir Başlık ve Açıklama:** Pop-up'ınızın başlık ve açıklama metnini kolayca düzenleyebilirsiniz.
- **Logo ve Arka Plan Resmi Desteği:** Pop-up'ınıza bir logo ve arka plan resmi ekleyebilirsiniz.
- **Çift Harekete Geçirici Mesaj (CTA) Butonu:** İki ayrı buton ekleyerek kullanıcıları farklı sayfalara yönlendirebilirsiniz (Örn: "İncele" ve "Satın Al").
- **Gelişmiş Gösterim ve Davranış Kontrolü:** 
  - **Yönlendirme Sonrası Otomatik Kapanma:** Müşteri butonlara tıkladığında pop-up'ın kapanması ve belirlenen gün boyunca gizlenmesi.
  - **Esnek "Daha Sonra Hatırlat":** Ertelemenin sayfa sayısı, saat veya gün bazlı yapılabilmesi ve panelden özelleştirilmesi.
  - **Özelleştirilebilir Kapatma Hafızası:** Kapat (X) butonuna tıklandığında kaç gün gizleneceğinin ayarlanabilmesi.
- **Modern ve Hazır Tema Şablonları:** Slate Glass (Dark Slate), Neon Purple, Emerald Luxury, Clean Light ve Özel Renk Seçimi.
- **Duyuru Rozeti (Badge):** Pop-up başlığı üstünde animasyonlu dikkat çekici etiket gösterimi (Örn: 🔥 ÖZEL FIRSAT).
- **Canlı Geri Sayım Sayacı (Countdown Timer):** Kampanya veya teklif sonlanma tarihi seçilerek canlı `Saat : Dakika : Saniye` sayacı.
- **Çeşitli Açılış Animasyonları:** Büyüyerek Açılma (Pop), Aşağıdan Yukarı Kayma (Slide Up), Yavaşça Belirme (Fade In) ve Zıplama (Bounce).
- **Mobil "Bottom Sheet" Stili:** Mobil cihazlarda ekranın altından yükselen modern mobil kart görünümü.
- **Gelişmiş Gösterim ve Davranış Kontrolü:** 
  - **Yönlendirme Sonrası Otomatik Kapanma:** Müşteri butonlara tıkladığında pop-up'ın kapanması ve belirlenen gün boyunca gizlenmesi.
  - **Esnek "Daha Sonra Hatırlat":** Ertelemenin sayfa sayısı, saat veya gün bazlı yapılabilmesi ve panelden özelleştirilmesi.
  - **Özelleştirilebilir Kapatma Hafızası:** Kapat (X) butonuna tıklandığında kaç gün gizleneceğinin ayarlanabilmesi.
- **Durum Yönetimi:** Eklentiyi aktif veya pasif hale getirebilirsiniz.

## Kurulum

1. **Eklentiyi İndir:** Bu projeyi [GitHub](https://github.com/kullaniciadi/serra-popup) üzerinden indirin veya zip dosyası olarak indirin.
2. **Eklentiyi Yükleyin:** `wp-content/plugins` klasörüne yükleyin.
3. **WordPress'te Eklentiyi Etkinleştirin:** WordPress panelinize giriş yapın ve `Eklentiler > Yüklü Eklentiler` sayfasında eklentiyi etkinleştirin.
4. **Ayarlar:** `Ayarlar > Serra Popup` sayfasına giderek pop-up içeriğinizi ve tasarımınızı düzenleyin.

## Kullanım

1. **Pop-up Oluşturma:** `Ayarlar > Serra Popup` sayfasından pop-up ayarlarınızı yapın.
2. **Logo ve Arka Plan Resmi Ekleme:** Medya Yükleyici'yi kullanarak bir logo ve arka plan resmi seçin.
3. **Tema ve Animasyon Seçimi:** Hazır renk temalarından (Slate Glass, Neon Purple, Emerald, Clean Light) veya özel renk kombinasyonundan birini seçin.
4. **Rozet ve Geri Sayım Ekleme:** Duyuru rozeti veya canlı geri sayım sayacını aktif edin.
5. **Pop-up Durumu:** Pop-up'ı aktif veya pasif olarak ayarlayın.

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
- **Tema Şablonu:** Slate Glass, Neon Purple, Emerald Luxury, Clean Light veya Özel Renkler.
- **Duyuru Rozeti (Badge):** Aktif/Pasif ve rozet metni.
- **Geri Sayım Sayacı (Countdown):** Bitiş tarihi & saati ve sayaç başlığı.
- **Açılış Animasyonu:** Pop, Slide Up, Fade In, Bounce.
- **Mobil Görünüm Stili:** Ortalanmış Pop-up veya Mobil Alt Kart (Bottom Sheet).
- **Yönlendirme Sonrası Kapatılsın mı?:** Butonlara tıklanınca pop-up'ı gizleme seçeneği.
- **"Daha Sonra Hatırlat" Ayarları:** Süre tipi (Sayfa, Saat, Gün) ve değer ayarı.
- **Durum:** Pop-up'ı aktif veya pasif olarak ayarlayın.

## Geliştirme

Bu eklenti, WordPress'in `wp_enqueue_script` ve `wp_enqueue_style` işlevlerini kullanarak özel JavaScript ve CSS dosyalarını yükler. `popup.php` ana dosyasını düzenleyerek eklentiyi daha fazla özelleştirebilirsiniz.

## Katkıda Bulunma

Bu projeye katkıda bulunmak isterseniz, lütfen `issues` bölümünden bir konu açın veya bir `pull request` gönderin. Her türlü katkınız değerlidir!

## Lisans

Bu proje [MIT Lisansı](LICENSE) altında lisanslanmıştır.

## Değişim Günlüğü (Changelog)

### v1.3.0 (19 Ağustos 2026)
- **Yeni:** 4 Adet Hazır Renk Teması (Slate Glass, Neon Purple, Emerald Luxury, Clean Light) ve Özel Renk Seçimi eklendi.
- **Yeni:** Animasyonlu Duyuru Rozeti (Badge) özelliği eklendi.
- **Yeni:** Canlı Geri Sayım Sayacı (Countdown Timer) eklendi.
- **Yeni:** 4 Farklı Açılış Animasyon seçeneği (Pop/Scale, Slide Up, Fade In, Bounce) eklendi.
- **Yeni:** Mobil Alt Kart (Bottom Sheet) stili eklendi.
- **Garantili Görünüm:** Tüm sitelerde varsayılan görünüm olarak mevcut Slate Glass korundu.

### v1.2.0 (19 Ağustos 2026)
- **Yeni:** Yönlendirme (CTA) butonlarına tıklandığında pop-up'ın otomatik kapanması ve belirlenen gün sayısı boyunca gösterilmemesi seçeneği eklendi.
- **Yeni:** "Daha sonra hatırlat" erteleme seçeneği esnek hale getirildi (Sayfa gezinmesi, Saat veya Gün bazlı erteleme).
- **Yeni:** Kapat (X) butonuna tıklandığında kaç gün gösterilmeyeceğinin panelden ayarlanabilmesi sağlandı.
- **Geliştirme:** PHP ayarları `wp_localize_script` ile güvenli şekilde JS tarafına aktarıldı.

### v1.1.0 (17 Şubat 2026)
- **Yeni:** Çift Aksiyon Butonu (CTA) özelliği eklendi. İkinci buton panelden aktif edilebilir.
- **Yeni:** "Daha sonra hatırlat" özelliği eklendi (3 sayfa sonra tekrar gösterim).
- **Yeni:** Kapat butonuna basıldığında 3 gün boyunca tekrar göstermeme özelliği eklendi.
- **Geliştirme:** UI/UX tasarımı tamamen modernize edildi.
- **Hata Giderimi:** Sayfa yenilemelerinde pop-up'ın tekrar tekrar çıkması sorunu giderildi.

## İletişim

Herhangi bir sorunuz veya öneriniz varsa, lütfen [info@serra.org.tr](mailto:info@serra.org.tr) adresinden bizimle iletişime geçin.


