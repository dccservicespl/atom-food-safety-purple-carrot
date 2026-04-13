<?php

namespace App\Livewire;

use App\Models\PortioningOrderDetail;
use App\Models\PortioningOrderHead;
use Carbon\Carbon;
use Livewire\Component;

class WeekDetail extends Component
{
    public $order_head_id;
    public $order_head;
    public $selected_day;
    public $week_days            = [];
    public $days_with_categories = [];
    public $today;

    public function mount($order_head_id = null)
    {
        if (!$order_head_id) {
            abort(404, 'Order head ID is required.');
        }

        $this->order_head_id = $order_head_id;
        $this->order_head    = PortioningOrderHead::where('order_head_id', $order_head_id)->firstOrFail();
        $this->today         = now()->toDateString();

        // Generate full week days from from_date to to_date
        $from    = Carbon::parse($this->order_head->from_date);
        $to      = Carbon::parse($this->order_head->to_date);
        $days    = [];
        $current = $from->copy();

        while ($current->lte($to)) {
            $days[] = $current->toDateString();
            $current->addDay();
        }

        $this->week_days = $days;

        // Default selected day = today if in range, else first day
        $this->selected_day = in_array($this->today, $this->week_days)
            ? $this->today
            : $this->week_days[0];

        $this->loadDaysWithCategories();
    }

    public function selectDay($day)
    {
        $this->selected_day = $day;
    }

    protected function loadDaysWithCategories()
    {
        $rows = PortioningOrderDetail::join('portioning_categories', 'portioning_order_details.portioning_category_id', '=', 'portioning_categories.category_id')
            ->where('portioning_order_details.order_head_id', $this->order_head_id)
            ->select(
                'portioning_order_details.scheduled_day',
                'portioning_order_details.status',
                'portioning_categories.category_name',
            )
            ->orderBy('scheduled_day')
            ->get();

        // Initialize all days even if empty
        $data = [];
        foreach ($this->week_days as $day) {
            $data[$day] = [];
        }

        // Group by scheduled_day with unique categories per day
        foreach ($rows as $row) {
            $day     = $row->scheduled_day;
            $catName = $row->category_name;

            $existing = array_column($data[$day] ?? [], 'category_name');
            if (!in_array($catName, $existing)) {
                $data[$day][] = [
                    'category_name' => $catName,
                    'status'        => $row->status ?? 'not_started',
                ];
            }
        }

        $this->days_with_categories = $data;
    }

    public function render()
    {
        return view('livewire.week-detail');
    }
}
