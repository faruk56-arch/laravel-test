<?php

namespace App\Http\Controllers;

use App\Actions\Translations\CreateTranslation;
use App\Actions\Translations\DeepLTranslation;
use App\Filters\ResearchFilter;
use App\Models\Research;
use App\Repositories\ResearchRepository;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
     * @param Research $research
     * @param ResearchRepository $researchRepository
     */
    public function __construct(
        Research           $research,
        ResearchRepository $researchRepository
    )
    {
        $this->researchRepository = $researchRepository;
        $this->research = $research;
        $this->authorizeResource(Research::class);
    }

    /**
     * Display a listing of the current user resource.
     *
     * @param ResearchFilter $filters
     *
     * @return JsonResponse
     */
    public function index(ResearchFilter $filters)
    {
        $response = Auth::user()->role === 'user' ?
            $this->research->where('user_id', Auth::id()) : $this->research;

        return $response->with(['modele', 'part'])->filter($filters)
            ->paginate();
    }

    /**
     * Display the specified resource.
     *
     * @param Research $research
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function show(Request $request, Research $research)
    {
        if ($request->wantsJson()) {

            return response()->json(
                [
                    $research->with(['model', 'part']),
                ]
            );
        }
        return view('research', ['research' => $research]);
    }

    public function showPublic(Request $request, int $id)
    {
        try {
            $research = Research::findOrFail($id);
            return view('research', ['research' => $research]);
        } catch (\Exception $e) {
            return view('research');
        }
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate(Research::$createRules);
        $research = $this->researchRepository->create($request->all());
        $user = $request->user_id;
        $research->user()->associate($user);
        $research->status = 'in_progress';
        $research->part_id = $request->part_id;
        $research->modele_id = $request->modele_id;
        $research->save();

        return response()->json(
            [
                $research->load(['modele', 'part']),
            ],
            201
        );
    }

    public function update(Request $request, Research $research)
    {
        $research->update($request->all());

        return $research;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Research $research
     *
     * @return JsonResponse
     */
    public function updateAdmin(Request $request, Research $research)
    {
        $locale = substr(\App::getLocale(), 0, 2);
        if($request->original_language!='null'){
            if($request->original_language){
                CreateTranslation::updateBaseLang('details', $request->original_language, $research);
                $research->refresh();//on refresh l'objet recherche avec le nouveau baseLang
            }
        }
        if ($request->details != 'null') {
            if (!$research->details){
                //si pas de trad, on la créé
                CreateTranslation::create('details', $request->details, $research);
            }else{
                //sinon on l'update
                CreateTranslation::update('details', $request->details, $research,substr($research->original_language, 0, 2));
            }
            $research->details = $request->details;
        }
        if ($request->details != 'null'||$request->original_language!='null') {
            // si la description ou la langue de base a changé on relance les trad
            DeepLTranslation::execute($research, 'details');
        }
        if ($request->reference != 'null') {
            $research->reference = $request->reference;
        }
        if ($request->compatible_suggestion != 'null') {
            $research->compatible_suggestion = $request->compatible_suggestion;
        }
        if (isset($request->unknown_part) && $request->unknown_part != 'null') {
            $research->forceFill([
                'unknown_part->category' => json_decode($request->unknown_part)->category,
                'unknown_part->message' => json_decode($request->unknown_part)->message,
            ])->save();
        } else {
            if ($request->part_id != 'null') {
                $research->part_id = $request->part_id;
            }
        }
        $research->types()->sync(json_decode($request->types));
        if ($request->images != 'null') {
            $imgRequest = json_decode($request->images);
            $images = $research->img;
            if ($images == null) {
                $images = [];
            }
            $filtered = array_filter(
                $images,
                function ($key) use ($imgRequest) {
                    return in_array($key, $imgRequest);
                }
            );
        }//on enlève les images supprimées de l'array

        if ($request->file('files')) {
            foreach ($request->file('files') as $img) {
                array_push($filtered, '/' . $this->convertImage($img));
            }
        }
        $research->img = $filtered;
        $research->touch();
        $research->save();
        return response()->json(
            [
                $research,
            ]
        );
    }

    public function convertImage($img)
    {
        $name = str_replace(
            'public',
            'storage',
            $img->storeAs('public/researches', Str::uuid() . '.' . $img->extension())
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

    public function imagecreatefromfile($filename)
    {
        if (!file_exists($filename)) {
            throw new InvalidArgumentException('File "' . $filename
                . '" not found.');
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
                throw new InvalidArgumentException('File "' . $filename
                    . '" is not valid jpg, png or gif image.');
                break;
        }
    }

    public function convert($from, $to)
    {
        $command = 'convert '
            . $from
            . ' '
            . '-sampling-factor 4:2:0 -strip -quality 65'
            . ' '
            . $to;

        return `$command`;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Research $research
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Research $research)
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
