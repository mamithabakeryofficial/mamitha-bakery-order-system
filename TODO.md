# TODO

## Progress

- [x] Baca routes/web.php (admin.products & admin.categories ada)
- [x] Baca ProductController (validasi & assignment masih ada kolom `stock` awalnya)
- [x] Baca migration products (kolom `stock` memang ada)
- [x] Baca form admin produk (create/edit) untuk cek field apa saja yang tampil
- [x] Konfirmasi kebutuhan: kategori masih ada (untuk mapping roti), tetapi isi kategori kosong boleh; yang diminta adalah **hapus stock**.

## Plan (final)

1. Update admin produk (create/edit/index): hapus input/penyebutan `stock` bila ada.
2. Update `ProductController`: hapus rule validasi `stock` dan field assignment `stock`.
3. Update `Product` model: pastikan `$fillable` tidak menyertakan `stock`.
4. Hapus kolom `stock` dari tabel `products` via migration baru (dan update down-nya).
5. Update semua dependency lain yang pakai `stock` (kitchen/customer/order/cart) agar tidak error.
6. Jalankan `php artisan migrate` (atau refresh) dan lakukan sanity check.

## Status implementasi saat ini

- [x] ProductController sudah ditulis ulang tanpa `stock` (via create_file).
- [x] Product model sudah ditulis ulang tanpa `stock` (tapi sebelumnya sempat sempat error, sudah fix).

## Next steps yang belum selesai

- [ ] Cek apakah ada field `stock` di create/edit/index blade (saat ini terlihat belum ada, tapi perlu cek lengkap).
- [ ] Buat migration baru untuk drop column `stock` dari `products`.
- [ ] Cari dan update kode yang masih mengakses `stock` (mis. perhitungan stok di kitchen/customer/cart/order).
