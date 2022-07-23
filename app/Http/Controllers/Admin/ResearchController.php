<?php

namespace App\Http\Controllers\Admin;

use App\Filters\ResearchFilter;
use App\Http\Controllers\Controller;
use App\Models\Research;
use App\Models\User;
use App\Notifications\RejectedResearch;
use App\Repositories\ResearchRepository;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;

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
        $this->authorizeResource(Research::class);
    }

    /**
     * Display a listing of the current user resource.
     *
     * @param  ResearchFilter  $filters
     *
     * @return JsonResponse
     */
    public function index(ResearchFilter $filters)
    {
        $response = $this->research::withTrashed();
        //        foreach ($response as $research) {
        //            $research->img = unserialize($research->img)  ;
        //        }
        return $response->with(['modele.modele', 'part', 'user'])
            ->orderBy('created_at', 'DESC')
            ->filter($filters)->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  Research  $research
     *
     * @return JsonResponse
     */
    public function show($id, Research $research)
    {
        return response()->json(
            [
                $research->with(['model', 'part'])
                    ->orderBy('created_at', 'DESC'),
            ]
        );
    }

    /**
     * @param  Request  $request
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Research  $research
     *
     * @return JsonResponse
     */
    public function update(Request $request, Research $research)
    {
        $research->update($request->all());

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

    public function reject(Request $request, User $admin, Research $research)
    {
        $request->validate(['message' => 'required|filled']);
        try {
            $research->user->notify(new RejectedResearch(
                $request->message,
                $research
            ));
            $research->delete();
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function toggleStill(Request $request, Research $research)
    {
        $research->still = (int)!$research->still;
        $research->save();

        return response()->json(
            [
                'still' => $research->still,
            ]
        );
    }
}
