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

        // Generate full week days from from_date to to_date, ensuring full week
        $from = Carbon::parse($this->order_head->from_date)->startOfWeek(Carbon::MONDAY);
        $to = Carbon::parse($this->order_head->to_date)->endOfWeek(Carbon::SUNDAY);
        $days = [];
        $current = $from->copy();

        while ($current->lte($to)) {
            $days[] = $current->toDateString();
            $current->addDay();
        }

        $this->week_days = $days;

        // Match today's day name to the same day in the week range
        $today_day_name = now()->format('l');
        $this->today    = $today_day_name;

        $matched_day = null;
        foreach ($this->week_days as $day) {
            if (Carbon::parse($day)->format('l') === $today_day_name) {
                $matched_day = $day;
                break;
            }
        }

        // Set selected day to matched day, else first day of the week
        $this->selected_day = $matched_day ?? $this->week_days[0];
        $this->loadDaysWithCategories();
    }

    public function selectDay($day)
    {
        $week_from = Carbon::parse($this->order_head->from_date);
        $week_to = Carbon::parse($this->order_head->to_date);
        $today = now()->startOfDay();
        $is_current_week = $today->between($week_from, $week_to);

        if ($is_current_week && Carbon::parse($day)->gt($today)) {
            return; // Don't allow selecting future days in current week
        }

        $this->selected_day = $day;
    }

    protected function loadDaysWithCategories()
    {
        $rows = PortioningOrderDetail::join('portioning_categories', 'portioning_order_details.portioning_category_id', '=', 'portioning_categories.category_id')
            ->where('portioning_order_details.order_head_id', $this->order_head_id)
            ->select(
                'portioning_order_details.scheduled_day',
                'portioning_order_details.status',
                'portioning_categories.category_id',
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
                    'category_id'   => $row->category_id,
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
