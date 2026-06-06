$ErrorActionPreference = "Stop"

$php = "C:\xampp\php\php.exe"
$composer = "f:\PORTOFOLIO KU\Rental Mobil\rental-mobil\composer.phar"
$projectDir = "f:\PORTOFOLIO KU\Mamitha order system"
$backupDir = "$projectDir\Mamitha_backup"
$coreDir = "$projectDir\core_files"

Write-Host "Mempersiapkan Instalasi..."

# Membuat folder backup
if (Test-Path $backupDir) { Remove-Item -Path $backupDir -Recurse -Force }
New-Item -ItemType Directory -Force -Path $backupDir | Out-Null

# Pindahkan file hasil generate saya ke backup
$filesToBackup = @("app", "database", "resources", "routes", ".env", "composer.json")
foreach ($item in $filesToBackup) {
    if (Test-Path "$projectDir\$item") {
        Move-Item -Path "$projectDir\$item" -Destination $backupDir -Force
    }
}

Write-Host "Mengunduh core framework Laravel (ini butuh waktu)..."
& $php $composer create-project laravel/laravel "$coreDir" --no-interaction

Write-Host "Menyusun struktur folder..."
# Pindahkan semua file core Laravel ke root project
$coreItems = Get-ChildItem -Path $coreDir
foreach ($item in $coreItems) {
    Move-Item -Path $item.FullName -Destination $projectDir -Force
}

# Kembalikan file hasil generate saya (akan menimpa file default Laravel)
Write-Host "Menggabungkan kode yang sudah dibuat..."
Copy-Item -Path "$backupDir\*" -Destination $projectDir -Recurse -Force

Write-Host "Membersihkan file sementara..."
Remove-Item -Path $coreDir -Recurse -Force
Remove-Item -Path $backupDir -Recurse -Force

Write-Host "Menginstal dependensi tambahan (DOMPDF, Midtrans)..."
& $php $composer update --no-interaction
& $php $composer require barryvdh/laravel-dompdf midtrans/midtrans-php --no-interaction

Write-Host "Mempersiapkan Database mamitha_bakery..."
$mysql = "C:\xampp\mysql\bin\mysql.exe"
if (Test-Path $mysql) {
    & $mysql -u root -e "CREATE DATABASE IF NOT EXISTS mamitha_bakery;"
    Write-Host "Database berhasil dibuat."
} else {
    Write-Host "Peringatan: MySQL tidak ditemukan di C:\xampp\mysql\bin. Harap buat database mamitha_bakery secara manual."
}

Write-Host "Menjalankan Migrasi Database..."
& $php artisan migrate:fresh --seed --force

Write-Host "Instalasi Selesai!"
