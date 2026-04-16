<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PortioningMeasureForm extends Component
{
    public $mode = 'read_only';

    public ?int $table = null;
    public ?string $preop = null;
    public ?int $people_qty = null;
    public ?string $scale = null;
    public bool $showStartTimeModal = false;

    protected function rules(): array
    {
        return [
            'table'      => 'required|integer|between:1,8',
            'preop'      => 'required|in:yes,no',
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
        try{
            dd($this->table, $this->preop, $this->people_qty, $this->scale);
            // আপনার logic এখানে — save to DB, dispatch event, etc.
            // উদাহরণ:
            // Measurement::create([
            //     'table'      => $this->table,
            //     'preop'      => $this->preop,
            //     'people_qty' => $this->people_qty,
            //     'scale'      => $this->scale,
            // ]);

            $this->dispatch('measurementStarted', [
                'table'      => $this->table,
                'preop'      => $this->preop,
                'people_qty' => $this->people_qty,
                'scale'      => $this->scale,
            ]);

            $this->reset();
            $this->dispatch('closeModal');
        }catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            Log::info('message'. $th->getMessage());
        }
    }


    public function render()
    {
        return view('livewire.portioning-measure-form');
    }
}
