<?php

namespace App\Livewire;

use App\Models\PortioningCategory;
use App\Models\PortioningMeasureHead;
use App\Models\PortioningMeasurement;
use App\Models\PortioningOrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

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

    // New properties for measure form
    public ?int $measurement_id = null;
    public $lot_number = '';
    public $temperature = '';
    public $allergen_result = '';
    public $allergen = '';
    public $pack_size = '';
    public $simple = [];
    public $kit_letter = '';
    public $qty_produces_final = '';
    public $fs_initial = '';
    public $description = '';

    public $selected_item_name = '';
    public $measure_date = '';

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

    public function mount()
    {
        $this->simple = [''];
    }

    public function openStartTimePopup()
    {
        $this->showStartTimeModal = true;
    }

    public function closeStartTimePopup()
    {
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

    public function measurementFormOpen($item_id)
    {
        $this->item_id = $item_id;

        $orderDetail = PortioningOrderDetail::findOrFail($item_id);
        $this->selected_item_name = $orderDetail->component_details ?? $orderDetail->component_details ?? 'Item';
        $this->measure_date = now()->format('m/d/Y');

        $this->loadExistingMeasurement($item_id);

        $this->mode = 'measure_form_open';
    }

    private function loadExistingMeasurement($item_id)
    {
        $today = date('Y-m-d');
        $measureHead = PortioningMeasureHead::where([
            'portioning_order_head_id' => $this->order_head_id,
            'portioning_category_id' => $this->portioning_category_id,
            'scheduled_day' => $today
        ])->first();

        if (!$measureHead) return;

        $existing = PortioningMeasurement::where([
            'item_id' => $item_id,
            'measure_id' => $measureHead->id,
        ])->orderBy('created_at', 'desc')
            ->first();

        if ($existing) {
            $this->measurement_id = $existing->id;
            $this->lot_number = $existing->lot_number;
            $this->temperature = $existing->temperature;
            $this->allergen_result = $existing->allergen_result;
            $this->allergen = $existing->allergen;
            $this->pack_size = $existing->pack_size;
            $this->simple = $existing->simple_samples ? json_decode($existing->simple_samples, true) : [''];
            $this->kit_letter = $existing->kit_letter;
            $this->qty_produces_final = $existing->qty_produces_final;
            $this->fs_initial = $existing->fs_initial;
            $this->description = $existing->description;
            $this->simple = $existing->simple_samples ? (array) json_decode($existing->simple_samples, true) : [''];
        } else {
            $this->resetFormProperties();
        }
    }

    public function addSample()
    {
        $this->simple[] = '';
    }

    public function removeSample($index)
    {
        unset($this->simple[$index]);
        $this->simple = array_values($this->simple);
    }

    public function save()
    {
        $this->validate([
            'lot_number' => 'required|string|max:255',
            'temperature' => 'required|numeric',
            'allergen_result' => 'required|in:Yes,No,N/A',
            'allergen' => 'nullable|string|max:255',
            'pack_size' => 'nullable|numeric',
            'kit_letter' => 'nullable|string|max:50',
            'qty_produces_final' => 'nullable|numeric',
            'fs_initial' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'simple' => 'array|min:1',
            'simple.*' => 'nullable|string|max:255',
        ]);

        try {
            $measureHead = PortioningMeasureHead::where([
                'portioning_order_head_id' => $this->order_head_id,
                'portioning_category_id' => $this->portioning_category_id,
                'scheduled_day' => date('Y-m-d')
            ])->firstOrFail();

            $data = [
                'item_id' => $this->item_id,
                'measure_id' => $measureHead->id,
                'measure_date' => date('Y-m-d'),
                'measure_time' => now()->format('H:i:s'),
                'measure_by' => Auth::id(),
                'lot_number' => $this->lot_number,
                'temperature' => $this->temperature,
                'allergen_result' => $this->allergen_result,
                'allergen' => $this->allergen ?: null,
                'pack_size' => $this->pack_size ?: null,
                'simple_samples' => json_encode($this->simple),
                'kit_letter' => $this->kit_letter ?: null,
                'qty_produces_final' => $this->qty_produces_final ?: null,
                'fs_initial' => $this->fs_initial ?: null,
                'description' => $this->description ?: null,
            ];

            // Update or Create
            if ($this->measurement_id) {
                PortioningMeasurement::find($this->measurement_id)->update($data);
                session()->flash('success', 'Measurement updated successfully.');
            } else {
                PortioningMeasurement::create($data);
                session()->flash('success', 'Measurement saved successfully.');
            }

            PortioningOrderDetail::where('order_detail_id', $this->item_id)->update(['status' => 'Completed']);

            $this->resetFormProperties();
            $this->mode = 'edit_mode';
        } catch (Throwable $th) {
            Log::error('Save error: ' . $th->getMessage());
            $this->addError('lot_number', $th->getMessage());
        }
    }

    public function cancel()
    {
        $this->resetFormProperties();
        $this->mode = 'edit_mode';
    }

    private function resetFormProperties()
    {
        $this->reset([
            'measurement_id',
            'lot_number',
            'temperature',
            'allergen_result',
            'allergen',
            'pack_size',
            'simple',
            'kit_letter',
            'qty_produces_final',
            'fs_initial',
            'description'
        ]);
        $this->simple = [''];
        $this->measurement_id = null;
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
