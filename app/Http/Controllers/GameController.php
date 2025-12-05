<?php

namespace App\Http\Controllers;

use App\Events\GameJoined;
use App\Events\MoveMade;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GameController extends Controller
{
    //
    public function create()
    {
        $code = strtoupper(Str::random(5));
        // garantizamos unidicidad ligera
        while (Game::where('code', $code)->exists()) {
            $code = strtoupper(Str::random(5));
        }

        $game = Game::create([
            'code' => $code,
            // 'code' => Str::upper(Str::random(5)),
            // 'board' => array_fill(0, 9, ""),
            // 'turn' => 'X'
        ]);

        return response()->json($game);
    }

    //
    public function join(Request $request, $code)
    {
        $game = Game::where('code', $code)->firstOrFail();
        $userId = $request->user()?->id ?? $request->header('X-Guest-Id');

        if (!$game->player_x) $game->player_x = $userId;
        elseif (!$game->player_o && $game->player_x != $userId) $game->player_o = $userId;
        elseif (!in_array($userId, [$game->player_x, $game->player_o])) return response()->json(['error' => 'Partida llena.'], 400);

        $game->save();

        // broadcast(new GameJoined($game))->toOthers();

        return response()->json($game);
    }

    //
    public function move(Request $request, $id)
    {
        $game = Game::findOrFail($id);
        $index = (int) $request->index;
        $userId = $request->user()?->id ?? $request->header('X-Guest-Id');

        // Validaciones 
        if ($game->winner || $game->id_draw) return response()->json(['message' => 'Partida finalizada'], 400);
        if($index < 0 || $index > 8) return response()->json(['messgae' => 'Index no valido.'], 400);
        // if ($game->board[$index] !== "") return;
        if ($game->turn === 'X' && $game->player_x !== $userId) return response()->json(['message' => "Abandono el usuario {$userId}"], 400);
        if ($game->turn === 'O' && $game->player_0 !== $userId) return response()->json(['message' => "Abandono el usuario {$userId}"], 400);

        // Actualizar tablero 
        $board = $game->board;
        if (!is_array($board)) $board = array_fill(0, 9, '');

        if ($board[$index] !== '') {
            return response()->json(['message' => 'Casilla ocupada'], 400);
        }

        $playerSymbol = null;
        if ($game->player_x == $userId) $playerSymbol = 'X';
        if ($game->player_o == $userId) $playerSymbol = 'O';
        if (!$playerSymbol) return response()->json(['message' => 'No estas en partida'], 403);

        if ($game->turn !== $playerSymbol) {
            return response()->json(['message' => 'No es tu turno'], 400);
        }

        // efectuar jugada
        $board[$index] = $playerSymbol;
        $game->board = $board;

        // comprobar ganador
        $winner = $this->checkWinner($board);
        if ($winner) {
            $game->winner = $winner;
        } else {
            // comprobar empate
            $isDraw = count(array_filter($board, fn($c) => $c === '')) === 0;
            $game->is_draw = $isDraw;
            if ($isDraw){
                $game->winner = null;
            }
        }

        // cambiar turno si no finalizó
        if (!$game->winner && !$game->is_draw){
            $game->turn = $game->turn === 'X' ? 'O' : 'X';
        }

        $game->save();

        broadcast(new MoveMade($game))->toOthers();

        return response()->json($game);        
    }

    //
    public function restart(Request $request, $id)
    {   
        $game = Game::fidnOrFail($id);
        $game->board = array_fill(0,9,'');
        $game->turn = 'X';
        $game->winner = null;
        $game->is_draw = false;
        $game->save();

        broadcast(new MoveMade($game))->toOthers();

        return response()->json($game);
    }

    private function checkWinner(array $b)
    {
        $wins = [
            [0,1,2],[3,4,5],[6,7,8],
            [0,3,6],[1,4,7],[2,5,8],
            [0,4,8],[2,4,6]
        ];
        foreach ($wins as $w){
            if ($b[$w[0]] !== '' && $b[$w[0]] === $b[$w[1]] && $b[$w[1]] === $b[$w[2]]) {
                return $b[$w[0]];
            }
        }
        return null;
    }
}
