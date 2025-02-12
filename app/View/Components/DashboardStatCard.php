<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DashboardStatCard extends Component
{
    public $title;
    public $count;
    public $color;
    public $icon;

    public function __construct($title, $count, $color, $icon)
    {
        $this->title = $title;
        $this->count = $count;
        $this->color = $color;
        $this->icon = $icon;
    }

    public function render()
    {
        return view('components.dashboard-stat-card');
    }
}