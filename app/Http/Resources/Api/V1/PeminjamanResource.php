<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeminjamanResource extends JsonResource
{
    /**
     * Transform the resource into an array with dynamic HATEOAS controls.
     */
    public function toArray(Request $request): array
    {
        $isBorrowed = ($this->status === 'dipinjam');

        $links = [
            'self' => [
                'href' => url("/api/v1/peminjamans/{$this->id}"),
                'method' => 'GET',
            ],
            'buku_detail' => [
                'href' => url("/api/v1/bukus/{$this->buku_id}"),
                'method' => 'GET',
            ],
        ];

        // Keindahan HATEOAS: Link aksi 'kembalikan_buku' HANYA muncul jika status masih 'dipinjam'!
        if ($isBorrowed) {
            $links['kembalikan_buku'] = [
                'href' => url("/api/v1/peminjamans/{$this->id}/kembalikan"),
                'method' => 'PATCH',
                'description' => 'Memproses pengembalian buku dan memulihkan stok secara atomik',
            ];
        }

        return [
            'id' => $this->id,
            'kode_pinjam' => $this->kode_pinjam,
            'buku_id' => $this->buku_id,
            'nim_peminjam' => $this->nim_peminjam,
            'nama_peminjam' => $this->nama_peminjam,
            'tanggal_pinjam' => $this->tanggal_pinjam?->format('Y-m-d') ?? $this->tanggal_pinjam,
            'tanggal_jatuh_tempo' => $this->tanggal_jatuh_tempo?->format('Y-m-d') ?? $this->tanggal_jatuh_tempo,
            'tanggal_kembali' => $this->tanggal_kembali?->format('Y-m-d') ?? $this->tanggal_kembali,
            'status' => $this->status,
            'status_label' => $isBorrowed ? 'SEDANG DIPINJAM' : 'TELAH DIKEMBALIKAN',
            'buku' => $this->whenLoaded('buku', function () {
                return [
                    'id' => $this->buku->id,
                    'isbn' => $this->buku->isbn,
                    'judul' => $this->buku->judul,
                    'penulis' => $this->buku->penulis,
                    'stok' => $this->buku->stok,
                ];
            }),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            '_links' => $links,
        ];
    }
}
