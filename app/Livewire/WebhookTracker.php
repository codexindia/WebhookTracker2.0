<?php

namespace App\Livewire;

use App\Models\WebhookEvent;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class WebhookTracker extends Component
{
    use WithPagination;

    public $sessionId;
    public $webhookUrl;
    public $selectedEvent = null;
    public $eventTypeFilter = '';
    public $search = '';
    public $pollingEnabled = true;

    protected $queryString = ['sessionId', 'eventTypeFilter', 'search'];
    public function copyUrl()
    {

        $this->dispatch('url-copied');
    }
    public function mount()
    {
        // Generate or retrieve session ID
        if (!$this->sessionId) {
            $this->sessionId = session('webhook_session_id') ?? 'session_' . Str::random(10);
            session(['webhook_session_id' => $this->sessionId]);
        }

        $this->webhookUrl = url("/webhook/{$this->sessionId}");
    }

    public function generateNewSession()
    {
        $this->sessionId = 'session_' . Str::random(10);
        session(['webhook_session_id' => $this->sessionId]);
        $this->webhookUrl = url("/webhook/{$this->sessionId}");
        $this->selectedEvent = null;
        $this->resetPage();
    }

    public function selectEvent($eventId)
    {
        $this->selectedEvent = WebhookEvent::find($eventId);
    }

    public function closeModal()
    {
        $this->selectedEvent = null;
    }

    public function clearEvents()
    {
        WebhookEvent::forSession($this->sessionId)->delete();
        $this->selectedEvent = null;
        $this->resetPage();
    }

    public function deleteEvent($eventId)
    {
        WebhookEvent::find($eventId)?->delete();
        if ($this->selectedEvent && $this->selectedEvent->id == $eventId) {
            $this->selectedEvent = null;
        }
    }

    public function updatedEventTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getEventsProperty()
    {
        $query = WebhookEvent::forSession($this->sessionId)
            ->orderBy('received_at', 'desc');

        if ($this->eventTypeFilter) {
            $query->where('event_type', $this->eventTypeFilter);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('event_type', 'like', "%{$this->search}%")
                    ->orWhere('payload', 'like', "%{$this->search}%");
            });
        }

        return $query->paginate(10);
    }

    public function getEventTypesProperty()
    {
        return WebhookEvent::forSession($this->sessionId)
            ->distinct()
            ->pluck('event_type')
            ->filter()
            ->sort();
    }

    public function togglePolling()
    {
        $this->pollingEnabled = !$this->pollingEnabled;
    }

    public function render()
    {
        return view('livewire.webhook-tracker', [
            'events' => $this->events,
            'eventTypes' => $this->eventTypes,
        ]);
    }
}
