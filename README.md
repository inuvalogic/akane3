## Akane Framework v3

*a PHP based framework for all purpose website types.*

Sebuah Framework berbasis PHP untuk semua jenis website

Version: 3.0
Last Update: 2017-04-23
Author: [WebHade Creative](http://www.webhade.id)
___

### Kebutuhan Minimum

1. PHP 5.3.9 keatas
2. MySQL / MariaDB
3. Composer

### Instalasi

1. clone repo dari Github ( [https://github.com/inuvalogic/akane3](https://github.com/inuvalogic/akane3) )
2. buat database baru dengan nama `akane`
3. jalankan command berikut

```sh
$ cd akane3
$ composer install
$ vendor/bin/phinx migrate
$ vendor/bin/phinx seed:run
```

### Running on Local

Untuk keperluan testing di local pc/laptop, Akane memakai `PHP built-in Server` dengan port tidak terpakai sesuai yang kamu inginkan. Misal pada port `3000`, jadi nanti akses Akane dari URL [http://localhost:3000/](http://localhost:3000/)
Ketikan perintah berikut untuk menjalankan Akane:

```sh
$ cd akane3
$ php -S localhost:3000
```

#### Running on Virtual Host (Apache atau Nginx)

Selain menggunakan `PHP built-in Server`, Akane juga dapat disetting sebagai virtual host Apache atau Nginx. Apa kelebihannya? tentu saja Multi Threaded, tidak seperti PHP built-in server yang Single Threaded. Berikut contoh settingan minimal menjalankan `Akane` pada virtual host jika memakai XAMPP:

*C:\xampp\apache\conf\extra\httpd-vhosts.conf*
```apacheconf
Listen 3000
<VirtualHost *:3000>
    DocumentRoot "C:/xampp/htdocs/akane3/"
    ServerName localhost
    ErrorLog "logs/akane-error.log"
    CustomLog "logs/akane-access.log" common
</VirtualHost>
```

### Running on Live / Production Server

`WARNING! DO WITH YOUR OWN RISK!`

Untuk saat ini, `Akane Framework` tidak disarankan untuk dipakai pada Live Server, karena masih pada tahap development dan tingkat keamanannya masih jauh dibawah standar operasional.
___

## Contribute

Feel free to contribute to this project

kunjungi website kami untuk informasi lebih lanjut

[akane.webhade.com](http://akane.webhade.com)

### Change Log

v.3.0.1 (Jun 10, 2017)
- autoload class
- assets helper
- move curl helper
- router

v.3.0 Master Release (Apr 23, 2017)
- configuration
- base class & runner
- base controller
- base model
- database handler (pdo)
- layout handler
- http handler untuk redirect & uri segmentation
- session handler
- curl helper
- common helper
- phinx for migration tools
- contoh controller, model, and view