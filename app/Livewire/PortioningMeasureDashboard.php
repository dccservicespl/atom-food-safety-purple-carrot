<?php

namespace App\Livewire;

use Livewire\Component;

class PortioningMeasureDashboard extends Component
{

    public $current_year;
    public $years = [];
    public $selected_year;
    public $selected_month;
    public $all_month = [[1,'January'], [2,'February'], [3,'March'], [4,'April'], [5,'May'], [6,'June'], [7,'July'], [8,'August'], [9,'September'], [10,'October'], [11,'November'], [12,'December']];
    public $current_week;
    public $weeks_of_month = [];

    public function mount()
    {
        $this->current_year = date('Y');
        $this->years = range($this->current_year, $this->current_year - 3, -1);
        $this->selected_year = $this->current_year;
        $this->selected_month = date('m');
        $this->current_week = (int) \Carbon\Carbon::now()->isoWeek();
        $this->get_the_weeks_of_this_month($this->selected_month, $this->selected_year);
    }


    function get_the_weeks_of_this_month(int $month, int $year = null): array
    {
        $year = $year ?? (int) date('Y');

        $firstDay = \Carbon\Carbon::create($year, $month, 1);
        $lastDay  = $firstDay->copy()->endOfMonth();

        $startWeek = (int) $firstDay->isoWeek();
        $endWeek   = (int) $lastDay->isoWeek();

        if ($endWeek < $startWeek) {
            $weeksInYear = (int) \Carbon\Carbon::create($year, 12, 28)->isoWeek();
            $weeks = range($startWeek, $weeksInYear);
            $weeks[] = 1;
        } else {
            $weeks = range($startWeek, $endWeek);
        }

        $this->weeks_of_month = $weeks;
        $this->current_week = (int) \Carbon\Carbon::now()->isoWeek();

        return [
            'total_weeks' => count($weeks),
            'weeks'       => $weeks,
            'current_week' => $this->current_week,
        ];
    }

    public function monthSelected($month)
    {
        $this->get_the_weeks_of_this_month($month, $this->selected_year);
        $this->selected_month = $month;
    }

    public function updatedSelectedYear()
    {
        $this->get_the_weeks_of_this_month($this->selected_month, $this->selected_year);
    }

    public function render()
    {
        // dd($this->selected_month, $this->selected_year, $this->current_week, $this->weeks_of_month);
        return view('livewire.portioning-measure-dashboard');
    }
}
