<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Buku>
 */
class BukuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'isbn' => $this->faker->unique()->isbn13(),
            'judul' => ucwords($this->faker->words(3, true)),
            'penulis' => $this->faker->name(),
            'penerbit' => $this->faker->company() . ' Press',
            'tahun_terbit' => $this->faker->numberBetween(2010, 2026),
            'kategori_id' => Kategori::factory(),
            'stok' => $this->faker->numberBetween(1, 50),
            'sinopsis' => $this->faker->paragraph(3),
        ];
    }
}
