<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Complete the original minimal users migration without deleting data.
        foreach (['name', 'email', 'password', 'role', 'email_verified_at', 'remember_token'] as $column) {
            if (!Schema::hasColumn('users', $column)) {
                Schema::table('users', function (Blueprint $table) use ($column) {
                    match ($column) {
                        'name' => $table->string('name')->default(''),
                        'email' => $table->string('email')->nullable()->unique(),
                        'password' => $table->string('password')->default(''),
                        'role' => $table->unsignedTinyInteger('role')->default(0),
                        'email_verified_at' => $table->timestamp('email_verified_at')->nullable(),
                        'remember_token' => $table->rememberToken(),
                    };
                });
            }
        }

        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('nom_categorie');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('produits')) {
            Schema::create('produits', function (Blueprint $table) {
                $table->id();
                $table->string('product_name');
                $table->text('product_description')->nullable();
                $table->decimal('product_price', 12, 2);
                $table->unsignedInteger('quantity_available')->default(0);
                $table->text('product_img')->nullable();
                $table->unsignedTinyInteger('discount')->nullable()->default(0);
                $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('cart_items')) {
            Schema::create('cart_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->constrained('produits')->cascadeOnDelete();
                $table->unsignedInteger('quantity')->default(1);
                $table->timestamps();
                $table->unique(['user_id', 'product_id']);
            });
        }
        if (!Schema::hasTable('commandes')) {
            Schema::create('commandes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->decimal('total', 12, 2)->default(0);
                $table->unsignedTinyInteger('status')->default(0);
                $table->boolean('livraison')->default(false);
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('commande_product')) {
            Schema::create('commande_product', function (Blueprint $table) {
                $table->id();
                $table->foreignId('commande_id')->constrained('commandes')->cascadeOnDelete();
                $table->foreignId('product_id')->constrained('produits')->cascadeOnDelete();
                $table->unsignedInteger('quantity');
                $table->decimal('price', 12, 2);
                $table->unique(['commande_id', 'product_id']);
            });
        }
    }

    public function down(): void
    {
        // This additive repair can encounter pre-existing tables. Keep their data.
    }
};
