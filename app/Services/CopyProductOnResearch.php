<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Research;
use App\Models\SoldProduct;
use Storage;
use Str;

/**
 * Class CopyProductOnResearch.
 *
 * @author Thomas Tosch <thomas.tosch@wigo-media.com>
 */
class CopyProductOnResearch
{
    /**
     * Adds to the relation of research "productMissed", displaying missed
     * potential products Called when Obvy transaction is done and after
     * product was set to sold in relation to it's research.
     *
     * @param  \App\Models\Product  $product
     * @param  \App\Models\SoldProduct  $soldProduct
     */
    public function save(Product $product, SoldProduct $soldProduct): void
    {
        $product->research->each(function ($research) use ($soldProduct) {
            $research->productMissed()->save($soldProduct);
        });
    }

    /**
     * Add the product to the research product sold (oneToOne), and returing
     * the SoldProduct model.
     *
     * @param  \App\Models\Product  $product
     * @param  \App\Models\Research  $research
     *
     * @return \App\Models\SoldProduct
     */
    public function sold(Product $product, Research $research): SoldProduct
    {
        $soldProduct = $this->castToSoldProduct($product, $research->id);
        $research->productSold()
            ->save($soldProduct);

        return $soldProduct;
    }

    /**
     * Cast product to SoldProduct.
     *
     * @param  \App\Models\Product  $product
     * @param $research_id
     *
     * @return \App\Models\SoldProduct
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function castToSoldProduct(
        Product $product,
        $research_id
    ): SoldProduct {
        $fields = $product->makeHidden([
            'id', 'created_at', 'updated_at', 'deleted_at', 'img', 'status',
        ])
            ->toArray();

        return SoldProduct::create(array_merge(
            $fields,
            [
                'old_id' => $product->id, 'research_id' => $research_id,
                'img'    => $this->duplicatePhotos($product),
                'status' => $product->status,
            ]
        ));
    }

    /**
     * Keep photos content and order but in new files.
     *
     * @param  \App\Models\Product  $product
     *
     * @return array|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function duplicatePhotos(Product $product): ?string
    {
        try {
            $newImages = [];
            foreach ($product->imgWithIndex as $image) {
                $content = Storage::disk('public')->get(substr($image['url'], 8));
                $fileName = $this->newFileName($image);
                Storage::disk('public')->put(
                    'products/'.$fileName,
                    $content
                );
                array_push($newImages, [
                    'index' => $image['index'],
                    'url'   => '/storage/products/'.$fileName,
                ]);
            }
            if (count($newImages) > 0) {
                $newImages = json_encode($newImages);
            }

            return ($newImages !== []) ? $newImages : null;
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return null;
        }
    }

    /**
     * Random file name with extension from starting fullName.
     *
     * @param $image
     *
     * @return string
     */
    private function newFileName($image): string
    {
        $explode = explode('.', $image['url']);

        return Str::uuid().'.'.$explode[array_key_last($explode)];
    }
}
