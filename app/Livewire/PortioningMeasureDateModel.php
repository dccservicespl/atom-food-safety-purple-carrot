<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PortioningMeasureHead;
use Illuminate\Support\Facades\Auth;

class PortioningMeasureDateModel extends Component
{
    public $mode = 'list';
    public $measurements = [];
    public $showStartModal = false;

    // Form fields for start modal
    public $equipment;
    public $table;
    public $pre_op_complete;
    public $people_qty;
    public $scale;

    protected $rules = [
        'equipment' => 'required',
        'table' => 'required',
        'pre_op_complete' => 'required',
        'people_qty' => 'required|integer',
        'scale' => 'required',
    ];

    protected $messages = [
        'equipment.required' => 'Equipment is required',
        'table.required' => 'Table is required',
        'pre_op_complete.required' => 'Pre-Op Complete is required',
        'people_qty.required' => 'People Qty is required',
        'people_qty.integer' => 'People Qty must be an integer',
        'scale.required' => 'Scale # is required',
    ];

    public function mount()
    {
        $this->loadMeasurements();
    }

    public function loadMeasurements()
    {
        $this->measurements = PortioningMeasureHead::latest()->get();
    }

    public function create()
    {
        $user_id = Auth::id();
        $incomplete = PortioningMeasureHead::where('measure_by', $user_id)
            ->whereNull('end_time')
            ->first();

        if ($incomplete) {
            session()->flash('error', 'You have an incomplete measurement. Please end it first before starting a new one.');
            return;
        }

        // Reset form fields and open modal
        $this->resetFormFields();
        $this->showStartModal = true;
    }

    public function resetFormFields()
    {
        $this->equipment = null;
        $this->table = null;
        $this->pre_op_complete = null;
        $this->people_qty = null;
        $this->scale = null;
        $this->resetValidation();
    }

    public function closeStartModal()
    {
        $this->showStartModal = false;
        $this->resetFormFields();
    }

    public function savePortioningHead()
    {
        // Validate and let Livewire handle the errors
        try {
            $this->validate($this->rules, $this->messages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw to ensure errors are properly displayed in the view
            throw $e;
        }

        // If validation passed, save the data
        try {
            $user_id = Auth::id();

            PortioningMeasureHead::create([
                'start_time' => now(),
                'measure_by' => $user_id,
                'equipment' => $this->equipment,
                'table_name' => $this->table,
                'pre_op_complete' => $this->pre_op_complete === 'Yes',
                'people_qty' => $this->people_qty,
                'scale' => $this->scale,
                'status' => 'pending',
            ]);

            session()->flash('success', 'Measurement started successfully!');
            $this->closeStartModal();
            $this->loadMeasurements();
        } catch (\Exception $e) {
            session()->flash('error', 'Error saving measurement: ' . $e->getMessage());
        }
    }

    public function completeMeasure()
    {
        $user_id = Auth::id();
        $measurement = PortioningMeasureHead::where('measure_by', $user_id)
            ->whereNull('end_time')
            ->first();

        if (!$measurement) {
            session()->flash('error', 'No active measurement found. Please start one first.');
            return;
        }
        $measurement->update([
            'end_time' => now(),
            'status' => 'completed',
        ]);

        session()->flash('success', 'Measurement completed successfully!');
        $this->loadMeasurements();
    }

    public function render()
    {
        return view('livewire.portioning-measure-date-model');
    }
}
