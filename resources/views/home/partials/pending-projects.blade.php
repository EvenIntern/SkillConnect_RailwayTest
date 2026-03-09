<div class="bg-white rounded-lg shadow p-4">
    <div class="mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Pending Projects</h3>
        <div class="space-y-4">
            @forelse ($pendingProjects as $project)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg shadow-sm p-4">
                    <h4 class="font-bold text-yellow-800">{{ $project->title }}</h4>
                    <p class="text-xs text-gray-600 mt-1">
                        Posted by
                        <a href="{{ route('profile.show', $project->user) }}" class="font-medium text-gray-900 hover:underline">
                            {{ $project->organization_name ?? $project->user->name }}
                        </a>
                    </p>
                    <p class="text-sm text-gray-700 mt-1">{{ Str::limit($project->description, 300) }}</p>
                    <p class="text-xs text-gray-500 mt-2">You applied {{ $project->pivot->updated_at->diffForHumans() }}</p>
                </div>
            @empty
                <div class="text-center py-4">
                    <p class="text-sm text-gray-500">Your pending project applications will show up here!</p>
                    <a href="{{ route('discover') }}" class="mt-2 inline-block text-sm text-blue-600 hover:underline font-semibold">
                        Find opportunities to apply for!
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
