php artisan serve
```

Buka browser ke `http://localhost:8000`, login dengan:
- Email: `admin@kopipwm.com`
- Password: `password123`

---
---

## Fungsi Pembelian, Produksi, dan Penjualan

Ini adalah **tiga inti utama** sistem. Ketiganya saling terhubung lewat stok.

---

### 🛒 PEMBELIAN — "Stok Bahan Baku Masuk"

**Fungsinya:** Mencatat setiap kali kamu membeli biji kopi mentah (green bean) atau bahan lain dari petani/supplier.

**Kenapa penting:** Ini adalah **pintu masuk pertama** bahan baku ke gudang kamu. Tanpa mencatat pembelian, sistem tidak tahu kamu punya stok bahan baku berapa.

**Alurnya:**
```
Catat Pembelian (status: pending)
        ↓
Barang tiba di gudang
        ↓
Klik "Terima Barang"
        ↓
Stok bahan baku otomatis bertambah ✅
```

**Contoh nyata:**

Senin pagi kamu pesan 100 kg Green Bean Robusta dari Koperasi Tani Maju seharga Rp 35.000/kg. Kamu catat di sistem, status masih `pending`. Selasa barang datang, kamu klik **Terima Barang** — otomatis stok Green Bean Robusta di sistem bertambah 100 kg.

**Yang dicatat sistem:**
- Dari supplier mana
- Tanggal beli
- Bahan apa saja, berapa kg, harga berapa per kg
- Total harga keseluruhan
- Siapa yang mencatat

---

### ⚙️ PRODUKSI — "Bahan Baku Jadi Produk"

**Fungsinya:** Mencatat proses mengubah green bean menjadi kopi siap jual — mulai dari roasting (sangrai) sampai packing (kemas).

**Kenapa penting:** Ini adalah **jembatan** antara bahan baku dan produk jadi. Di sinilah stok bahan baku berkurang dan stok produk jadi bertambah secara bersamaan.

**Alurnya:**
```
Catat Produksi (status: proses)
        ↓
Tulis: bahan apa yang dipakai + produk apa yang dihasilkan
        ↓
Proses sangrai/packing selesai
        ↓
Klik "Selesaikan Produksi"
        ↓
Stok bahan baku otomatis berkurang ✅
Stok produk jadi otomatis bertambah ✅
```

**Contoh nyata:**

Rabu kamu sangrai 20 kg Green Bean Arabika dan kemas hasilnya menjadi 85 bungkus Arabika Medium Roast 200g. Kamu catat di sistem:
- Bahan masuk: Green Bean Arabika 20 kg
- Produk keluar: Arabika Medium Roast 200g 85 pcs

Setelah selesai, klik **Selesaikan** — stok green bean turun 20 kg, stok produk naik 85 pcs.

**Jenis proses yang bisa dicatat:**

| Jenis | Artinya |
|---|---|
| Roasting | Hanya sangrai, belum dikemas |
| Packing | Hanya mengemas (dari roasted bean yang sudah ada) |
| Roasting + Packing | Sangrai langsung dikemas dalam satu batch |

---

### 💰 PENJUALAN — "Stok Produk Keluar"

**Fungsinya:** Mencatat setiap transaksi penjualan produk kopi yang sudah dikemas ke pelanggan.

**Kenapa penting:** Ini adalah **pintu keluar** produk jadi dari gudang. Setiap penjualan yang dikonfirmasi akan otomatis mengurangi stok produk.

**Alurnya:**
```
Catat Penjualan (status: pending)
        ↓
Pilih produk, jumlah, harga
        ↓
Pelanggan bayar
        ↓
Klik "Konfirmasi Lunas"
        ↓
Stok produk otomatis berkurang ✅
```

**Contoh nyata:**

Jumat siang Kafe Mandar Coffee datang beli 20 bungkus Arabika Medium Roast 200g dan 10 bungkus Robusta Dark Roast 200g. Kamu catat di sistem, total Rp 1.690.000. Mereka bayar tunai, kamu klik **Konfirmasi Lunas** — stok kedua produk langsung berkurang otomatis.

**Fitur tambahan penjualan:**
- Bisa pilih pelanggan terdaftar atau pelanggan umum (walk-in)
- Bisa tambah diskon per item atau diskon global
- Pilihan metode bayar: Tunai, Transfer, COD
- Jika ada kesalahan, bisa **dibatalkan** dan stok otomatis dikembalikan

---

## Hubungan Ketiganya
```
PEMBELIAN                PRODUKSI               PENJUALAN
(Beli dari petani)  →  (Sangrai & Kemas)  →  (Jual ke pelanggan)
      ↓                       ↓                      ↓
Stok bahan baku         Bahan baku TURUN        Stok produk
   NAIK ✅              Produk jadi NAIK ✅        TURUN ✅