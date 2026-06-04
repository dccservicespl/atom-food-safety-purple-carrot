<?php

namespace App\Livewire;

use App\Models\LabelInspectionItemMst;
use Livewire\Component;

class LabelInspectionItemMaster extends Component
{
    public $items;
    public $item_id, $item_name, $weight, $item_unit, $status = 'Active';
    public $mode = 'list';

    public function render(){
       $this->items = LabelInspectionItemMst::orderBy('item_name', 'ASC')->orderBy('status','ASC')->get();
        return view('livewire.label-inspection-item-master');
    }
     public function create()
    {
        $this->resetForm();
        $this->mode = 'create';
    }

    public function edit($id)
    {
        $item = LabelInspectionItemMst::findOrFail($id);
        $this->item_id = $item->id;
        $this->item_name = $item->item_name;
        $this->weight = $item->weight;
        $this->item_unit = $item->item_unit;
        $this->status = $item->status;
        $this->mode = 'edit';
    }

    public function store()
    {
        $this->validate([
            'item_name' => 'required|unique:label_inspection_item_msts,item_name,'.$this->item_id,
            'weight' => 'required|numeric',
            'item_unit' => 'required',
        ]);

        if ($this->item_id) {
            LabelInspectionItemMst::find($this->item_id)->update([
                'item_name' => $this->item_name,
                'weight' => $this->weight,
                'item_unit' => $this->item_unit,
                'status' => $this->status,
            ]);
            session()->flash('message', 'Item updated successfully.');
        } else {
            LabelInspectionItemMst::create([
                'item_name' => $this->item_name,
                'weight' => $this->weight,
                'item_unit' => $this->item_unit,
                'status' => $this->status,
            ]);
            session()->flash('message', 'Item created successfully.');
        }

        $this->resetForm();
        $this->mode = 'list';
    }

    public function resetForm()
    {
        $this->item_id = null;
        $this->item_name = '';
        $this->weight = '';
        $this->item_unit = '';
        $this->status = 'Active';
    }

    public function backToList()
    {
        $this->resetForm();
        $this->mode = 'list';
    }
}
