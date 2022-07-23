<?php

namespace App\Http\Controllers\User;

use App\Actions\Translations\CreateTranslation;
use App\Events\ResearchValidated;
use App\Filters\ResearchFilter;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Research;
use App\Models\Status;
use App\Models\User;
use App\Models\UserModel;
use App\Repositories\ResearchRepository;
use Auth;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class ResearchController extends Controller
{
    protected $researchRepository;

    /**
     * @var Research
     */
    private $research;

    /**
     * ResearchController constructor.
     *
     * @param  Research  $research
     * @param  ResearchRepository  $researchRepository
     */
    public function __construct(
        Research $research,
        ResearchRepository $researchRepository
    ) {
        $this->researchRepository = $researchRepository;
        $this->research = $research;
    }

    /**
     * Display a listing of the current user resource.
     *
     * @param  ResearchFilter  $filters
     * @param  Request  $request
     *
     * @return JsonResponse|\Illuminate\Support\Collection|int
     */
    public function index(ResearchFilter $filters, Request $request)
    {
        $response = $this->research->where('user_id', Auth::id());
        if ($request->has('count')) {
            return $response->count();
        }

        return $response->with(['modele.modele.brand', 'part', 'products'])
            ->without([
                'user',
            ])
            ->filter($filters)->latest()->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  Research  $research
     *
     * @return \App\Models\Research|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     */
    public function show(User $user, Research $research)
    {
        return Research::where('id', $research->id)->with(['meta',
            'products' => function ($query) use ($research) {
                if ($research->type !== null) {
                    return $query->orderByRaw(DB::raw("type = $research->type desc"));
                }
            },
        ])->firstOrFail()->load([
            'modele.modele.brand', 'part', 'productMissed', 'productSold',
        ]);
    }

    public function showAdmin(User $user, int $id)
    {
        $research = Research::withTrashed()->findOrFail($id);
        return Research::withTrashed()->where('id', $research->id)->with(['meta',
            'products' => function ($query) use ($research) {
                if ($research->type !== null) {
                    return $query->orderByRaw(DB::raw("type = $research->type desc"));
                }
            },
        ])->firstOrFail()->load([
            'modele.modele.brand', 'part', 'productMissed', 'productSold',
        ]);
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

    public function convertImage($img)
    {
        $name = str_replace(
            'public',
            'storage',
            $img->store('public/researches')
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
        //        $dst   = imagecreatetruecolor(1920, 1080);
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

    /**
     * @param  Request  $request
     *
     * @return JsonResponse
     */
    public function store(Request $request, User $user)
    {
        $request->validate(Research::$createRules);
        if ($request->modele_year == 'null') {
            $request->modele_year = null;
        }
        if (! $request->custom) {
            $userModel = UserModel::create(
                [
                    'user_id' => $user->id, 'modele_id' => $request->modele_id,
                    'year'    => $request->modele_year,
                    'version' => $request->version,

                ]
            );
        } else {
            $userModel = UserModel::where('id', $request->custom)
                ->where('user_id', $user->id)->firstOrFail();
            if ($request->modele_year != $userModel->year) {
                $userModel->year = $request->modele_year;
                $userModel->save();
            }
            if ($request->version != $userModel->version) {
                $userModel->version = $request->version;
                $userModel->save();
            }
        }
        $images = [];
        $res = null;
        if ($request->file('files')) {
            foreach ($request->file('files') as $img) {
                array_push($images, '/'.$this->convertImage($img));
            }
        }
        if ($request->part_id == 'null') {
            $request->part_id = null;
        }
        if ($request->version == 'null') {
            $request->version = null;
        }
        if ($request->reference == 'null') {
            $request->reference = null;
        }
        if ($request->details == 'null') {
            $request->details = null;
        }
        if ($request->compatible_suggestion == 'null') {
            $request->compatible_suggestion = null;
        }

        $research = Research::create([
            'user_id'   => $user->id,
            'part_id'   => $request->part_id,
            'modele_id' => $userModel->id,
            'details'   => $request->details,
            'reference' => $request->reference,
            'compatible_suggestion' => $request->compatible_suggestion,
            'img'       => $images,
        ]);
        if ($request->details) {
            CreateTranslation::create('details', $request->details, $research);
        }

        foreach (json_decode($request->types) as $type) {
            $research->types()->save(Status::find($type));
        }
        if ($research->part === null) {
            $category
                = Category::find(json_decode($request->unknown_part)->category)->translation;
            $research->forceFill([
                'unknown_part->category' => $category,
                'unknown_part->message'  => json_decode($request->unknown_part)->message,
            ])->save();
        }

        return response()->json(
            $research->load(['modele.modele.brand', 'part', 'products'])
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Research  $research
     *
     * @return JsonResponse
     */
    public function update(Request $request, User $user, Research $research)
    {
        $this->authorize('update', $research);
        $research->update($request->all());
        //        $research->status = $request->status;
        //        $research->save();

        if ($request->details) {
            CreateTranslation::update('details', $request->details, $research,substr($research->original_language, 0, 2));
        }
        if ($request->status === 'in_progress') {
            ResearchValidated::dispatch($research);
        }

        return response()->json(
            [
                $research,
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Research  $research
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(User $user, Research $research)
    {
        $research->delete();

        return response()->json(null, 204);
    }

    public function getProductsOfResearch(Request $request)
    {
        if ($this->researchRepository->getStatus($request->id)->getData()->data
            == 'finished'
        ) {
            return response()->json(
                [
                    'data' => $this->researchRepository->getProductsWithMerchants($request->id),
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'error' => 'Recherche non terminée, pas de produits associés',
                ],
                500
            );
        }
    }

    public function getResearchesOfUser($id = null)
    {
        if ($id) {
            $userId = $id;
        } else {
            $userId = Auth::id();
        }
        $response = $this->researchRepository->getFromUserWithTerms($userId);

        return response()->json(
            [
                'data' => $response,
            ]
        );
    }
}
