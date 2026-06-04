<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PortioningMeasurement;
use App\Models\PortioningMeasurementSample;
use App\Models\PortioningMeasureHead;
use App\Models\PurpleCarrotCategory;
use App\Models\PurpleCarrotItemMst;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PortioningMeasurementManager extends Component
{
    use WithFileUploads;

    public $mode = 'list';
    public $measure_id;
    public $measure_head;
    public $filter_date;

    // Modal properties
    public $showItemModal = false;
    public $itemSearch = '';
    public $categorySearch = '';
    public $filteredItems = [];
    public $availableCategories = [];

    // Form fields
    public $measurement_id;
    public $item_id;
    public $item_details;
    public $measure_date;
    public $measure_time;
    public $equipment;
    public $table;
    public $pre_op_complete;
    public $people_qty;
    public $scale;
    public $lot_number;
    public $temperature;
    public $allergen;
    public $allergen_result;
    public $pack_size;
    public $samples = [];
    public $kit_letter;
    public $qty_produces_final;
    public $fs_initial;
    public $attachment;
    public $attachment_preview;
    public $description;

    public $measurements = [];

    protected $rules = [
        // 'equipment' => 'required',
        // 'table' => 'required',
        // 'pre_op_complete' => 'required',
        // 'people_qty' => 'required|integer',
        // 'scale' => 'required',
        'lot_number' => 'required',
        'temperature' => 'required',
        'allergen' => 'required',
        'pack_size' => 'required|numeric',
        'samples' => 'required|array|min:1',
        'samples.*.sample_value' => 'required|numeric',
        'kit_letter' => 'required',
        'qty_produces_final' => 'required|integer',
        'fs_initial' => 'required',
        'attachment' => 'nullable|file|max:10240',
        'description' => 'nullable|string',
    ];

    protected $messages = [
        // 'equipment.required' => 'Equipment is required, Select any of one.',
        // 'table.required' => 'Table is required, Select any of one.',
        // 'pre_op_complete.required' => 'Pre-Op Complete is required, Select any of one.',
        // 'people_qty.required' => 'People Qty is required',
        // 'people_qty.integer' => 'People Qty must be an integer',
        // 'scale.required' => 'Scale # is required',
        'lot_number.required' => 'Lot number is required',
        'temperature.required' => 'Temperature is required',
        'temperature.numeric' => 'Temperature must be numeric',
        'allergen.required' => 'Allergen is required',
        'pack_size.required' => 'Pack size is required',
        'pack_size.numeric' => 'Pack size must be numeric',
        'samples.required' => 'At least one sample is required',
        'samples.min' => 'At least one sample is required',
        'samples.*.sample_value.required' => 'Sample value is required',
        'samples.*.sample_value.numeric' => 'Sample value must be numeric',
        'kit_letter.required' => 'Kit letter is required',
        'qty_produces_final.required' => 'Qty produces (Final) is required',
        'qty_produces_final.integer' => 'Qty produces (Final) must be an integer',
        'fs_initial.required' => 'FS initial is required',
        'attachment.file' => 'Attachment must be a file',
        'attachment.max' => 'Attachment must not exceed 2MB',
        'description.string' => 'Description must be text',
    ];

    public function mount()
    {
        try {
            // Get measure_id from route parameter
            $measure_id = request('measure_id');

            if ($measure_id) {
                $this->measure_id = Crypt::decrypt($measure_id);
                $this->measure_head = PortioningMeasureHead::findOrFail($this->measure_id);
                $this->loadMeasurements();
            }

            $this->filter_date = today()->format('Y-m-d');
            $this->measure_date = today()->format('Y-m-d');
            $this->measure_time = now()->format('H:i');
        } catch (\Exception $e) {
            Log::error('Error mounting component: ' . $e->getMessage());
        }
    }

    public function loadMeasurements()
    {
        if (!$this->measure_id) return;

        $query = PortioningMeasurement::with(['item', 'measuredBy'])
            ->where('measure_id', $this->measure_id);

        if ($this->filter_date) {
            $query->whereDate('measure_date', $this->filter_date);
        }

        $this->measurements = $query->latest()->get();
    }

    public function updatedItemSearch()
    {
        $this->filterItems();
    }

    public function updatedCategorySearch()
    {
        $this->filterItems();
    }

    public function filterItems()
    {
        $query = PurpleCarrotItemMst::query();

        if ($this->itemSearch) {
            $query->where('component_details', 'like', '%' . $this->itemSearch . '%');
        }

        if ($this->categorySearch) {
            $query->where('category_id', $this->categorySearch);
        }

        $this->filteredItems = $query->limit(10)->get();
    }

    public function openItemModal()
    {
        $this->showItemModal = true;
        $this->availableCategories = PurpleCarrotCategory::all();
        $this->filteredItems = PurpleCarrotItemMst::limit(10)->get();
    }

    public function closeItemModal()
    {
        $this->showItemModal = false;
        $this->itemSearch = '';
        $this->categorySearch = '';
        $this->filteredItems = [];
        $this->resetValidation();
    }

    public function selectItem($item_id)
    {
        try {
            $item = PurpleCarrotItemMst::findOrFail($item_id);

            // Create new measurement record
            $measurement = PortioningMeasurement::create([
                'measure_id' => $this->measure_id,
                'item_id' => $item_id,
                'measure_date' => today(),
                'measure_time' => now(),
                'measure_by' => Auth::id(),
            ]);

            // Create initial sample
            PortioningMeasurementSample::create([
                'measure_id' => $measurement->id,
                'sample_number' => 1,
                'sample_value' => 0,
            ]);

            session()->flash('message', 'Item added to measurement. Please fill in the details.');
            $this->closeItemModal();
            $this->loadMeasurements();
        } catch (\Exception $e) {
            Log::error('Error selecting item: ' . $e->getMessage());
            session()->flash('error', 'Error adding item: ' . $e->getMessage());
        }
    }

    public function updatedFilterDate()
    {
        $this->loadMeasurements();
    }

    public function edit($id)
    {
        $measurement = PortioningMeasurement::with('samples')->findOrFail($id);

        $this->measurement_id = $measurement->id;
        $this->item_id = $measurement->item_id;
        $this->item_details = $measurement->item;
        // $this->equipment = $measurement->equipment;
        // $this->table = $measurement->table;
        // $this->pre_op_complete = $measurement->pre_op_complete;
        // $this->people_qty = $measurement->people_qty;
        // $this->scale = $measurement->scale;
        $this->lot_number = $measurement->lot_number;
        $this->temperature = $measurement->temperature;
        $this->allergen = $measurement->allergen;
        $this->allergen_result = $measurement->allergen_result;
        $this->pack_size = $measurement->pack_size;

        // Load samples from database
        $this->samples = $measurement->samples->map(function ($sample) {
            return [
                'id' => $sample->id,
                'sample_number' => $sample->sample_number,
                'sample_value' => $sample->sample_value,
            ];
        })->toArray();

        $this->kit_letter = $measurement->kit_letter;
        $this->qty_produces_final = $measurement->qty_produces_final;
        $this->fs_initial = $measurement->fs_initial;
        $this->attachment_preview = $measurement->attachment;
        $this->description = $measurement->description;

        $this->mode = 'edit';
    }

    public function save()
    {
        // First, validate the form - this will populate the error bag if there are validation errors
        try {
            $this->validate($this->rules, $this->messages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw to let Livewire handle it and populate the error bag
            throw $e;
        }

        // If we reach here, validation passed
        try {
            DB::beginTransaction();
            $data = [
                'equipment' => $this->equipment,
                'table' => $this->table,
                'pre_op_complete' => $this->pre_op_complete,
                'people_qty' => $this->people_qty,
                'scale' => $this->scale,
                'lot_number' => $this->lot_number,
                'temperature' => $this->temperature,
                'allergen_result' => $this->allergen_result,
                'allergen' => $this->allergen,
                'pack_size' => $this->pack_size,
                'kit_letter' => $this->kit_letter,
                'qty_produces_final' => $this->qty_produces_final,
                'fs_initial' => $this->fs_initial,
                'description' => $this->description,
            ];

            if ($this->attachment) {
                $data['attachment'] = $this->attachment->store('attachments', 'public');
            }

            // dd($this->measurement_id, $data);
            if ($this->measurement_id) {
                $measurement = PortioningMeasurement::find($this->measurement_id);
                $measurement->update($data);

                // Delete existing samples and create new ones
                $measurement->samples()->delete();
                foreach ($this->samples as $index => $sample) {
                    PortioningMeasurementSample::create([
                        'measure_id' => $measurement->id,
                        'sample_number' => $index + 1,
                        'sample_value' => $sample['sample_value'],
                    ]);
                }
                session()->flash('success', 'Measurement updated successfully.');
            } else {
                $measurement = PortioningMeasurement::create($data);

                // Create samples
                foreach ($this->samples as $index => $sample) {
                    PortioningMeasurementSample::create([
                        'measure_id' => $measurement->id,
                        'sample_number' => $index + 1,
                        'sample_value' => $sample['sample_value'],
                    ]);
                }

                session()->flash('success', 'Measurement created successfully.');
            }
            DB::commit();
            $this->mode = 'list';
            $this->loadMeasurements();
            $this->resetForm();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving measurement: ' . $e->getMessage());
            session()->flash('error', 'Error saving measurement: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        $this->resetValidation();
        $this->mode = 'list';
        $this->resetForm();
    }

    public function delete($id)
    {
        try {
            PortioningMeasurement::findOrFail($id)->delete();
            $this->resetValidation();
            session()->flash('message', 'Measurement deleted successfully!');
            $this->loadMeasurements();
        } catch (\Exception $e) {
            Log::error('Error deleting measurement: ' . $e->getMessage());
            session()->flash('error', 'Error deleting measurement.');
        }
    }

    private function resetForm()
    {
        $this->measurement_id = null;
        $this->item_id = null;
        $this->item_details = null;
        $this->equipment = null;
        $this->table = null;
        $this->pre_op_complete = null;
        $this->people_qty = null;
        $this->scale = null;
        $this->lot_number = null;
        $this->temperature = null;
        $this->allergen = null;
        $this->pack_size = null;
        $this->samples = [
            ['sample_number' => 1, 'sample_value' => null],
        ];
        $this->kit_letter = null;
        $this->qty_produces_final = null;
        $this->fs_initial = null;
        $this->attachment = null;
        $this->attachment_preview = null;
        $this->description = null;

        $this->measure_date = today()->format('Y-m-d');
        $this->measure_time = now()->format('H:i');
    }

    public function addSample()
    {
        $this->samples[] = [
            'sample_number' => count($this->samples) + 1,
            'sample_value' => null,
        ];
    }

    public function removeSample($index)
    {
        unset($this->samples[$index]);
        $this->samples = array_values($this->samples);

        // Update sample numbers
        foreach ($this->samples as $key => $sample) {
            $this->samples[$key]['sample_number'] = $key + 1;
        }
    }

    public function updatedAttachment()
    {
        if ($this->attachment) {
            $this->attachment_preview = $this->attachment->temporaryUrl();
        }
    }

    public function clearAttachment()
    {
        $this->attachment = null;
        $this->attachment_preview = null;
    }

    public function render()
    {
        return view('livewire.portioning-measurement-manager');
    }
}
