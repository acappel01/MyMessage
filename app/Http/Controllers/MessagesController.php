<?php

namespace App\Http\Controllers;

use App\Message;
use App\Http\Resources\Message as MessageResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Message::class);

        return MessageResource::collection( request()->user()->messages );
    }

    public function store()
    {
        $this->authorize('create', Message::class);

        $message = request()->user()->messages()->create( $this->validateData() );

        return (new MessageResource($message))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Message $message)
    {
        $this->authorize('view', $message);
        
        return new MessageResource($message);
    }

    public function update(Message $message)
    {
        $this->authorize('update', $message);

        $message->update( $this->validateData() );

        return (new MessageResource($message))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(Message $message)
    {
        $this->authorize('delete', $message);

        $message->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }

    public function validateData()
    {
        return request()->validate([
            'subject' => 'required',
            'content' => 'required',
            'startDate' => 'required',
            'expirationDate' => 'required',
        ]);
    }
}
