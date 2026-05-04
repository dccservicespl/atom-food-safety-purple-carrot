<?php

namespace App\Livewire;

use App\Models\PortioningCategory;
use App\Models\PortioningMeasureHead;
use App\Models\PortioningMeasurement;
use App\Models\PortioningMeasurementSample;
use App\Models\PortioningOrderDetail;
use App\Models\PortioningOrderHead;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class PortioningMeasureForm extends Component
{
    use WithFileUploads;

    public $mode = 'edit_mode';
    public $measure_form_mode = 'read_only';
    public $listing_table_type = 'item_list';

    public bool $showDownloadModal = false;

    public $table = null;
    public $preop = null;
    public $people_qty = null;
    public $scale = null;
    public bool $showStartTimeModal = false;
    public bool $showEndTimeModal = false;
    public $portioning_order_data = [];
    public $order_head_id;
    public $portioning_category_id;
    public $item_id = null;

    public $item_process_start_time;
    public $item_process_end_time;

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
    public $attachment = null;
    public $attachmentPreview = null;
    public $existingAttachment = null;
    public $description = '';

    public $selected_item_name = '';
    public $measure_date = '';
    public $selected_item_data = null;

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

    public function updatedAttachment()
    {
        if ($this->attachment) {
            $this->attachmentPreview = $this->attachment->temporaryUrl();
        }
    }

    public function removeAttachment()
    {
        $this->attachment = null;
        $this->attachmentPreview = null;
    }

    public function removeExistingAttachment()
    {
        $this->existingAttachment = null;
    }

    public function openStartTimePopup()
    {
        // dd('open start time popup'. $this->order_head_id . ' - ' . $this->portioning_category_id);
        // $get_start_time_data = PortioningMeasureHead::where('portioning_order_head_id', $this->order_head_id)
        //     ->where('portioning_category_id', $this->portioning_category_id)
        //     ->where('scheduled_day', date('Y-m-d'))
        //     ->first();

        $get_start_time_data = PortioningMeasureHead::where('portioning_order_head_id', $this->order_head_id)
            ->where('portioning_category_id', $this->portioning_category_id)
            ->where('scheduled_day', date('Y-m-d', strtotime($this->measure_date)))
            ->first();

        if ($get_start_time_data) {
            $this->table = $get_start_time_data->table_name;
            $this->preop = (string) $get_start_time_data->pre_op_complete;
            $this->people_qty = $get_start_time_data->people_qty;
            $this->scale = $get_start_time_data->scale;
        } else {
            $this->reset(['table', 'preop', 'people_qty', 'scale']);
        }


        $this->showStartTimeModal = true;
    }

    public function closeStartTimePopup()
    {
        $this->showStartTimeModal = false;
    }

    public function openEndTimePopup()
    {
        $this->showEndTimeModal = true;
    }

    public function closeEndTimePopup()
    {
        $this->showEndTimeModal = false;
    }

    public function openDownloadModal()
    {
        $this->showDownloadModal = true;
    }

    public function closeDownloadModal()
    {
        $this->showDownloadModal = false;
    }

    public function downloadReport($type = 'pdf')
    {
        $this->showDownloadModal = false;

        if ($type === 'excel') {
            $reportUrl = route('portioning_report_excel', [
                'order_head_id' => $this->order_head_id,
                'portioning_category_id' => $this->portioning_category_id,
            ]);
        } else {
            $reportUrl = route('portioning_report', [
                'order_head_id' => $this->order_head_id,
                'portioning_category_id' => $this->portioning_category_id,
            ]);
        }

        return redirect()->to($reportUrl);
    }

    public function endMeasurement()
    {
        try {
            DB::beginTransaction();
            $today = date('Y-m-d', strtotime($this->measure_date));

            // Check if all items are completed
            $pendingItems = PortioningOrderDetail::where('order_head_id', $this->order_head_id)
                ->where('portioning_category_id', $this->portioning_category_id)
                ->where('scheduled_day', $today)
                ->where('status', '!=', 'Completed')
                ->count();

            // dd($pendingItems, $this->order_head_id, $this->portioning_category_id, $today);

            if ($pendingItems > 0) {
                session()->flash('error', "Cannot end measurement. $pendingItems item(s) are still pending.");
                $this->showEndTimeModal = false;
                return;
            }
            // dd($pendingItems);

            // Update end_time in portioning_measure_heads
            $measureHead = PortioningMeasureHead::where([
                'portioning_order_head_id' => $this->order_head_id,
                'portioning_category_id' => $this->portioning_category_id,
                'scheduled_day' => $today
            ])->first();

            if ($measureHead) {
                $measureHead->update(['end_time' => now()->format('H:i:s'), 'status' => 'Completed']);
            }

            // Update status to completed in portioning_order_heads
            // PortioningOrderHead::where('order_head_id', $this->order_head_id)->update(['status' => 'Completed']);
            DB::commit();
            $this->showEndTimeModal = false;
            $this->mode = 'read_only';
            session()->flash('success', 'Measurement ended successfully.');
        } catch (Throwable $th) {
            DB::rollback();
            Log::error('End measurement error: ' . $th->getMessage());
            session()->flash('error', 'Error ending measurement: ' . $th->getMessage());
            $this->showEndTimeModal = false;
        }
    }

    public function startMeasurement()
    {
        $this->validate();
        try {
            PortioningMeasureHead::updateOrCreate(
                [
                    'portioning_order_head_id' => $this->order_head_id,
                    'portioning_category_id'   => $this->portioning_category_id,
                    'scheduled_day'            => date('Y-m-d', strtotime($this->measure_date)),
                ],
                [
                    'start_time'      => date('H:i:s'),
                    'measure_by'      => Auth::user()->id,
                    'table_name'      => $this->table,
                    'people_qty'      => $this->people_qty,
                    'scale'           => $this->scale,
                    'pre_op_complete' => $this->preop,
                    'order_details_id' => $this->item_id
                ]
            );

            $this->reset(['table', 'preop', 'people_qty', 'scale']);

            // $this->mode = 'edit_mode';
            $this->measure_form_mode = 'edit_mode';
            $this->closeStartTimePopup();
            session()->flash('success', 'Measurement time has been started.');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            Log::error('startMeasurement error: ' . $th->getMessage());
        }
    }

    public function measurementFormOpen($measure_item_id)
    {
        // dd('open measure form for item_id: ' . $measure_item_id . ' - listing_table_type: ' . $this->listing_table_type);
        if ($this->listing_table_type === 'item_measure_log') {
            $get_the_measure_item = PortioningMeasurement::findOrFail($measure_item_id);
            $this->item_id = $get_the_measure_item->item_id;
            $orderDetail = PortioningOrderDetail::findOrFail($get_the_measure_item->item_id);
            $this->item_process_start_time = $get_the_measure_item->start_time;
            $this->item_process_end_time = $get_the_measure_item->end_time;
            $this->measure_date = date('m/d/Y', strtotime($get_the_measure_item->measure_date));
        } else {
            $this->item_id = $measure_item_id;
            $orderDetail = PortioningOrderDetail::findOrFail($this->item_id);
            $this->measure_date = date('m/d/Y', strtotime($orderDetail->scheduled_day));
        }
         $this->selected_item_name = $orderDetail->component_details ?? $orderDetail->component_details ?? 'Item';

        // $this->selected_item_data = $orderDetail;

        if ($this->listing_table_type === 'item_measure_log') {
            // dd('loading existing measurement for item_id33333: ' . $measure_item_id);
            $this->loadExistingMeasurement($measure_item_id);
        }
        $this->mode = 'measure_form_open';
    }

    private function loadExistingMeasurement($measure_item_id)
    {
        $today = date('Y-m-d', strtotime($this->measure_date));
        $measureHead = PortioningMeasureHead::where([
            'portioning_order_head_id' => $this->order_head_id,
            'portioning_category_id' => $this->portioning_category_id,
            'scheduled_day' => $today
        ])->first();

        if (!$measureHead) return;

        $existing = PortioningMeasurement::findOrFail($measure_item_id);
        // dd($existing);
        if ($existing) {
            $this->measurement_id = $existing->id;
            $this->lot_number = $existing->lot_number;
            $this->temperature = $existing->temperature;
            $this->allergen_result = $existing->allergen_result;
            $this->allergen = $existing->allergen;
            $this->pack_size = $existing->pack_size;
            $this->kit_letter = $existing->kit_letter;
            $this->qty_produces_final = $existing->qty_produces_final;
            $this->fs_initial = $existing->fs_initial;
            $this->description = $existing->description;
            $this->existingAttachment = $existing->attachment;

            // Load samples from portioning_measurement_samples table
            $samples = PortioningMeasurementSample::where('measure_id', $existing->id)
                ->orderBy('sample_number')
                ->get();

            if ($samples->isNotEmpty()) {
                $this->simple = $samples->pluck('sample_value')->toArray();
            } else {
                $this->simple = $existing->simple_samples ? (array) json_decode($existing->simple_samples, true) : [''];
            }
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
            'pack_size' => 'required|numeric',
            'kit_letter' => 'required|string|max:50',
            'qty_produces_final' => 'nullable|numeric',
            'fs_initial' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'simple' => 'array|min:1',
            'simple.*' => 'required|string|max:255',
            'item_process_end_time' => 'after_or_equal:item_process_start_time',
        ]);

        try {
            DB::beginTransaction();
            // if($this->listing_table_type === 'item_measure_log'){
            $measureDateFormatted = date('Y-m-d', strtotime($this->measure_date));
            $measureHead = PortioningMeasureHead::where([
                'portioning_order_head_id' => $this->order_head_id,
                'portioning_category_id' => $this->portioning_category_id,
                'scheduled_day' => $measureDateFormatted
            ])->first();

            if (!$measureHead) {
                $measureHead = PortioningMeasureHead::create([
                    'portioning_order_head_id' => $this->order_head_id,
                    'portioning_category_id' => $this->portioning_category_id,
                    'scheduled_day' => $measureDateFormatted,
                    'start_time' => now()->format('H:i:s'),
                ]);
            }
            // }

            $data = [
                'item_id' => $this->item_id,
                'measure_id' => $measureHead->id,
                'measure_date' => $measureDateFormatted,
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
                'start_time' => $this->item_process_start_time ?: null,
                'end_time' => $this->item_process_end_time ?: null,
            ];

            // Handle attachment upload
            $attachmentPath = null;
            if ($this->attachment) {
                $attachmentPath = $this->attachment->store('portioning-attachments', 'public');
            } elseif ($this->existingAttachment) {
                $attachmentPath = $this->existingAttachment;
            }
            $data['attachment'] = $attachmentPath;

            // Update or Create
            $measurement = null;
            if ($this->measurement_id) {
                $measurement = PortioningMeasurement::find($this->measurement_id);
                if ($this->listing_table_type === 'item_measure_log') {
                    $measurement->update($data);
                } elseif ($this->listing_table_type === 'item_list') {
                    $measurement = PortioningMeasurement::create($data);
                }
                // $measurement->update($data);
                PortioningMeasurementSample::where('measure_id', $measurement->id)->where('item_id', $this->item_id)->delete();
                session()->flash('success', 'Measurement updated successfully.');
            } else {
                $measurement = PortioningMeasurement::create($data);
                session()->flash('success', 'Measurement saved successfully.');
            }

            // Save samples to portioning_measurement_samples table
            foreach ($this->simple as $index => $sampleValue) {
                if (!empty($sampleValue)) {
                    PortioningMeasurementSample::create([
                        'measure_id' => $measurement->id,
                        'item_id' => $this->item_id,
                        'sample_number' => $index + 1,
                        'sample_value' => $sampleValue,
                    ]);
                }
            }

            PortioningOrderDetail::where('order_detail_id', $this->item_id)->update(['status' => 'In Process']);
            DB::commit();
            $this->resetFormProperties();
            $this->mode = 'edit_mode';
        } catch (Throwable $th) {
            DB::rollback();
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
            'attachment',
            'attachmentPreview',
            'existingAttachment',
            'description',
            'item_process_start_time',
            'item_process_end_time',
        ]);
        $this->simple = [''];
        $this->measurement_id = null;
    }

    public function render()
    {
        $check_start_time = PortioningMeasureHead::where('portioning_order_head_id', $this->order_head_id)
            ->where('portioning_category_id', $this->portioning_category_id)
            ->where('scheduled_day', date('Y-m-d', strtotime($this->measure_date)))
            ->where('order_details_id', $this->item_id)
            ->first();
        // Only override mode if currently in read_only (don't override measure_form_open)
        if ($this->mode === 'read_only' && $check_start_time && $check_start_time->start_time) {
            $this->mode = 'edit_mode';
        }
        if ($check_start_time && $check_start_time->start_time) {
            $this->measure_form_mode = 'edit_mode';
        }

        $data = [];
        if ($this->listing_table_type === 'item_list') {
            $data = PortioningOrderDetail::with('category')
                ->where('order_head_id', $this->order_head_id)
                ->where('portioning_category_id', $this->portioning_category_id)
                //->where('scheduled_day', date('Y-m-d'))
                ->get();
        }

        if ($this->listing_table_type === 'item_measure_log') {
            // $data = PortioningOrderDetail::with('category')
            //     ->where('order_head_id', $this->order_head_id)
            //     ->where('portioning_category_id', $this->portioning_category_id)
            //     ->where('scheduled_day', date('Y-m-d'))
            //     ->get();

            $data = PortioningMeasurement::query()
                ->with('orderDetail')
                ->join('portioning_measure_heads as pmh', 'pmh.id', '=', 'portioning_measurements.measure_id')
                ->where('pmh.portioning_order_head_id', $this->order_head_id)
                ->where('pmh.portioning_category_id', $this->portioning_category_id)
                //->whereDate('pmh.scheduled_day', now())
                ->select('portioning_measurements.*') // duplicate column conflict avoid
                ->get();
            // dd($data);
        }

        $this->portioning_order_data = $data;
        $portioning_category_name = PortioningCategory::where('category_id',  $this->portioning_category_id)->value('category_name');
        return view('livewire.portioning-measure-form', compact('check_start_time', 'portioning_category_name'));
    }

    public function switchTableType($type)
    {
        $this->listing_table_type = $type;
    }
}
