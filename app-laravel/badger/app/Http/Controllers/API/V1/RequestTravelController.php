<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\RequestTravelCollection;
use App\Models\RequestTravel;
use Illuminate\Http\Request;
use App\Http\Resources\V1\RequestTravelResource;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RequestTravelController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'destino' => 'required|string|max:255',
            'data_ida' => 'required|date',
            'data_volta' => 'required|date|after_or_equal:data_ida',
            'email_solicitante' => 'required|string|max:255'
        ]);

        $user = User::where('email', $request['email_solicitante'])->first();

        $travel = RequestTravel::create([
            'user_id'=> $user->id,
            'nome_solicitante' => $user->name,
            'destino' => $validated['destino'],
            'data_ida' => $validated['data_ida'],
            'data_volta' => $validated['data_volta'],
            'status' => 'solicitado',
        ]);

        return new RequestTravelResource($travel);
    }

    public function update(Request $request, RequestTravel $requestTravel)
    {
        if ($requestTravel->user_id === Auth::id()) {
            return response()->json(['error' => 'Você não pode alterar seu próprio pedido.'], 403);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['aprovado', 'cancelado'])]
        ]);

        if ($requestTravel->status === 'aprovado' && $validated['status'] === 'cancelado') {
            return response()->json(['error' => 'Pedidos aprovados não podem ser cancelados.'], 400);
        }

        $requestTravel->update([
            'status' => $validated['status']
        ]);

        return new RequestTravelResource($requestTravel);
    }

    public function show(RequestTravel $requestTravel)
    {
        return new RequestTravelResource($requestTravel);
    }

    public function index(Request $request)
    {
        $query = RequestTravel::query();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('destino')) {
            $query->where('destino', 'like', '%' . $request->input('destino') . '%');
        }

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('data_ida', [$request->input('data_inicio'), $request->input('data_fim')]);
        }

        return new RequestTravelCollection($query->get());
    }

    public function aprovarViagem(RequestTravel $requestTravel)
    {
        if ($requestTravel->status !== 'solicitado') {
            return response()->json(['error' => 'A viagem só pode ser aprovada se estiver no status "solicitado".'], 400);
        }

        $requestTravel->update(['status' => 'aprovado']);

        return new RequestTravelResource($requestTravel);
    }

    public function reprovarViagem(RequestTravel $requestTravel)
    {
        $requestTravel->update(['status' => 'cancelado']);

        return new RequestTravelResource($requestTravel);
    }

    public function destroy(RequestTravel $requestTravel)
    {
        //
    }
}
