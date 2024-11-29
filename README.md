# Development SIAKAD UNSRI

## Getting Started
- Install composer, node js dan database server pada perangkat yang digunakan
- Clone repository ini
- Jalankan perintah 'composer install' pada terminal
- Jalankan perintah 'npm install && npm run dev' pada terminal
- Copy file .env.example menjadi .env
- Isikan konfigurasi pada .env
- Jalankan perintah 'php artisan key:generate' pada terminal
- Jalankan perintah 'php artisan migrate --seed' pada terminal
- Jalankan perintah 'php artisan serve' pada terminal
- Buka browser dan akses localhost:8000
- Pastikan alamat dan username serta password feeder pada .env sudah terisi dengan benar
- Jalankan perintah 'php artisan queue:work' pada terminal untuk menjalankan queue saat syncronisasi data

## Update 
- Jalankan perintah 'composer install' pada terminal saat terdapat perubahan pada file composer.json
- Lalu jalankan pernitah 'npm install && npm run dev' kembali pada terminal

## Yang harus dilakukan
- Membuat fitur untuk recalculasi akm dengan menghitung sks yang diambil mahasiswa dan sks-sks yang ada di transkrip dengan melakukan pengecekan matakuliah

