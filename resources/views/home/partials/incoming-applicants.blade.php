<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Incoming Applicants</h3>
    <div class="space-y-6">
        @forelse ($incomingApplicants as $projectTitle => $applications)
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h4 class="font-bold text-gray-900 mb-3">{{ $projectTitle }}</h4>

                <div class="space-y-4">
                    @foreach ($applications as $application)
                        <div class="py-3 border-b border-gray-200 last:border-b-0">
                            <div class="flex items-baseline justify-between">
                                <a href="{{ route('profile.show', $application->user) }}" class="font-semibold text-blue-700 hover:underline">
                                    {{ $application->user->name }}
                                </a>
                                <p class="text-xs text-gray-500">
                                    {{ $application->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <div class="mt-2">
                                <form action="{{ route('applications.update', $application->id) }}" method="POST" class="flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <button name="status" value="accepted" class="font-semibold text-xs px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition">Accept</button>
                                    <button name="status" value="declined" class="font-semibold text-xs px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition">Decline</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-4">
                <p class="text-sm text-gray-500">When someone applies to your projects, you'll see them here.</p>
            </div>
        @endforelse
    </div>
</div>
