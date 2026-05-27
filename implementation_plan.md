# Rencana Implementasi: Dashboard Header Premium & Mekanisme Pengarsipan Kandidat (Soft-Delete)

Rencana kerja terperinci untuk memperbarui tampilan header Dashboard Utama agar memukau dan serasi dengan halaman **Log Aktivitas**, serta mengubah proses penghapusan kandidat dari penghapusan fisik permanen menjadi sistem pengarsipan (**soft-delete**) agar data statistik bulanan dan catatan riwayat aktivitas HRD tetap utuh.

---

## User Review Required

> [!IMPORTANT]
> - **Transformasi Desain Header**: Header hitam/gelap pada Dashboard Utama (`overview.blade.php`) akan diperbarui secara total menggunakan struktur grid, glow efek ambient blur, dan widget ringkasan sisi kanan yang persis sama dengan tampilan premium di halaman **Log Aktivitas**.
> - **Database Schema Migration**: Kami akan menambahkan kolom boolean baru `is_archived` (default `false`) pada tabel `applications` melalui migrasi Laravel (`php artisan migrate`). Kolom ini digunakan untuk menandai data kandidat yang telah "dihapus" oleh recruiter dari papan aktif.
> - **Preservasi Log & Statistik**: Dengan metode ini, kandidat yang "dihapus" hanya akan hilang dari papan Kanban aktif dan daftar tahapan, namun **tetap tercatat** dalam total statistik pelamar tahunan/bulanan, dan datanya di halaman **Log Aktivitas** tetap terbaca sempurna (tidak menjadi kosong atau rusak).

---

## Open Questions

> [!NOTE]
> Kami tidak memiliki pertanyaan terbuka untuk saat ini karena seluruh instruksi Anda sangat jelas dan solusi ini adalah opsi yang paling aman, solid, dan tidak memiliki efek samping merusak pada data riwayat rekrutmen Anda.

---

## Proposed Changes

### Database Schema Update

#### [NEW] [2026_05_27_170000_add_is_archived_to_applications_table.php](file:///c:/xampp/htdocs/web-recruitment-ats/database/migrations/2026_05_27_170000_add_is_archived_to_applications_table.php)
- Membuat file migrasi baru untuk menambahkan kolom `is_archived` ke tabel `applications`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('is_archived');
        });
    }
};
```

---

### UI & Styling Updates

#### [MODIFY] [overview.blade.php](file:///c:/xampp/htdocs/web-recruitment-ats/resources/views/livewire/hrd/overview.blade.php)
- Mengganti elemen Header/Banner di bagian atas agar menggunakan styling premium yang sama persis seperti di `activity-logs.blade.php`.
- Menambahkan judul besar putih `"Dashboard Utama & Ringkasan ATS"` dan teks deskripsi yang elegan.
- Menambahkan **widget total pelamar bertema glassmorphism di sebelah kanan** (`bg-white/10 backdrop-blur-md`) lengkap dengan bayangan *shadow-inner* untuk menyelaraskan dengan estetika log aktivitas.

#### [MODIFY] [analytics.blade.php](file:///c:/xampp/htdocs/web-recruitment-ats/resources/views/livewire/hrd/analytics.blade.php)
- Menyelaraskan banner di halaman Analitik agar memiliki gaya dan tata letak yang identik untuk menjaga konsistensi visual di seluruh sistem admin HRD.

---

### Backend Logic & Queries (Archiving Mechanism)

#### [MODIFY] [Dashboard.php](file:///c:/xampp/htdocs/web-recruitment-ats/app/Livewire/Hrd/Dashboard.php)
- **Hapus Fisik ➔ Arsipkan (Individual)**: Mengubah `deleteApplication(int $id)` agar tidak memanggil `$app->delete()`, melainkan `$app->update(['is_archived' => true])`. Log aksi ini di database sebagai aksi `'deleted'` agar terekam di audit trail.
- **Hapus Fisik ➔ Arsipkan (Massal)**: Mengubah `bulkDelete()` untuk melakukan pembaruan massal `is_archived = true` pada aplikasi terpilih dan mencatat log aktivitas untuk masing-masing kandidat.
- **Kanban Column Filters**: Menambahkan filter `->where('is_archived', false)` pada query pengelompokan tahapan Kanban (Administrasi, Psikotes, Interview, MCU, Hired) agar kandidat terarsip otomatis tidak tampil di papan.
- **Table View Filter**: Menambahkan filter `->where('is_archived', false)` pada query tampilan list tabel.

#### [MODIFY] [CandidateStage.php](file:///c:/xampp/htdocs/web-recruitment-ats/app/Livewire/Hrd/CandidateStage.php)
- **Stage List Filter**: Menambahkan filter `->where('is_archived', false)` pada query `render()` agar kandidat yang telah dihapus menghilang dari halaman proses tahapan spesifik.
- **Hapus Fisik ➔ Arsipkan (Individual & Massal)**: Menyesuaikan method `deleteApplication(int $id)` dan `bulkDelete()` di file ini agar memperbarui nilai `is_archived = true`.

#### [MODIFY] [HiredCandidates.php](file:///c:/xampp/htdocs/web-recruitment-ats/app/Livewire/Hrd/HiredCandidates.php)
- Menambahkan filter `->where('is_archived', false)` pada pemuatan data karyawan yang diterima.
- Menyesuaikan method `deleteApplication(int $id)` dan `bulkDelete()` agar memperbarui nilai `is_archived = true`.

#### [MODIFY] [RejectedCandidates.php](file:///c:/xampp/htdocs/web-recruitment-ats/app/Livewire/Hrd/RejectedCandidates.php)
- Menambahkan filter `->where('is_archived', false)` pada pemuatan data kandidat ditolak.
- Menyesuaikan method `deleteApplication(int $id)` dan `bulkDelete()` agar memperbarui nilai `is_archived = true`.

#### [MODIFY] [Overview.php](file:///c:/xampp/htdocs/web-recruitment-ats/app/Livewire/Hrd/Overview.php) & [Analytics.php](file:///c:/xampp/htdocs/web-recruitment-ats/app/Livewire/Hrd/Analytics.php)
- Menyesuaikan metrik `$totalActive` agar mengecualikan status `'Draft'` dan kandidat yang terarsip (`is_archived = true`):
```php
$totalActive = Application::whereNotIn('status', ['Hired', 'Ditolak', 'Draft'])
    ->where('is_archived', false)
    ->count();
```
- Menjamin metrik **Total Pelamar** (`$totalCandidates` / `$administrasiCount`) tetap menghitung seluruh kandidat yang pernah masuk (termasuk yang diarsipkan/soft-deleted) agar data rekap data historis pelamar bulanan tidak berkurang/hilang.

---

## Verification Plan

### Automated Tests
1. **Linter Syntax Check**:
   Menjalankan pengecekan linter sintaksis PHP untuk memastikan tidak ada kesalahan penulisan kode:
   ```bash
   php -l app/Livewire/Hrd/Dashboard.php
   php -l app/Livewire/Hrd/CandidateStage.php
   php -l app/Livewire/Hrd/Overview.php
   php -l app/Livewire/Hrd/Analytics.php
   php -l app/Livewire/Hrd/HiredCandidates.php
   php -l app/Livewire/Hrd/RejectedCandidates.php
   ```

2. **Database Migration**:
   Menjalankan perintah migrasi Laravel untuk memperbarui skema database:
   ```bash
   php artisan migrate
   ```

### Manual Verification
1. **Verifikasi Visual Header**:
   - Membuka halaman **Dashboard** utama (Overview) dan memastikan banner hitam di atas sekarang memiliki tulisan besar putih `"Dashboard Utama & Ringkasan ATS"` beserta deskripsi premium dan widget angka total pelamar di sebelah kanan yang persis sama dengan gaya halaman **Log Aktivitas**.
2. **Verifikasi Pengarsipan & Kanban**:
   - Pilih satu kandidat di papan Kanban, klik tombol hapus.
   - Pastikan kandidat tersebut langsung menghilang dari papan Kanban secara instan.
   - Periksa database tabel `applications` untuk memastikan baris data kandidat tersebut **TIDAK terhapus**, melainkan kolom `is_archived` berubah menjadi `1` (true).
3. **Verifikasi Statistik & Rekap**:
   - Pastikan angka metrik **Total Pelamar** di Dashboard Utama tidak berkurang/tetap sama setelah kandidat tersebut dihapus (karena rekap data historis pelamar bulanan tetap dipertahankan).
4. **Verifikasi Log Aktivitas**:
   - Membuka halaman **Log Aktivitas**, pastikan catatan log tentang kandidat yang dihapus tersebut **tetap ada** dan nama kandidat tersebut **tetap tertera dengan jelas** (tidak menjadi kosong/rusak/menampilkan tanda strip `—`).
