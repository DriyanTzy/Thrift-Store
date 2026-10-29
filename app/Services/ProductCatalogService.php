<?php

namespace App\Services;

use App\DTO\CreateProductDTO;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductCatalogService
{
    public function __construct(
        private readonly StockLedgerService $stockLedgerService
    ) {}

    /**
     * Daftarkan produk baru ke katalog menggunakan CreateProductDTO.
     */
    public function createProduct(User $seller, CreateProductDTO $dto): Product
    {
        return DB::transaction(function () use ($seller, $dto) {
            $slug = Str::slug($dto->name).'-'.strtolower(Str::random(5));

            // Simpan gambar utama
            $mainImagePath = null;
            if (! empty($dto->images)) {
                $mainImagePath = $dto->images[0]->store('products', 'public');
            }

            // Normalisasi pemetaan ukuran (sizes)
            [$sizeStocks, $totalStock] = $this->resolveSizeStocks($dto);

            $costPrice = $dto->costPrice ?? round($dto->price * 0.7);

            $product = Product::create([
                'user_id' => $seller->id,
                'category_id' => $dto->categoryId,
                'name' => $dto->name,
                'slug' => $slug,
                'description' => $dto->description,
                'price' => $dto->price,
                'cost_price' => $costPrice,
                'stock' => $totalStock,
                'sizes' => json_encode($sizeStocks),
                'badge' => $dto->badge,
                'image' => $mainImagePath,
                'is_active' => true,
            ]);

            // Simpan galeri gambar produk
            foreach ($dto->images as $index => $file) {
                $path = ($index === 0 && $mainImagePath) ? $mainImagePath : $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'sort_order' => $index,
                ]);
            }

            // Catat mutasi stok masuk awal via StockLedgerService
            $this->stockLedgerService->record(
                product: $product,
                quantity: $totalStock,
                type: 'in',
                reason: 'Stok Awal Barang Baru Masuk ke Etalase',
                reference: 'INIT-'.$product->id,
                userId: $seller->id
            );

            return $product;
        });
    }

    /**
     * Hitung stok per ukuran dan total stok akumulasi dari DTO.
     *
     * @return array{0: array<string, int>, 1: int}
     */
    private function resolveSizeStocks(CreateProductDTO $dto): array
    {
        $sizeStocks = [];
        $totalStock = 0;

        if (! empty($dto->sizeStocks)) {
            foreach ($dto->sizeStocks as $sz => $qty) {
                $q = max(0, intval($qty));
                if ($q > 0) {
                    $sizeStocks[$sz] = $q;
                    $totalStock += $q;
                }
            }
        }

        if (empty($sizeStocks)) {
            $totalStock = max(1, $dto->stock);
            if (! empty($dto->sizes)) {
                $each = max(1, (int) floor($totalStock / max(1, count($dto->sizes))));
                foreach ($dto->sizes as $sz) {
                    $sizeStocks[$sz] = $each;
                }
            } else {
                $sizeStocks['All Size'] = $totalStock;
            }
        }

        return [$sizeStocks, $totalStock];
    }
}
