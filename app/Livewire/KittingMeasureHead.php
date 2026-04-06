<?php

namespace App\Livewire;

use Livewire\Component;

class KittingMeasureHead extends Component
{
    public $selectedDate;
    public $mode = 'list';
    public $openKittingMeasureModal = false;

    public function mount(){
        $this->selectedDate = date('Y-m-d');
    }

    public function createMeasureDate(){
        $this->openKittingMeasureModal = true;
    }

    public function render()
    {
        return view('livewire.kitting-measure-head');
    }
}
