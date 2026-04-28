<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventaris', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 50)->nullable();
            $table->string('kategori', 30)->nullable();
            $table->integer('stok')->default(0);
            $table->bigInteger('harga')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventaris');
    }
};
