<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use DB, Hash;
use Date;

class StudentAuthBearerToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Extract the Bearer token from the Authorization header
        $bearerToken = $request->bearerToken();

        // Check if a token is present
        if (!$bearerToken) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $students = DB::select('SELECT * FROM personal_access_tokens WHERE tokenable_type = ?', ['StudentUser']);
        
        $match = false;
        foreach($students as $student) {
            if(Hash::check($bearerToken, $student->token)){

                $expiry = Date::parse($student->expires_at);
                
                if($expiry > now('Asia/Manila')) {
                    $match = true;
                    break;
                }
            }
        }

        if($match == false) {
            return response()->json(['error' => 'Unauthorized'], 401);
        } else {
            return $next($request);
        }
    }
}
