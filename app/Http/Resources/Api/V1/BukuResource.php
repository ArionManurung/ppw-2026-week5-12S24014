<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuResource extends JsonResource
{
    /**
     * Transform the resource into an array with dynamic HATEOAS controls.
     */
    public function toArray(Request $request): array
    {
        // Hitung peminjaman jika withCount('peminjamans') dipanggil
        $loansCount = $this->whenCounted('peminjamans', fn () => $this->peminjamans_count, 0);
        $isAvailable = $this->stok > 0;

        // Bangun kontrol Hypermedia HATEOAS (Richardson Maturity Model Level 3)
        $links = [
            'self' => [
                'href' => url("/api/v1/bukus/{$this->id}"),
                'method' => 'GET',
            ],
            'update_full' => [
                'href' => url("/api/v1/bukus/{$this->id}"),
                'method' => 'PUT',
            ],
            'update_partial' => [
                'href' => url("/api/v1/bukus/{$this->id}"),
                'method' => 'PATCH',
            ],
            'delete' => [
                'href' => url("/api/v1/bukus/{$this->id}"),
                'method' => 'DELETE',
            ],
            'referensi_eksternal' => [
                'href' => url("/api/v1/bukus/{$this->id}/referensi-eksternal"),
                'method' => 'GET',
            ],
        ];

        // Keindahan HATEOAS: Link aksi 'pinjam_buku' HANYA muncul jika stok masih tersedia (> 0)!
        if ($isAvailable) {
            $links['pinjam_buku'] = [
                'href' => url('/api/v1/peminjamans'),
                'method' => 'POST',
                'description' => 'Membuat transaksi peminjaman buku ini (membutuhkan Bearer Token)',
            ];
        }

        return [
            'id' => $this->id,
            'isbn' => $this->isbn,
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'penerbit' => $this->penerbit,
            'tahun_terbit' => (int) $this->tahun_terbit,
            'stok' => (int) $this->stok,
            'ketersediaan' => $isAvailable ? 'TERSEDIA' : 'HABIS',
            'sinopsis' => $this->sinopsis,
            'kategori' => $this->whenLoaded('kategori', function () {
                return [
                    'id' => $this->kategori->id,
                    'kode_kategori' => $this->kategori->kode_kategori,
                    'nama_kategori' => $this->kategori->nama_kategori,
                ];
            }),
            'total_kali_dipinjam' => $loansCount,
            'riwayat_peminjaman' => PeminjamanResource::collection($this->whenLoaded('peminjamans')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            '_links' => $links,
        ];
    }
}
