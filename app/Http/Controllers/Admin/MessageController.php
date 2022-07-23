<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Models\Message[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(User $user)
    {
        $messages = Message::orderBy('created_at', 'desc')
            ->where('checked', 0)
            ->get()
            ->load('template', 'sender', 'recipient');
        foreach ($messages as $msg) {
            if ($msg->template_id != 5) {
                $msg->research_id = $this->getIdMessage($msg->type_id);
            }
        }

        return $messages;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\User  $user
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \App\Models\Message|\Illuminate\Database\Eloquent\Model
     */
    public function store(User $user, Request $request)
    {
        return Message::create(array_merge([
            'sender' => $user->id,
        ], $request->all()));
    }

    public function getIdMessage($id)
    {
        $pivot = DB::table('research_product')->find($id);
        if ($pivot) {
            return $pivot->research_id;
        } else {
            return 0;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     *
     * @return Message|Message[]|bool|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function update(Request $request, $idUser, $idMessage)
    {
        Message::find($idMessage)->update($request->all());

        return Message::find($idMessage)
            ->load('template', 'sender', 'recipient');
    }

    public function getConv(Request $request, $idMessage)
    {
        //        $type = $request->has('type') ? $request->type : 'research_product';
        return Message::where('type_id', $idMessage)
            ->whereIn(
                'type',
                ['research_product', 'alert_subscription_researches']
            )
            ->where('template_id', 4)
            ->orderBy('created_at', 'ASC')->get()
            ->load('template', 'sender', 'recipient');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        $message = Message::findOrFail($request->message);
        if (isAdmin()) {
            $message->delete();
        }
    }
}
