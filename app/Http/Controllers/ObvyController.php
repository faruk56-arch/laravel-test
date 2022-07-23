<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Research;
use App\Services\CopyProductOnResearch;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ObvyController extends Controller
{
    /**
     * @var \App\Services\CopyProductOnResearch
     */
    private $service;

    public function __construct(CopyProductOnResearch $service)
    {
        $this->service = $service;
    }

    public function processHook(Request $request)
    {
        try {
            $transactionInformations = $this->getTransactionEvent($request->EventId);
            if (count($queryParams = $this->parseIdEncoding($transactionInformations['data']['itemNumber'])) === 2) {
                $pivot = DB::table('research_product')->where(
                    $queryParams
                )
                    ->first();
                if (!$pivot) {
                    throw new \RuntimeException('Pivot not found');
                }
                $research = Research::find($pivot->research_id);
                $product = Product::find($pivot->product_id);
                /** @var \App\Models\SoldProduct $soldProduct */
                $soldProduct = $this->service->sold($product, $research);
                $research->status
                             = 'finished'; //on marque les recherches qui ont succédé à une vente comme terminées
                $research->save();
            } else {
                $product = Product::where(['status' => 1, 'sent' => 1])->findOrFail($queryParams['product_id']);
                $soldProduct = $this->service->castToSoldProduct($product, null);
            }

            if ($product->stock > 1) {//on descend de 1 le stock
                $product->stock--;
            } else {
                $this->service->save($product, $soldProduct);
                $product->status = 3; //sinon on la marque comme brouillon
                $product->sent = 0;
            }
            $product->save();
        } catch (\Exception | ClientException $e) {
            Log::error($e->getMessage());
        }
    }

    public function parseIdEncoding(string $value): array
    {
        $valArray = explode(',', $value);
        if (count($valArray) === 2) {
            return [
                'research_id' => $valArray[1],
                'product_id' => $valArray[0],
            ];
        }
        return [
            'product_id' => $valArray[0],
        ];
    }

    public function getTransactionEvent(int $eventId)
    {
            $headers = [
                'Content-Type' => 'application/json',
                'X-Secret-Key' => env('OBVY_PRIVATE_KEY'),
            ];
            $url = 'https://api'.
                (app()->environment('production') ? '' : 'sandbox')
                .'public.obvy-app.com/api/hook/event/'.$eventId;

            $response = (new Client([
                'headers' => $headers,
            ]))->request(
                'GET',
                $url
            );
            return json_decode($response->getBody(), true, 512,
                JSON_THROW_ON_ERROR);
    }
}
