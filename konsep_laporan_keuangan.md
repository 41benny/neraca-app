
# 📊 Konsep Aplikasi Laporan Keuangan Otomatis (PSAK-Based)

## 🎯 Tujuan Utama
Membangun **web app** untuk menghasilkan laporan keuangan lengkap (Neraca, Laba Rugi, Arus Kas, dan Catatan atas Laporan Keuangan) secara otomatis berdasarkan **upload file jurnal Excel (XLS/XLSX)**.

Semua laporan mengikuti standar **PSAK (Indonesia)** dengan metode **Indirect Method** untuk laporan arus kas.

---

## ⚙️ Alur Sistem

### 1. Input Data Awal
- Pengguna menentukan **Chart of Accounts (COA)** 3–4 level hierarki.
- Input **Saldo Awal (Opening Balance)** per akun dan per cabang.
- Definisikan **mapping laporan** (akun → kategori laporan keuangan).

### 2. Upload Jurnal Transaksi
- Format file Excel sederhana: `date`, `account_code`, `debit`, `credit`, `description`, `branch`, `invoice_id`, `project_id`, `vehicle_id`.
- Sistem otomatis:
  - Validasi total debit = kredit.
  - Simpan data ke tabel `journal_lines`.
  - Hitung saldo otomatis per akun.

### 3. Mesin Periode (Period Engine)
- Menyusun saldo per akun dan per cabang untuk periode tertentu (bulanan, triwulan, semester, tahunan).
- Menutup laba/rugi tahunan ke akun **Retained Earnings**.
- Lock periode setelah “closing”.

### 4. Laporan Keuangan yang Dihasilkan
#### 🧾 Neraca (Balance Sheet)
- Menampilkan posisi aset, liabilitas, dan ekuitas.
- Menghitung otomatis: **Aset = Kewajiban + Ekuitas.**

#### 💰 Laba Rugi (Profit & Loss)
- Menampilkan pendapatan dan beban untuk menghitung laba bersih.
- Dapat difilter per **invoice**, **project**, atau **mobil (vehicle)**.

#### 💵 Arus Kas (Cash Flow - Indirect)
- Menggunakan laba bersih + penyesuaian non-kas.
- Perubahan aset lancar & kewajiban jangka pendek diidentifikasi otomatis.

#### 📄 Catatan atas Laporan Keuangan (CALK)
- Otomatis menampilkan rincian akun (kas, piutang, persediaan, utang, ekuitas).
- Pengguna dapat menambah catatan manual di halaman editor.

#### 📈 Analisa Keuangan
- Rasio likuiditas, profitabilitas, leverage, dan aktivitas.
- Analisis tren (YoY, QoQ, MoM).
- Filter per cabang / proyek.

---

## 🏗️ Struktur Database (Inti)

### `accounts`
- account_code
- account_name
- level (1–4)
- parent_code
- normal_balance (debit/credit)

### `journal_lines`
- jdate
- doc_no
- account_code
- debit
- credit
- branch_code
- invoice_id
- project_id
- vehicle_id

### `account_mappings`
- account_code
- report_type (neraca/labarugi/arus_kas)
- group_name
- side (asset/liability/equity)
- sign (1/-1)

---

## 🧮 Perhitungan Laporan
- **Saldo akun:** Σ (debit - credit) s.d. tanggal akhir periode.
- **Normal balance:** mempengaruhi tanda di laporan.
- **Laba Ditahan:** saldo awal + laba/rugi tahun berjalan.
- **Arus kas:** dihitung dari perubahan akun kas/bank antar periode.

---

## 🧭 Dimensi & Analisis
Setiap baris jurnal dapat memiliki atribut tambahan:
- branch_code (cabang)
- invoice_id
- project_id
- vehicle_id

Laporan dapat difilter berdasarkan dimensi tersebut, memungkinkan analisa pendapatan per:
- **Invoice**
- **Proyek**
- **Mobil/Armada**

---

## 🎨 UI & UX
- Desain modern berbasis **TailwindCSS + Vite**.
- Tema indigo–purple dengan **dark mode toggle (🌙/☀️)**.
- Navigasi responsif dengan sidebar.
- Dashboard dengan grafik dan indikator keuangan.

---

## 🔒 Fitur Keamanan & Kontrol
- Validasi debit = kredit setiap upload.
- Role-based access control (Admin, Akuntan, Viewer).
- Audit log (siapa meng-upload apa & kapan).
- Lock/unlock periode.

---

## 🚀 Roadmap Pengembangan
| Fase | Fitur Utama | Keterangan |
|------|--------------|------------|
| **1. MVP** | Upload Jurnal, Neraca, Laba Rugi | Dasar perhitungan |
| **2. Extended** | Arus Kas + Analisa Rasio | Tambah kalkulasi otomatis |
| **3. CALK** | Editor Catatan Otomatis | Rangkuman akuntansi & narasi |
| **4. Konsolidasi** | Multi-entitas & FX | PSAK + konsolidasi antar cabang |

---

## 📁 Stack Teknologi
- **Laravel 12** (backend & API)
- **MySQL** (database)
- **TailwindCSS 4 + Vite** (frontend)
- **PhpSpreadsheet** (import Excel)
- **Chart.js** (grafik analisa)
- **PDF & Excel export** (laporan resmi)

---

## 🧩 Fitur Tambahan Rencana
- Upload multi-sheet Excel.
- Auto mapping akun (prefix 11xx = aset, 41xx = pendapatan).
- Template laporan PSAK siap ekspor (PDF).
- API endpoint untuk integrasi POS atau sistem lain.
