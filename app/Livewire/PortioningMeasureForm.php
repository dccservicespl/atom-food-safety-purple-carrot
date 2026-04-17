<?php

namespace App\Livewire;

use App\Models\PortioningCategory;
use App\Models\PortioningMeasureHead;
use App\Models\PortioningOrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PortioningMeasureForm extends Component
{
    public string $mode = 'read_only';

    public ?int $table = null;
    public ?string $preop = null;
    public ?int $people_qty = null;
    public ?string $scale = null;
    public bool $showStartTimeModal = false;
    public $portioning_order_data = [];
    public $order_head_id;
    public $portioning_category_id;
    public $item_id = null;

    protected function rules(): array
    {
        return [
            'table'      => 'required|integer|between:1,8',
            'preop'      => 'required',
            'people_qty' => 'required|integer|min:1',
            'scale'      => 'required|string|max:50',
        ];
    }

    protected $messages = [
        'table.required'      => 'Please select a table.',
        'preop.required'      => 'Please select Pre-Op Complete status.',
        'people_qty.required' => 'People quantity is required.',
        'people_qty.min'      => 'People quantity must be at least 1.',
        'scale.required'      => 'Scale number is required.',
    ];

    public function openStartTimePopup(){
        $this->showStartTimeModal = true;
    }

    public function closeStartTimePopup(){
        $this->showStartTimeModal = false;
    }

    public function startMeasurement()
    {
        $this->validate();
        try {
            PortioningMeasureHead::updateOrCreate(
                [
                    'portioning_order_head_id' => $this->order_head_id,
                    'portioning_category_id'   => $this->portioning_category_id,
                    'scheduled_day'            => date('Y-m-d'),
                ],
                [
                    'start_time'      => date('H:i:s'),
                    'measure_by'      => Auth::user()->id,
                    'table_name'      => $this->table,
                    'people_qty'      => $this->people_qty,
                    'scale'           => $this->scale,
                    'pre_op_complete' => $this->preop,
                ]
            );

            $this->reset(['table', 'preop', 'people_qty', 'scale']);

            $this->mode = 'edit_mode';
            $this->closeStartTimePopup();
            session()->flash('success', 'Measurement time has been started.');

        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            Log::error('startMeasurement error: ' . $th->getMessage());
        }
    }

    public function measurementFormOpen($item_id = null)
    {
        $this->mode = 'measure_form_open';
    }

    public function render()
    {
        $check_start_time = PortioningMeasureHead::where('portioning_order_head_id', $this->order_head_id)
            ->where('portioning_category_id', $this->portioning_category_id)
            ->where('scheduled_day', date('Y-m-d'))
            ->first();
        // Only override mode if currently in read_only (don't override measure_form_open)
        if ($this->mode === 'read_only' && $check_start_time && $check_start_time->start_time) {
            $this->mode = 'edit_mode';
        }

         $data = PortioningOrderDetail::with('category')
            ->where('order_head_id', $this->order_head_id)
            ->where('portioning_category_id', $this->portioning_category_id)
            // ->where('scheduled_day', date('Y-m-d'))
            ->get();

        $this->portioning_order_data = $data;
        $portioning_category_name = PortioningCategory::where('category_id',  $this->portioning_category_id)->value('category_name');
        return view('livewire.portioning-measure-form', compact('check_start_time', 'portioning_category_name'));
    }


}
