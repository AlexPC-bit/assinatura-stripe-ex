<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSubscribed
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()->activeSubscription()) {
            return redirect()->route('checkout.index')
                ->with('warning', 'Você precisa de uma assinatura ativa para acessar essa área.');
        }

        return $next($request);
    }
}