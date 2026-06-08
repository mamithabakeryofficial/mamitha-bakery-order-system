$ErrorActionPreference = "Stop"

$php = "C:\xampp\php\php.exe"
$composer = "f:\PORTOFOLIO KU\Rental Mobil\rental-mobil\composer.phar"
$projectDir = "f:\PORTOFOLIO KU\Mamitha order system"
$backupDir = "f:\PORTOFOLIO KU\Mamitha_backup"

Write-Host "Memulai proses instalasi..."

# Membuat folder backup
Write-Host "Mengamankan file hasil generate..."
if (Test-Path $backupDir) { Remove-Item -Path $backupDir -Recurse -Force }
New-Item -ItemType Directory -Force -Path $backupDir | Out-Null

# Pindahkan file yang sudah dibuat ke backup
$filesToMove = @("app", "database", "resources", "routes", ".env", "composer.json")
foreach ($item in $filesToMove) {
    if (Test-Path "$projectDir\$item") {
        Move-Item -Path "$projectDir\$item" -Destination $backupDir -Force
    }
}

# Hapus sisa folder target agar create-project berhasil
if (Test-Path $projectDir) {
    Remove-Item -Path $projectDir -Recurse -Force
}

Write-Host "Mengunduh core framework Laravel (ini butuh waktu beberapa menit)..."
& $php $composer create-project laravel/laravel "$projectDir" --no-interaction

Write-Host "Mengembalikan file hasil generate ke dalam project..."
Copy-Item -Path "$backupDir\*" -Destination "$projectDir" -Recurse -Force

Write-Host "Menginstal dependensi tambahan (DOMPDF, Midtrans)..."
Set-Location -Path "$projectDir"
& $php $composer update --no-interaction
& $php $composer require barryvdh/laravel-dompdf midtrans/midtrans-php --no-interaction

Write-Host "Membersihkan backup..."
Set-Location -Path "f:\PORTOFOLIO KU"
Remove-Item -Path $backupDir -Recurse -Force

Write-Host "Mempersiapkan Database mamitha_bakery..."
# Membuat database via command line mysql
$mysql = "C:\xampp\mysql\bin\mysql.exe"
if (Test-Path $mysql) {
    & $mysql -u root -e "CREATE DATABASE IF NOT EXISTS mamitha_bakery;"
    Write-Host "Database berhasil dibuat."
} else {
    Write-Host "Peringatan: MySQL tidak ditemukan di C:\xampp\mysql\bin. Harap buat database mamitha_bakery secara manual."
}

Write-Host "Menjalankan Migrasi Database..."
Set-Location -Path "$projectDir"
& $php artisan migrate:fresh --seed --force

Write-Host "Setup Selesai! Silakan jalankan 'php artisan serve' untuk memulai server."
