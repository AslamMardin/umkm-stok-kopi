<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\DetailProduksi;
use App\Models\Produksi;
use App\Models\ProdukKopi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduksiController extends Controller
{
    public function index()
    {
        $produksis = Produksi::with('user')
            ->latest()
            ->paginate(15);

        return view('produksi.index', compact('produksis'));
    }

    public function create()
    {
        $bahanBakus = BahanBaku::orderBy('nama_bahan')->get();
        $produkList = ProdukKopi::orderBy('nama_produk')->get();

        return view('produksi.create', compact('bahanBakus', 'produkList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_produksi'   => 'required|date',
            'jenis_proses'       => 'required|in:roasting,packing,roasting_packing',
            'catatan'            => 'nullable|string',
            'detail'             => 'required|array|min:1',
            'detail.*.bahan_baku_id'            => 'nullable|exists:bahan_bakus,id',
            'detail.*.jumlah_bahan_digunakan'   => 'nullable|numeric|min:0',
            'detail.*.produk_id'                => 'nullable|exists:produk_kopis,id',
            'detail.*.jumlah_produk_dihasilkan' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $noProduksi = 'PRD-' . date('Ymd') . '-' . str_pad(
                Produksi::whereDate('created_at', today())->count() + 1,
                4, '0', STR_PAD_LEFT
            );

            $produksi = Produksi::create([
                'no_produksi'      => $noProduksi,
                'tanggal_produksi' => $request->tanggal_produksi,
                'jenis_proses'     => $request->jenis_proses,
                'status'           => 'proses',
                'catatan'          => $request->catatan,
                'user_id'          => auth()->id(),
            ]);

            foreach ($request->detail as $item) {
                DetailProduksi::create([
                    'produksi_id'              => $produksi->id,
                    'bahan_baku_id'            => $item['bahan_baku_id'] ?: null,
                    'jumlah_bahan_digunakan'   => $item['jumlah_bahan_digunakan'] ?: null,
                    'produk_id'                => $item['produk_id'] ?: null,
                    'jumlah_produk_dihasilkan' => $item['jumlah_produk_dihasilkan'] ?: null,
                    'keterangan'               => $item['keterangan'] ?? null,
                ]);
            }
        });

        return redirect()->route('produksi.index')
            ->with('success', 'Data produksi berhasil dicatat.');
    }

    public function show(Produksi $produksi)
    {
        $produksi->load('detailProduksis.bahanBaku', 'detailProduksis.produkKopi', 'user');
        return view('produksi.show', compact('produksi'));
    }

    /**
     * Selesaikan produksi:
     * → Kurangi stok bahan baku secara otomatis
     * → Tambah stok produk jadi secara otomatis
     */
    public function selesaikan(Produksi $produksi)
    {
        if ($produksi->status !== 'proses') {
            return back()->with('error', 'Hanya produksi berstatus "proses" yang dapat diselesaikan.');
        }

        try {
            $produksi->selesaikanProduksi();
            return redirect()->route('produksi.show', $produksi)
                ->with('success', 'Produksi selesai. Stok bahan baku berkurang dan stok produk jadi bertambah.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
