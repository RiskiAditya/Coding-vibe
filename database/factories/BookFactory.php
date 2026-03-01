<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $books = [
            [
                'title' => 'Sejarah Peradaban Islam',
                'author' => 'Dr. Ahmad Rahman',
                'description' => 'Buku komprehensif tentang perkembangan peradaban Islam dari masa ke masa',
                'isbn' => '978-623-123-456-7',
                'publisher' => 'Penerbit Akademik',
                'publication_year' => 2023,
            ],
            [
                'title' => 'Teknologi Modern dan AI',
                'author' => 'Prof. Siti Nurhaliza',
                'description' => 'Panduan lengkap tentang kecerdasan buatan dan dampaknya pada kehidupan modern',
                'isbn' => '978-623-234-567-8',
                'publisher' => 'Penerbit Teknologi',
                'publication_year' => 2023,
            ],
            [
                'title' => 'Sastra Indonesia Klasik',
                'author' => 'Dra. Maya Sari',
                'description' => 'Kumpulan karya sastra Indonesia klasik dengan analisis mendalam',
                'isbn' => '978-623-345-678-9',
                'publisher' => 'Penerbit Sastra',
                'publication_year' => 2022,
            ],
            [
                'title' => 'Matematika Diskrit',
                'author' => 'Dr. Budi Santoso',
                'description' => 'Dasar-dasar matematika diskrit untuk mahasiswa teknik informatika',
                'isbn' => '978-623-456-789-0',
                'publisher' => 'Penerbit Pendidikan',
                'publication_year' => 2023,
            ],
            [
                'title' => 'Fisika Modern',
                'author' => 'Prof. Rina Wijaya',
                'description' => 'Penjelasan mendalam tentang teori relativitas dan mekanika kuantum',
                'isbn' => '978-623-567-890-1',
                'publisher' => 'Penerbit Sains',
                'publication_year' => 2022,
            ],
            [
                'title' => 'Bahasa Pemrograman Python',
                'author' => 'Ir. Dedi Kurniawan',
                'description' => 'Panduan praktis belajar Python dari dasar hingga advanced',
                'isbn' => '978-623-678-901-2',
                'publisher' => 'Penerbit Komputer',
                'publication_year' => 2023,
            ],
            [
                'title' => 'Manajemen Proyek',
                'author' => 'Dr. Eko Prasetyo',
                'description' => 'Strategi dan teknik manajemen proyek yang efektif untuk bisnis modern',
                'isbn' => '978-623-789-012-3',
                'publisher' => 'Penerbit Bisnis',
                'publication_year' => 2023,
            ],
            [
                'title' => 'Psikologi Pendidikan',
                'author' => 'Prof. Lestari Dewi',
                'description' => 'Memahami psikologi siswa dan strategi pembelajaran yang optimal',
                'isbn' => '978-623-890-123-4',
                'publisher' => 'Penerbit Psikologi',
                'publication_year' => 2022,
            ],
        ];

        $book = $this->faker->randomElement($books);

        return [
            'title' => $book['title'],
            'author' => $book['author'],
            'isbn' => $book['isbn'],
            'description' => $book['description'],
            'publisher' => $book['publisher'],
            'publication_year' => $book['publication_year'],
            'quantity' => $this->faker->numberBetween(5, 20),
            'available_quantity' => function (array $attributes) {
                return $attributes['quantity'];
            },
            'featured' => $this->faker->boolean(30), // 30% chance of being featured
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'image' => null,
        ];
    }
}
