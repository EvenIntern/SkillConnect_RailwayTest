<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class FollowController extends Controller
{
        /**
     * Follow or unfollow the given user.
     */
    public function toggle(User $user): RedirectResponse
    {
        $follower = auth()->user();

        // Prevent users from following themselves
        if ($follower->id === $user->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        // Use the toggle() method to attach or detach the relationship
        $follower->following()->toggle($user->id);

        return $this->safeRedirectBack(route('profile.show', $user))
            ->with('success', 'Follow status updated!');
    }

    private function safeRedirectBack(string $fallback): RedirectResponse
    {
        $previous = url()->previous();
        $previousPath = $previous ? parse_url($previous, PHP_URL_PATH) : null;

        if (! $previousPath || str_starts_with($previousPath, '/media/')) {
            return redirect()->to($fallback);
        }

        return redirect()->to($previous);
    }
}
