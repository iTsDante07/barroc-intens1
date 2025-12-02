<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    public $notifications;
    public $unreadCount = 0;

    public function mount()
    {
        if (Auth::check()) {
            $this->loadNotifications();
        }
    }

    public function loadNotifications()
    {
        $user = Auth::user();
        $this->notifications = $user->notifications()->latest()->get();
        $this->unreadCount = $user->unreadNotifications->count();
    }

    public function markAsRead($notificationId)
    {
        $user = Auth::user();
        $notification = $user->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
