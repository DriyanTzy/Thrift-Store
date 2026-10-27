<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['in', 'out', 'adjustment']); // in = masuk, out = keluar, adjustment = penyesuaian
            $table->integer('quantity'); // jumlah unit mutasi
            $table->integer('stock_before'); // stok sebelum mutasi
            $table->integer('stock_after'); // sisa stok setelah mutasi
            $table->decimal('price', 12, 2)->default(0); // harga jual saat mutasi
            $table->decimal('cost_price', 12, 2)->nullable()->default(0); // harga modal saat mutasi
            $table->string('reference_type')->nullable(); // 'initial', 'restock', 'order', 'adjustment'
            $table->string('reference_id')->nullable(); // misal order_number atau ID referensi
            $table->string('notes')->nullable(); // Keterangan transaksi/mutasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
