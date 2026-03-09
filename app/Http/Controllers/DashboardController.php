<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Application;

use Illuminate\Support\Carbon; 
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{

    public function __invoke(Request $request): View
    {
        $user = auth()->user()->load('skills');
        $sidebarData = $this->buildSidebarData($user);
        $projects = Project::with('user')->withCount(['comments', 'likers'])->latest()->paginate(5);

        $followingIds = $user->following()->pluck('users.id');
        $excludeIds = $followingIds->push($user->id);

        $recommendedUsers = User::whereNotIn('id', $excludeIds)
            ->inRandomOrder()
            ->take(5)
            ->get();

        $likedProjectIds = auth()->check() ? auth()->user()->likedProjects()->pluck('projects.id')->toArray() : [];

        return view('home', [
            'user' => $user,
            'projects' => $projects,
            'recommendedUsers' => $recommendedUsers,
            'acceptedProjects' => $sidebarData['acceptedProjects'],
            'pendingProjects' => $sidebarData['pendingProjects'],
            'incomingApplicants' => $sidebarData['incomingApplicants'],
            'upcomingEvents' => $sidebarData['upcomingEvents'],
            'likedProjectIds' => $likedProjectIds,
        ]);
    }

    public function live(Request $request): JsonResponse
    {
        $user = $request->user();
        $sidebarData = $this->buildSidebarData($user);

        return response()->json([
            'incoming_applicants_html' => view('home.partials.incoming-applicants', [
                'incomingApplicants' => $sidebarData['incomingApplicants'],
            ])->render(),
            'accepted_projects_html' => view('home.partials.accepted-projects', [
                'acceptedProjects' => $sidebarData['acceptedProjects'],
            ])->render(),
            'pending_projects_html' => view('home.partials.pending-projects', [
                'pendingProjects' => $sidebarData['pendingProjects'],
            ])->render(),
        ]);
    }

    private function buildSidebarData(User $user): array
    {
        $acceptedProjects = $user->acceptedProjects()
            ->wherePivot('status', 'accepted')
            ->with('user')
            ->latest('pivot_updated_at')
            ->get();

        $pendingProjects = $user->pendingProjects()
            ->with('user')
            ->get();

        $myProjectIds = $user->projects()->pluck('id');

        $incomingApplicants = Application::whereIn('project_id', $myProjectIds)
            ->where('status', 'pending')
            ->with('user', 'project')
            ->latest()
            ->get()
            ->groupBy('project.title');

        $upcomingEvents = $user->acceptedProjects()
            ->wherePivot('status', 'accepted')
            ->whereNotNull('schedule_details')
            ->latest('pivot_updated_at')
            ->take(3)
            ->get();

        return [
            'acceptedProjects' => $acceptedProjects,
            'pendingProjects' => $pendingProjects,
            'incomingApplicants' => $incomingApplicants,
            'upcomingEvents' => $upcomingEvents,
        ];
    }
}
