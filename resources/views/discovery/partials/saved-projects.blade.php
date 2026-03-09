@forelse ($savedProjectsForSidebar as $savedProject)
    @php
        $icon = 'fa-calendar-day';
        $bgColor = 'bg-gray-100';
        $iconColor = 'text-gray-500';

        switch ($savedProject->status) {
            case 'Urgent':
                $icon = 'fa-circle-exclamation';
                $bgColor = 'bg-red-100';
                $iconColor = 'text-red-500';
                break;
            case 'Environmental':
                $icon = 'fa-tree';
                $bgColor = 'bg-green-100';
                $iconColor = 'text-green-500';
                break;
            case 'Education':
                $icon = 'fa-graduation-cap';
                $bgColor = 'bg-blue-100';
                $iconColor = 'text-blue-500';
                break;
            case 'Community':
                $icon = 'fa-users';
                $bgColor = 'bg-yellow-100';
                $iconColor = 'text-yellow-500';
                break;
            case 'Animals':
                $icon = 'fa-paw';
                $bgColor = 'bg-orange-100';
                $iconColor = 'text-orange-500';
                break;
            case 'Health':
                $icon = 'fa-heart-pulse';
                $bgColor = 'bg-rose-100';
                $iconColor = 'text-rose-500';
                break;
            case 'Programming':
                $icon = 'fa-code';
                $bgColor = 'bg-indigo-100';
                $iconColor = 'text-indigo-500';
                break;
        }
    @endphp

    <div class="flex items-start py-4 @if(!$loop->last) border-b border-gray-200 @endif">
        <div class="flex-shrink-0 h-12 w-12 rounded-lg {{ $bgColor }} flex items-center justify-center">
            <i class="fas {{ $icon }} {{ $iconColor }} text-xl"></i>
        </div>
        <div class="ml-4">
            <a href="#" class="text-sm font-semibold text-blue-600 hover:underline">{{ $savedProject->title }}</a>
            <p class="text-xs text-gray-600 mt-1">
                Posted by
                <a href="{{ route('profile.show', $savedProject->user) }}" class="font-medium text-gray-800 hover:underline">
                    {{ $savedProject->organization_name ?? $savedProject->user->name }}
                </a>
            </p>
            <p class="text-xs text-gray-500">Saved {{ $savedProject->pivot->created_at->diffForHumans() }}</p>
        </div>
    </div>
@empty
    <p class="text-sm text-gray-500">You haven't saved any projects yet.</p>
@endforelse
