<<<<<<< HEAD
## CHIKADMIN ->> Indosopha Prospect
<p><b>
Merupakan perubahan update dari CHIKA ADMIN untuk kebutuhan perusahaan
</b></p>

## Instalasi
- download original CHIKAADMIN zip <a href="https://github.com/rahmathidayat9/sb-admin-2-laravel-8/archive/master.zip">disini</a> 
- atau clone : git clone https://github.com/rahmathidayat9/sb-admin-2-laravel-8.git

Atau lgsg Download 
https://github.com/minrandom/indosophaprospect.git



## Setup
- buka direktori project di terminal anda.
- ketikan command : cp .env.example .env (copy paste file .env.example)
- buat database dengan nama laravel_sb_admin_2 (bebas)
- buka file .env dengan teks editor , edit bagian DB_DATABASE= menjadi DB_DATABASE=laravel_sb_admin_2 
(sesuaikan dengan nama database yang anda buat)

Lalu ketik command dibawah ini : 

- composer install
- php artisan optimize:clear 
- php artisan key:generate (generate app key)
- php artisan migrate (migrasi database)
- php artisan db:seed --class=UserClass (mengisi data table users) atau bisa juga php artisan db:seed (semua tabel)

## Login
- Email : admin@gmail.com
- Password : password

## Fitur
- Autentikasi dengan Laravel Auth
- Autorisasi dengan Laravel Gate
- Yajra DataTable Serverside
- jquery ajax crud dengan datatable serverside example

## Preview


## Author
Thanks To core Resource to
- Rahmat Hidayatullah

Update for Work by :
@minrandom

=======
# indosophaprospect
>>>>>>> 78268c3e084d89a0b90340ce1300ae94e12621bc
