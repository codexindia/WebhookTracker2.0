
<div class="min-h-screen bg-gray-50 dark:bg-zinc-900 py-4 sm:py-8" 
    
@if($pollingEnabled) wire:poll.2s @endif>
    <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Webhook Event Tracker</h1>
                    <p class="mt-2 text-sm sm:text-base text-gray-600 dark:text-zinc-400">Track and monitor webhook events for your session</p>
                </div>
                
                <!-- Real-time Toggle -->
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <span class="text-sm text-gray-600 dark:text-zinc-400">Real-time</span>
                    <button 
                        wire:click="togglePolling"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $pollingEnabled ? 'bg-blue-600' : 'bg-gray-200 dark:bg-zinc-700' }}"
                    >
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $pollingEnabled ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                    @if($pollingEnabled)
                        <div class="flex items-center gap-1 text-green-600 dark:text-green-400">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-xs">Live</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Session Info & Controls -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700 p-4 sm:p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Your Webhook URL</label>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <input 
                            type="text" 
                            value="{{ $webhookUrl }}" 
                            readonly 
                            class="flex-1 px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md bg-gray-50 dark:bg-zinc-700 text-xs sm:text-sm font-mono text-gray-900 dark:text-white break-all"
                        >
                        <button wire:click="copyUrl"
                            onclick="navigator.clipboard.writeText('{{ $webhookUrl }}')" 
                            class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-sm whitespace-nowrap"
                        >
                            Copy
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-zinc-400">Session ID: {{ $sessionId }}</p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-2 lg:flex-row">
                    <button 
                        wire:click="generateNewSession" 
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-sm"
                    >
                        New Session
                    </button>
                    <button 
                        wire:click="clearEvents" 
                        wire:confirm="Are you sure you want to clear all events?"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-sm"
                    >
                        Clear All
                    </button>
                </div>
            </div>
             <!-- Action Messages -->
    <x-action-message on="session-generated" class="mt-4 text-green-600">
        New session generated successfully!
    </x-action-message>

    <x-action-message on="events-cleared" class="mt-4 text-green-600">
        All events cleared!
    </x-action-message>

    <x-action-message on="url-copied" class="mt-4 text-blue-600">
        URL copied to clipboard!
    </x-action-message>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Events List -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700">
                    <!-- Filters -->
                    <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-zinc-700">
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                            <div class="flex-1">
                                <input 
                                    wire:model.live="search"
                                    type="text" 
                                    placeholder="Search events..." 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md text-sm bg-white dark:bg-zinc-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-zinc-400"
                                >
                            </div>
                            <div class="min-w-0 sm:w-48">
                                <select wire:model.live="eventTypeFilter" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md text-sm bg-white dark:bg-zinc-700 text-gray-900 dark:text-white">
                                    <option value="">All Types</option>
                                    @foreach($eventTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Events -->
                    <div class="divide-y divide-gray-200 dark:divide-zinc-700">
                        @forelse($events as $event)
                            <div 
                                wire:click="selectEvent({{ $event->id }})"
                                class="p-3 sm:p-4 hover:bg-gray-50 dark:hover:bg-zinc-700/50 cursor-pointer {{ $selectedEvent && $selectedEvent->id === $event->id ? 'bg-blue-50 dark:bg-blue-900/20 border-l-4 border-l-blue-500' : '' }}"
                            >
                                <!-- Mobile Layout -->
                                <div class="sm:hidden">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            @if($event->http_method)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                                    {{ $event->http_method === 'POST' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : '' }}
                                                    {{ $event->http_method === 'GET' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' : '' }}
                                                    {{ $event->http_method === 'PUT' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' : '' }}
                                                    {{ $event->http_method === 'DELETE' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' : '' }}
                                                    {{ !in_array($event->http_method, ['POST', 'GET', 'PUT', 'DELETE']) ? 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300' : '' }}
                                                ">
                                                    {{ $event->http_method }}
                                                </span>
                                            @endif
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300">
                                                {{ Str::limit($event->event_type, 15) }}
                                            </span>
                                        </div>
                                        <button 
                                            wire:click.stop="deleteEvent({{ $event->id }})"
                                            class="text-red-400 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 p-1"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="flex flex-col gap-1 text-xs text-gray-500 dark:text-zinc-400">
                                        <span class="font-medium">{{ $event->received_at->format('M j, H:i:s') }}</span>
                                        <div class="flex items-center justify-between">
                                            @if($event->source_ip)
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $event->source_ip }}
                                                </span>
                                            @endif
                                            <span>
                                                @if(is_array($event->payload) && count($event->payload) > 0)
                                                    {{ count($event->payload) }} fields
                                                @else
                                                    No payload
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Desktop Layout -->
                                <div class="hidden sm:flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            @if($event->http_method)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                                    {{ $event->http_method === 'POST' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : '' }}
                                                    {{ $event->http_method === 'GET' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' : '' }}
                                                    {{ $event->http_method === 'PUT' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' : '' }}
                                                    {{ $event->http_method === 'DELETE' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' : '' }}
                                                    {{ !in_array($event->http_method, ['POST', 'GET', 'PUT', 'DELETE']) ? 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300' : '' }}
                                                ">
                                                    {{ $event->http_method }}
                                                </span>
                                            @endif
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300">
                                                {{ $event->event_type }}
                                            </span>
                                            <span class="text-sm text-gray-500 dark:text-zinc-400">
                                                {{ $event->received_at->format('M j, Y H:i:s') }}
                                            </span>
                                        </div>
                                        <div class="mt-1 flex items-center gap-4 text-xs text-gray-500 dark:text-zinc-400">
                                            @if($event->source_ip)
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $event->source_ip }}
                                                </span>
                                            @endif
                                            <span>
                                                @if(is_array($event->payload) && count($event->payload) > 0)
                                                    {{ count($event->payload) }} fields
                                                @else
                                                    No payload
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <button 
                                        wire:click.stop="deleteEvent({{ $event->id }})"
                                        class="ml-2 text-red-400 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No webhook events</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">Send some webhooks to see them here</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($events->hasPages())
                        <div class="p-3 sm:p-4 border-t border-gray-200 dark:border-zinc-700">
                            {{ $events->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Event Details -->
            <div class="lg:col-span-1">
                <!-- Mobile: Show as modal-like overlay when event selected -->
                @if($selectedEvent)
                    <div 
                        class="lg:hidden fixed inset-0 z-50 bg-black bg-opacity-50" 
                        wire:click="closeModal"
                    >
                        <div class="absolute inset-x-0 bottom-0 bg-white dark:bg-zinc-800 rounded-t-lg max-h-[85vh] overflow-hidden">
                            <!-- Header with close button -->
                            <div class="bg-white dark:bg-zinc-800 p-4 border-b border-gray-200 dark:border-zinc-700 flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Event Details</h3>
                                <button 
                                    wire:click="closeModal"
                                    type="button"
                                    class="bg-gray-100 dark:bg-zinc-700 hover:bg-gray-200 dark:hover:bg-zinc-600 rounded-full p-2 text-gray-400 hover:text-gray-600 dark:text-zinc-400 dark:hover:text-zinc-200 transition-colors"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Scrollable content -->
                            <div class="overflow-y-auto max-h-[calc(85vh-80px)]">
                                <div class="p-4 space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">HTTP Method</label>
                                            <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $selectedEvent->http_method === 'POST' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : '' }}
                                                {{ $selectedEvent->http_method === 'GET' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' : '' }}
                                                {{ $selectedEvent->http_method === 'PUT' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' : '' }}
                                                {{ $selectedEvent->http_method === 'DELETE' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' : '' }}
                                                {{ !in_array($selectedEvent->http_method, ['POST', 'GET', 'PUT', 'DELETE']) ? 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300' : '' }}
                                            ">
                                                {{ $selectedEvent->http_method ?? 'Unknown' }}
                                            </span>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Event Type</label>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-white break-words">{{ $selectedEvent->event_type }}</p>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Received At</label>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $selectedEvent->received_at->format('M j, Y H:i:s') }}</p>
                                    </div>
                                    
                                    @if($selectedEvent->source_ip)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Source IP</label>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $selectedEvent->source_ip }}</p>
                                        </div>
                                    @endif

                                    @if($selectedEvent->user_agent)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">User Agent</label>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-white break-all">{{ $selectedEvent->user_agent }}</p>
                                        </div>
                                    @endif

                                    @if($selectedEvent->headers && count($selectedEvent->headers) > 0)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Headers</label>
                                            <div class="bg-gray-50 dark:bg-zinc-900 rounded border border-gray-200 dark:border-zinc-600 max-h-40 overflow-y-auto">
                                                <pre class="text-xs text-gray-900 dark:text-zinc-100 p-3 whitespace-pre-wrap break-words">{{ json_encode($selectedEvent->headers, JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-2">Payload</label>
                                        <div class="bg-gray-50 dark:bg-zinc-900 rounded border border-gray-200 dark:border-zinc-600 max-h-60 overflow-y-auto">
                                            <pre class="text-xs text-gray-900 dark:text-zinc-100 p-3 whitespace-pre-wrap break-words">{{ json_encode($selectedEvent->payload, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    </div>
                                    
                                    <!-- Bottom padding for better scrolling -->
                                    <div class="pb-4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Desktop: Keep original sidebar layout -->
                <div class="hidden lg:block bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700 sticky top-8">
                    @if($selectedEvent)
                        <div class="p-4 border-b border-gray-200 dark:border-zinc-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Event Details</h3>
                        </div>
                        <div class="p-4 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">HTTP Method</label>
                                    <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $selectedEvent->http_method === 'POST' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : '' }}
                                        {{ $selectedEvent->http_method === 'GET' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' : '' }}
                                        {{ $selectedEvent->http_method === 'PUT' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' : '' }}
                                        {{ $selectedEvent->http_method === 'DELETE' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' : '' }}
                                        {{ !in_array($selectedEvent->http_method, ['POST', 'GET', 'PUT', 'DELETE']) ? 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300' : '' }}
                                    ">
                                        {{ $selectedEvent->http_method ?? 'Unknown' }}
                                    </span>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Event Type</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $selectedEvent->event_type }}</p>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Received At</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $selectedEvent->received_at->format('M j, Y H:i:s') }}</p>
                            </div>
                            
                            @if($selectedEvent->source_ip)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Source IP</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $selectedEvent->source_ip }}</p>
                                </div>
                            @endif

                            @if($selectedEvent->user_agent)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">User Agent</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white break-all">{{ $selectedEvent->user_agent }}</p>
                                </div>
                            @endif

                            @if($selectedEvent->headers && count($selectedEvent->headers) > 0)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Headers</label>
                                    <div class="mt-1 max-h-32 overflow-y-auto">
                                        <pre class="text-xs text-gray-900 dark:text-zinc-100 bg-gray-50 dark:bg-zinc-900 rounded p-3 border border-gray-200 dark:border-zinc-600">{{ json_encode($selectedEvent->headers, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                </div>
                            @endif
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">Payload</label>
                                <pre class="mt-1 text-xs text-gray-900 dark:text-zinc-100 bg-gray-50 dark:bg-zinc-900 rounded p-3 overflow-auto max-h-96 border border-gray-200 dark:border-zinc-600">{{ json_encode($selectedEvent->payload, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Select an event</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">Click on an event to view its details</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
