<?php

namespace App\Observers;

use App\Events\ProductValidated;
use App\Models\Product;
use App\Services\MoulinetteMatchService;
use Illuminate\Support\Facades\DB;
use Storage;

class ProductObserver
{
    /**
     * Handle the product "created" event.
     *
     * @param  Product  $product
     *
     * @return void
     */
    public function created(Product $product)
    {
        //
    }

    /**
     * Handle the product "updated" event.
     *
     * @param  Product  $product
     *
     * @return void
     */
    public function updated(Product $product)
    {
        if ($product->wasChanged('sent')) {
            if ($product->sent == 1) {
                ProductValidated::dispatch($product);
                $service = new MoulinetteMatchService();
                $service->product($product);
            }
        } elseif ($product->sent != 0) {
            $product->research()->detach();
            $product->sent = 0;
            $product->save();
        }

//        if ($product->wasChanged('img')) {
//            $original = $product->getOriginal('img');
//            if ($original === null) {
//                return;
//            }
//            $changes = $product->getChanges();
//            //            if (gettype($original)=="string") {
//            //                $original = json_decode($original, true);
//            //            }
//            //            if (gettype($changes['img'])=="string") {
//            //                $changes['img']=json_decode($changes['img']);
//            //            }
//            $oldPhotos = $this->getOldImages($original, $changes['img']);
//            foreach ($oldPhotos as $photo) {
//                $this->removeImageFromDisk($photo);
//            }
//        }
    }

    /**
     * Handle the product "deleted" event.
     *
     * @param  Product  $product
     *
     * @return void
     */
    public function deleted(
        Product $product
    ) {
        try {
//            if ($product->img) {
//                foreach ($product->img as $image) {
//                    $this->removeImageFromDisk($image);
//                }
//            }
            DB::table('notifications')->whereJsonContains(
                'data',
                ['products' => ['id' => $product->id]]
            )->delete();
        } catch (\ErrorException $e) {
            \Log::error($e->getMessage());
        }
        $product->research()->detach();
        // TODO : What is order ?
        $product->orders()->detach();
    }

    /**
     * Handle the product "restored" event.
     *
     * @param  Product  $product
     *
     * @return void
     */
    public function restored(
        Product $product
    ) {
        //
    }

    /**
     * Handle the product "force deleted" event.
     *
     * @param  Product  $product
     *
     * @return void
     */
    public function forceDeleted(
        Product $product
    ) {
        //
    }

    /**
     * @param $image
     */
    private function removeImageFromDisk($image): void
    {
        $image = str_replace('/storage', 'public', $image);
        Storage::delete($image);
    }

    /**
     * @param $original
     * @param $img
     *
     * @return mixed
     * @throws \JsonException
     */
    private function getOldImages($original, $img)
    {
        $oldPhotos = array_diff(
            json_decode($original, true, 512, JSON_THROW_ON_ERROR),
            json_decode(
                $img,
                true,
                512,
                JSON_THROW_ON_ERROR
            )
        );

        return $oldPhotos;
    }
}
