<?php

namespace App\Http\Controllers;

use App\Services\CheckAccess;

class SomeController extends Controller
{
    protected $checkAccess;

    public function __construct(CheckAccess $checkAccess)
    {
        $this->checkAccess = $checkAccess;
    }

    public function someMethod()
    {
        // Get authenticated user
        $user = Auth::user();

        // Retrieve the resource (e.g., specific record from database)
        $resource = Resource::find($resourceId); // Replace $resourceId with the ID of the resource

        // Check if the user has access to the resource
        if ($this->isSuperAdmin($user) && $this->checkAccess->hasAccess($user, $resource)) {
            // User is a superadmin and has access, perform actions
            // Example: Display resource details
            return view('resource.show', ['resource' => $resource]);
        } else {
            // User does not have access or is not a superadmin, handle accordingly
            abort(403, 'Unauthorized'); // Return a 403 Forbidden error
        }
    }

    /**
     * Check if the user is a superadmin.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    private function isSuperAdmin($user)
    {
        return $user->role === 'superadmin';
    }
}


