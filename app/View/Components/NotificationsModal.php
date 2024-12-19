<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NotificationsModal extends Component
{
    /**
     * Create a new component instance.
     */
    private $user;
    public function __construct()
    {
        $this->user = auth()->user();
    }  

    public function notifications()
    {
        return $this->user->notifications;
    //     dd($this->user->notifications->groupBy(function ($notification) {
    //         return $notification->type;
    //     }));

    //     return $this->user->notifications->groupBy(function ($notification) {
    //         return $notification->type;
    //     });
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.notifications-modal',[
            'notifications' => $this->notifications()
        ]);
    }
}
