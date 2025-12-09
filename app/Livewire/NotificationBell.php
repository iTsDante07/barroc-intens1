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

        // Clean up old read notifications (older than 24 hours)
        $user->notifications()
            ->whereNotNull('read_at')
            ->where('read_at', '<', now()->subHours(24))
            ->delete();

        // Check for upcoming appointments (throttled to once per hour per session)
        if (!session()->has('last_appointment_check') || now()->diffInMinutes(session('last_appointment_check')) > 60) {
            $this->checkUpcomingAppointments($user);
            session()->put('last_appointment_check', now());
        }

        $this->notifications = $user->notifications()->latest()->get();
        $this->unreadCount = $user->unreadNotifications->count();
    }

    protected function checkUpcomingAppointments($user)
    {
        $appointments = \App\Models\Maintenance::where('assigned_to', $user->id)
            ->where('status', '!=', 'completed')
            ->get();

        foreach ($appointments as $appointment) {
            $daysUntil = now()->diffInDays($appointment->scheduled_date, false);

            // 1 Month Warning (approx 30 days)
            if ($daysUntil >= 29 && $daysUntil <= 31) {
                $this->sendNotificationIfNotSent($user, $appointment, '1_month');
            }

            // 1 Week Warning (7 days)
            if ($daysUntil >= 6 && $daysUntil <= 8) {
                $this->sendNotificationIfNotSent($user, $appointment, '1_week');
            }
        }
    }

    protected function sendNotificationIfNotSent($user, $appointment, $type)
    {
        $exists = $user->notifications()
            ->where('data->maintenance_id', $appointment->id)
            ->where('data->type', $type)
            ->exists();

        if (!$exists) {
            $user->notify(new \App\Notifications\MaintenanceAppointmentNotification($appointment, $type));
        }
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
