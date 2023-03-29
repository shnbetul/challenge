#Challenge

E-Ticaret altyapısı sunan bir platform için mağaza, paket ve ödeme kayıtları API üzerinden kayıt yapılacaktır. Bu kayıtlar üzerinden aylık veya yıllık periyotlara göre paket tanımlamaları olacaktır. Bu paketler mağazanın sisteme kayıt olduğunu tarih ile ilişkilendirilerek paket periyoduna göre ödemeleri Worker ile çekilecektir. Mağazada herhangi bir değişiklik olması durumunda belirlenen Endpoint’e Callback ile dönüş sağlanacaktır.

Tablolar: Companies, Packages, Company_packages, Company_payments
Tablolar oluşturulduktan sonra Dummy (laravel seed/faker) veriler ile doldurmanız beklenmektedir.

Company Register
Bir kullanıcı sisteme kayıt olmak istediğinde Request datası olarak “site_url, name, lastname, company_name, email, password” gönderecektir. Response olarak  status, token ve company_id dönüşü yapmanız beklenmektedir.

Company Package
	Company belirlenen paketler doğrultusunda aylık veya yıllık yenilenecek şekilde paket tanımlaması yapmanız beklenmektedir. Company paket tanımlama işleminde request olarak company_id, package_id gönderilecektir. Response olarak status,start_date, end_date, package bilgileri dönmenizi bekliyoruz.

Check Company Package
	Gerekli görüldüğünde token üzerinden company ve package bilgileri talep edileceği bir endpoint oluşturmanız beklenmektedir.

Cron’dan ya da supervisord gibi çeşitli server side tetikleyiciler vasıtası paket günü biten müşterilerin hesaplarından para çekimi yapılmak istenmektedir. Random bir hash oluşturarak son basamağı tek ise hesaptan çekildi, çift ise çekilemedi, şeklinde yapabilirsiniz.
	
Çekilemez ise 1 gün ara ile tekrar denenecek şekilde bir kuyruk yapısı kullanmanız gerekiyor. Üçüncü seferde yapılamaz ise company pasif durumuna almanız gerekiyor.
Company_payments’ a denemeleri kayıt etmeniz beklenmektedir.

Worker işlemi için Queue kullanmanız beklenmektedir.

# docker compose up 
# php artisan migrate --seed

## Company kayıt olduktan sonra login olursa company status 1 olur
## Package status ve period update edilmeli 
## postman collection resources içinde

