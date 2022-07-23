<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AlertSubscription;
use App\Models\Message;
use App\Models\Product;
use App\Models\Research;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(User $user)
    {
        $conversations = Message::where(function ($query) use ($user) {
            $query->where('sender', $user->id)
                ->orWhere('recipient', $user->id);
        })->where('type', 'research_product')->where(function ($query) use ($user) {
            $query->where('checked', 1)->orWhere(function ($query) use ($user) {
                $query->where('checked', 0)->where('sender', $user->id);
            });
        })->where('template_id', 4)->orderBy('created_at', 'ASC')
            ->get()->load('template', 'sender', 'recipient')->groupBy('type_id');
        $products = [];
        $conversations = $conversations->sortByDesc(function ($conv, $key) {
            return $conv->last()->created_at;
        });
        foreach ($conversations as $conv) {
            if (DB::table('research_product')->find($conv[0]->type_id)) {
                $productId = DB::table('research_product')->find($conv[0]->type_id)->product_id;
                $researchId = DB::table('research_product')->find($conv[0]->type_id)->research_id;
                $product = Product::find($productId);
                if (Research::find($researchId)) {
                    $product->researchUser = Research::find($researchId)->user;
                    $product->messages = $conv;
                    array_push($products, $product);
                }
            }
        }

        $conversationsAlerts = Message::where(function ($query) use ($user) {
            $query->where('sender', $user->id)
                ->orWhere('recipient', $user->id);
        })->where('type', 'alert_subscription_researches')
            ->where(function ($query) use ($user) {
                $query->where('checked', 1)->orWhere(function ($query) use ($user) {
                    $query->where('checked', 0)->where('sender', $user->id);
                });
            })->where('template_id', 4)->orderBy('created_at', 'ASC')
            ->get()->load('template', 'sender', 'recipient')->groupBy('type_id');
        $researches = [];
        $conversationsAlerts = $conversationsAlerts->sortByDesc(function ($conv, $key) {
            return $conv->last()->created_at;
        });
        foreach ($conversationsAlerts as $conv) {
            if ($conv[0]->type === 'alert_subscription_researches') {
                $alertPivot = DB::table('alert_subscription_researches')
                    ->find($conv[0]->type_id);
                if ($alertPivot) {
                    $research = Research::find($alertPivot->research_id);
                    if ($research) {
                        $research->merchant_id = AlertSubscription::withTrashed()->find($alertPivot->alert_subscription_id)->merchant_id;
                        $research->messages = $conv;
                        array_push($researches, $research->load('modele.modele.brand'));
                    }
                }
            }
        }

        return response()->json(['products' => $products, 'researches' => $researches]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Models\User $user
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Models\Message|\Illuminate\Database\Eloquent\Model
     */
    public function store(User $user, Request $request)
    {
        $message = Message::create(array_merge([
            'sender' => $user->id,
        ], $request->all()));
        if ($message->type == 'research_product') {
            $message->product_id = DB::table('research_product')->find($message->type_id)->product_id;
            if ($message->template_id == 4) {
                return $message->load('template', 'sender', 'recipient');
            }
        }
        if ($message->type === 'alert_subscription_researches') {
            $message->research_id = DB::table('alert_subscription_researches')
                ->find($message->type_id)
                ->research_id;
            if ($message->template_id == 4) {
                return $message->load('sender', 'recipient');
            }
        }

        return $message;
    }

    public function messageCount($userId)
    {
        $messages = Message::where('recipient', $userId)
            ->whereIn('type', ['research_product', 'alert_subscription_researches'])
            ->where('read', 0)->where('checked', 1)->get();
        return $messages->filter(function ($message) {
            return DB::table($message->type)
                ->where('id', $message->type_id)->exists();
        })->count();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Message $message
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Message $message
     *
     * @return Message
     */
    public function update(User $user, Message $message, Request $request)
    {
        $message->update($request->all());

        if ($message->type == 'research_product') {
            $message->product_id = DB::table('research_product')->find($message->type_id)->product_id;
            if ($message->template_id == 4) {
                return $message->load('template', 'sender', 'recipient');
            }
        }
        if ($message->type === 'alert_subscription_researches') {
            $message->research_id = DB::table('alert_subscription_researches')
                ->find($message->type_id)
                ->research_id;
            if ($message->template_id === 4) {
                return $message->load('sender', 'recipient');
            }
        }

        return $message;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Message $message
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
