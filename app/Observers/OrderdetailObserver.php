<?php

namespace App\Observers;

use App\Models\Orderdetail;
use App\Models\Product;

class OrderdetailObserver
{
    /**
     * Handle the Orderdetail "created" event.
     * Kurangi stok produk ketika detail pesanan dibuat
     */
    public function created(Orderdetail $orderdetail): void
    {
        /** @var Product|null $product */
        $product = Product::find($orderdetail->product_id);
        if ($product) {
            $product->stock -= $orderdetail->quantity;
            $product->save();
        }
    }

    /**
     * Handle the Orderdetail "updated" event.
     * Sesuaikan stok jika quantity berubah
     */
    public function updated(Orderdetail $orderdetail): void
    {
        // Hanya jalankan jika quantity berubah
        if ($orderdetail->isDirty('quantity')) {
            /** @var Product|null $product */
            $product = Product::find($orderdetail->product_id);
            if ($product) {
                // Kembalikan stok lama
                $oldQuantity = $orderdetail->getOriginal('quantity');
                $product->stock += $oldQuantity;
                
                // Kurangi stok baru
                $product->stock -= $orderdetail->quantity;
                $product->save();
            }
        }
        
        // Jika product_id berubah
        if ($orderdetail->isDirty('product_id')) {
            // Kembalikan stok ke produk lama
            $oldProductId = $orderdetail->getOriginal('product_id');
            /** @var Product|null $oldProduct */
            $oldProduct = Product::find($oldProductId);
            if ($oldProduct) {
                $oldProduct->stock += $orderdetail->getOriginal('quantity');
                $oldProduct->save();
            }
            
            // Kurangi stok dari produk baru
            /** @var Product|null $newProduct */
            $newProduct = Product::find($orderdetail->product_id);
            if ($newProduct) {
                $newProduct->stock -= $orderdetail->quantity;
                $newProduct->save();
            }
        }
    }

    /**
     * Handle the Orderdetail "deleted" event.
     * Kembalikan stok ketika detail pesanan dihapus
     */
    public function deleted(Orderdetail $orderdetail): void
    {
        /** @var Product|null $product */
        $product = Product::find($orderdetail->product_id);
        if ($product) {
            $product->stock += $orderdetail->quantity;
            $product->save();
        }
    }
}
