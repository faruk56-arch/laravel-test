<?php

namespace App\Http\Controllers\Merchant;

use App\Actions\Translations\CreateTranslation;
use App\Actions\Translations\DeepLTranslation;
use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Modele;
use App\Models\Product;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use InvalidArgumentException;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use Storage;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Merchant $merchant)
    {
        if (!isAdmin() && auth()->user()->merchant_id !== $merchant->id) {
            throw new AccessDeniedHttpException();
        }
        $products = $merchant->products()->latest()->get();

        return response()->json(
            [
                'status' => 'success',
                'data'   => $products,
            ],
            201
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Merchant $merchant, Request $request)
    {
        if (!isAdmin() && auth()->user()->merchant_id !== $merchant->id) {
            throw new AccessDeniedHttpException();
        }
        $product = Product::create();
        $product->merchant_id = $merchant->id;
        if ($request->name != 'null') {
            $product->name = $request->name;
        }
        if ($request->regularPrice != 'null') {
            $product->price = $request->regularPrice;
        }

        //        $product->shipping_cost        = $request->shippingFees;
        //        $product->shipping_cost_abroad = $request->shippingFeesAbroadZone;
        if ($request->description != 'null') {
            $product->description = $request->description;
        }
        if ($request->manufacturer && $request->manufacturer != 'null') {
            $product->manufacturer = $request->manufacturer;
        }
        $product->negotiable = $request->negotiable == 'false' ? 0 : 1;
        //        $product->minPrice             = $request->minPrice;

        $product->currency = $request->currency;
        $product->stock = (int) $request->stock;
        if ($request->suggestion != 'null') {
            $product->suggestion = $request->suggestion;
        }
        if ($request->part != 'null') {
            $product->part_id = $request->part;
        }
        if ($request->intern != 'null' && $request->intern != 'undefined') {
            $product->intern = $request->intern;
        }
        if ($request->send != 'null') {
            $product->status = 1;
        } else {
            $product->status = 3;
        }

        if ($request->has('height') && $request->height != 'null') {
            $product->height = $request->height;
        }
        if ($request->has('width') && $request->width != 'null') {
            $product->width = $request->width;
        }
        if ($request->has('depth') && $request->depth != 'null') {
            $product->depth = $request->depth;
        }
        if ($request->has('averagePreparationTime') && $request->averagePreparationTime != 'null') {
            $product->average_preparation_time
                = $request->averagePreparationTime;
        }
        $product->condition = $request->condition;
        if ($request->weight != 'null') {
            $product->weight = $request->weight;
        }
        $product->delivery_option = (int) $request->delivery_option;

        $product->type = $request->type;
        if ($request->reference && $request->reference != 'null') {
            $product->reference = $request->reference;
        }
        $imgNames = [];
        if ($request->file) {
            foreach ($request->file as $i => $img) {
                array_push($imgNames, '/'.$this->convertImage($img));
            }
            foreach ($imgNames as $i => $image) {
                if (json_decode($request->orientation)[$i] != 0) {
                    $name = str_replace(
                        '/storage',
                        'storage',
                        $image
                    );
                    $deg = -1
                        * abs((float) json_decode($request->orientation)[$i]);
                    $newName = 'storage/products/'.Str::uuid().'.jpg';
                    Image::make($name)->rotate($deg)->save($newName);
                    Storage::delete($name);
                    $imgNames[$i] = '/'.$newName;
                }
            }
            $product->img = $imgNames;
        }
        foreach (json_decode($request->compatibleCars) as $car) {
            $product->modele()->save(Modele::find($car));
        }

        if ($request->name!='null'&&$request->name) {
            CreateTranslation::create('name', $request->name, $product);
        }
        if ($request->description!='null'&&$request->description) {
            CreateTranslation::create('description', $request->description, $product);
        }

        $product->save();
        $p = $merchant->products()->latest()->first();

        return response()->json(
            [
                'status' => 'success',
                'data'   => $p,
            ],
            201
        );
    }

    public function convertImage($img)
    {
        $name = str_replace(
            'public',
            'storage',
            $img->storeAs('public/products', Str::uuid().'.'.$img->extension())
        );
        $src = $this->imagecreatefromfile($name);
        $ratio = imagesx($src) / imagesy($src);
        $stamp = $this->imagecreatefromfile('images/stamp.png');
        if ($ratio > 1.77) {//si c'est horizontal
            $stamp = imagescale($stamp, imagesx($src));
        } else {//si c'est vertical
            $multi = imagesy($src) / imagesy($stamp);
            $stamp = imagescale($stamp, imagesx($stamp) * $multi, imagesy($src));
        }
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);
        //        $dst = imagecreatetruecolor(1920, 1080);
        //        imagefill($dst, 0, 0, imagecolorallocate($dst, 231, 231, 231));
        //        $this->scaleImage($src, $dst, 'fit');
        imagecopy(
            $src,
            $stamp,
            imagesx($src) - $sx,
            imagesy($src) - $sy,
            0,
            0,
            imagesx($stamp),
            imagesy($stamp)
        );
        imagejpeg($src, $name);
        ImageOptimizer::optimize($name);
        $this->convert($name, $name);

        return $name;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \App\Models\Product
     */
    public function show($id, Product $product)
    {
        if (!isAdmin() && auth()->user()->merchant_id !== $product->merchant_id) {
            throw new AccessDeniedHttpException();
        }
        return $product;
    }

    public function scaleImage($src_image, $dst_image, $op = 'fit')
    {
        $src_width = imagesx($src_image);
        $src_height = imagesy($src_image);

        $dst_width = imagesx($dst_image);
        $dst_height = imagesy($dst_image);

        // Try to match destination image by width
        $new_width = $dst_width;
        $new_height = round($new_width * ($src_height / $src_width));
        $new_x = 0;
        $new_y = round(($dst_height - $new_height) / 2);

        // FILL and FIT mode are mutually exclusive
        if ($op == 'fill') {
            $next = $new_height < $dst_height;
        } else {
            $next = $new_height > $dst_height;
        }

        // If match by width failed and destination image does not fit, try by height
        if ($next) {
            $new_height = $dst_height;
            $new_width = round($new_height * ($src_width / $src_height));
            $new_x = round(($dst_width - $new_width) / 2);
            $new_y = 0;
        }

        // Copy image on right place
        imagecopyresampled(
            $dst_image,
            $src_image,
            $new_x,
            $new_y,
            0,
            0,
            $new_width,
            $new_height,
            $src_width,
            $src_height
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProduct(
        Request $request
    ) {
        $product = Product::find($request->product_id);
        if (!isAdmin() && auth()->user()->merchant_id !== $product->merchant_id) {
            throw new AccessDeniedHttpException();
        }
        if ($request->merchant_id) {
            $product->merchant_id = $request->merchant_id;
        }
        if ($request->name != 'null') {
            $product->name = $request->name;
        }
        if ($request->regularPrice != 'null') {
            $product->price = $request->regularPrice;
        }

        //        $product->shipping_cost        = $request->shippingFees;
        //        $product->shipping_cost_abroad = $request->shippingFeesAbroadZone;
        if ($request->description != 'null') {
            $product->description = $request->description;
        }


        if ($request->manufacturer && $request->manufacturer != 'null') {
            $product->manufacturer = $request->manufacturer;
        }
        $product->negotiable = $request->negotiable == 'false' ? 0 : 1;
        //        $product->minPrice             = $request->minPrice;

        $product->currency = $request->currency;

        $product->stock = (int) $request->stock;
        $product->type = $request->type;

        if ($request->weight != 'null') {
            $product->weight = $request->weight;
        }
        $product->delivery_option = (int) $request->delivery_option;

        //        $modeles = [];
        //        $modeles = $product->modele()->pluck('id')->toArray();
        //        if ($request->send != 'null' &&
        //            ($product->part_id != $request->part ||(
        //                sizeof(array_diff(json_decode($request->compatibleCars), (array)$modeles))>0||
        //                sizeof(array_diff((array)$modeles, json_decode($request->compatibleCars)))>0
        //            )&&$product->status == 1)
        //        ) {
        //            //ici on check si la pièce ou le modele a changé, si oui on relance la moulinette
        //            $product->status = 3;
        //            $product->save();
        //            $product->status = 1;
        //        } else
        if ($request->send != 'null' && $product->status == 3) {
            $product->status = 1;
        } elseif ($request->send == 'null') {
            $product->status = 3;
        }
        if ($request->part != 'null') {
            $product->part_id = $request->part;
        }
        if ($request->part == 'null') {
            $product->part_id = null;
        }
        if ($request->intern != 'null') {
            $product->intern = $request->intern;
        }
        if ($request->suggestion != 'null') {
            $product->part_id = null;
            $product->suggestion = $request->suggestion;
        }
        if ($request->has('height') && $request->height != 'null') {
            $product->height = $request->height;
        }
        if ($request->has('width') && $request->width != 'null') {
            $product->width = $request->width;
        }
        if ($request->has('depth') && $request->depth != 'null') {
            $product->depth = $request->depth;
        }
        if ($request->has('averagePreparationTime') && $request->averagePreparationTime != 'null') {
            $product->average_preparation_time
                = $request->averagePreparationTime;
        }

        $product->condition = $request->condition;
        if ($request->reference && $request->reference != 'null') {
            $product->reference = $request->reference;
        }
        $filtered = [];
        if ($request->img != 'null') {
            $imgRequest = json_decode($request->img);
            $images = $product->img;
            if ($images == null) {
                $images = [];
            }
            $filtered = array_values(array_filter(
                $images,
                function ($key) use ($imgRequest) {
                    return in_array($key, $imgRequest);
                }
            ));
        }//on enlève les images supprimées de l'array
        $order = [];
        if ($request->file) {
            foreach ($request->file as $i => $img) {
                $name = '/'
                    .$this->convertImage($img);
                array_push(
                    $filtered,
                    $name
                ); //on rajoute les nouvelles images dans l'array
                $order[$i] = $name;
            }
        }
        $filteredOrdered = [];
        foreach ($filtered as $i => $image) {//on parcourt les url
            if (array_key_exists($i, $order)) {
                $filteredOrdered[$i] = $order[$i];
            }//si à l'index c'est une nouvelle image, on ajoute l'url
            else {
                $filteredOrdered[$i] = $imgRequest[$i];
            }//sinon on ajoute l'ancien url
        }
        foreach ($filteredOrdered as $i => $image) {
            if (json_decode($request->orientation)[$i] != 0) {
                $name = str_replace(
                    '/storage',
                    'storage',
                    $image
                );
                $deg = -1
                    * abs((float) json_decode($request->orientation)[$i]);
                $newName = 'storage/products/'.Str::uuid().'.jpg';
                Image::make($name)->rotate($deg)->save($newName);
                Storage::delete($name);
                $filteredOrdered[$i] = '/'.$newName;
            }
        }
        $product->img = $filteredOrdered;
        $product->modele()->sync(json_decode($request->compatibleCars));
        $product->save();

        if($request->original_language!='null'){
            if($request->original_language&&$request->original_language!==$product->original_language){
                CreateTranslation::updateBaseLang('name', $request->original_language, $product);
                CreateTranslation::updateBaseLang('description', $request->original_language, $product);
                $product->original_language = $request->original_language;//on refresh l'objet recherche avec le nouveau baseLang
            }
        }
        if ($request->name!='null'&&$request->name) {
            CreateTranslation::update('name', $request->name ?: '',
                $product, substr($product->original_language, 0, 2));
            DeepLTranslation::execute($product, 'name');
        }
        if ($request->description!='null'&&$request->description) {
            CreateTranslation::update('description', $request->description ?: '',
                $product, substr($product->original_language, 0, 2));
            DeepLTranslation::execute($product, 'description');
        }

        return response()->json(
            [
                'status' => 'success',
                'data'   => $product->refresh()->load(
                    'status',
                    'part',
                    'modele.brand',
                    'merchant.country'
                ),
            ],
            201
        );
    }

    public function imagecreatefromfile($filename)
    {
        if (! file_exists($filename)) {
            throw new InvalidArgumentException('File "'.$filename
                .'" not found.');
        }
        switch (strtolower(pathinfo($filename, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                return imagecreatefromjpeg($filename);
                break;

            case 'png':
                return imagecreatefrompng($filename);
                break;

            case 'gif':
                return imagecreatefromgif($filename);
                break;

            default:
                throw new InvalidArgumentException('File "'.$filename
                    .'" is not valid jpg, png or gif image.');
                break;
        }
    }

    public function convert($from, $to)
    {
        $command = 'convert '
            .$from
            .' '
            .'-sampling-factor 4:2:0 -strip -quality 65'
            .' '
            .$to;

        return `$command`;
    }

    public function update(
        Request $request,
        Merchant $merchant,
        Product $product
    ) {
        if (!isAdmin() || auth()->user()->merchant_id !== $merchant->id) {
            throw new AccessDeniedHttpException();
        }
        $product->update($request->all());
        //        $research->status = $request->status;
        //        $research->save();
        return response()->json(
            [
                $product->fresh(),
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Merchant  $merchant
     * @param  \App\Models\Product  $product
     *
     * @throws \Exception
     */
    public function destroy(Merchant $merchant, Product $product)
    {
        if (!isAdmin() && auth()->user()->merchant_id !== $merchant->id) {
            throw new AccessDeniedHttpException();
        }
        $merchant->products()->find($product->id)->research()->detach();

        $product->delete();
    }

    public function scriptImg()
    {
        $products = Product::whereNotNull('img')->get();
        foreach ($products as $p) {
            $imagesBdd = $p->img_with_index;
            if ($imagesBdd && gettype($imagesBdd[0]) !== 'array') {
                $p->img = $imagesBdd;
                $p->save();
            }
        }
    }
}
