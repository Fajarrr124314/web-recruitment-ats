# Tambah Fitur Dashboard Analytics

Tujuan dari rencana ini adalah untuk menambahkan fitur Dashboard utama di atas fitur "Proses Kandidat" pada halaman HRD. Dashboard ini akan menampilkan grafik (chart) data kandidat per bulan dan statistik ringkas yang didesain interaktif dan modern.

## User Review Required

> [!IMPORTANT]
> Mohon konfirmasi apakah Anda setuju menggunakan **Chart.js** untuk me-render grafik (chart) interaktif? Chart.js adalah library yang ringan dan menghasilkan grafik yang indah.

## Open Questions

> [!WARNING]
> Untuk saat ini, chart akan menampilkan data jumlah pelamar yang masuk setiap bulannya pada tahun berjalan. Apakah Anda juga ingin menambahkan filter tahun di masa depan? (Saat ini saya akan buat default untuk tahun ini terlebih dahulu).

## Proposed Changes

---

### Routing & Layout

#### [MODIFY] [routes/web.php](file:///c:/laragon/www/web-rekruitmen/routes/web.php)
- Menambahkan route baru `/hrd/overview` untuk halaman Dashboard.
- Mengubah *redirect* default HRD dari `/hrd/dashboard` menjadi `/hrd/overview`.

#### [MODIFY] [hrd.blade.php](file:///c:/laragon/www/web-rekruitmen/resources/views/components/layouts/hrd.blade.php)
- Menambahkan menu **"Dashboard"** pada sidebar di atas "Proses Kandidat".
- Mengupdate rute navigasi.

#### [MODIFY] [app.blade.php](file:///c:/laragon/www/web-rekruitmen/resources/views/components/layouts/app.blade.php)
- Mengubah *link* "Dashboard HRD" pada navbar desktop dan mobile untuk mengarah ke Dashboard baru.

---

### Dashboard Component

#### [NEW] [Overview.php](file:///c:/laragon/www/web-rekruitmen/app/Livewire/Hrd/Overview.php)
- Membuat komponen Livewire baru `Overview`.
- Mengambil data dari model `Application`, mengelompokkannya berdasarkan bulan (Januari - Desember) untuk tahun berjalan.
- Menghitung statistik singkat (Total Kandidat, Kandidat Diterima/Hired, Kandidat Ditolak).

#### [NEW] [overview.blade.php](file:///c:/laragon/www/web-rekruitmen/resources/views/livewire/hrd/overview.blade.php)
- Membuat tampilan (UI) dashboard yang sangat modern dan premium (menggunakan *glassmorphism*, gradasi warna yang serasi, dan bayangan *shadow* yang lembut).
- Menyertakan **Chart.js** via CDN.
- Merender grafik Bar/Line Chart yang menunjukkan fluktuasi jumlah pelamar setiap bulannya.
- Menambahkan kotak-kotak metrik ringkas di bagian atas chart.

## Verification Plan

### Manual Verification
1. Login sebagai HRD.
2. Memastikan menu "Dashboard" muncul di posisi teratas sidebar dan terbuka secara default.
3. Memastikan grafik (Chart) ter-render dengan baik di layar desktop maupun mobile.
4. Memastikan angka pada grafik sesuai dengan jumlah pelamar (candidates/applications) di database.
5. Memastikan navigasi ke "Proses Kandidat" tetap berfungsi dengan baik.
