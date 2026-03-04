<?php

namespace App\Livewire;

use App\Models\PurpleCarrotCategory;
use App\Models\PurpleCarrotItemMst;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PurpleCarrotItemMasterModel extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $mode = 'list'; // list, create, edit
    public $selectedCategoryId = '';
    public $categories = [];

    // Form fields
    public $itemId;
    public $category_id;
    public $component_details;
    public $label;
    public $unite;
    public $status = 'active';

    public function mount()
    {
        $this->categories = PurpleCarrotCategory::where('status', 'active')->get();
    }

    // Filter by category
    public function updatedSelectedCategoryId()
    {
        $this->resetPage();
    }

    // Open Create Form
    public function create()
    {
        $this->resetForm();
        $this->mode = 'create';
    }

    // Open Edit Form
    public function edit($itemId)
    {
        $item = PurpleCarrotItemMst::findOrFail($itemId);
        $this->itemId = $item->id;
        $this->category_id = $item->category_id;
        $this->component_details = $item->component_details;
        $this->label = $item->label;
        $this->unite = $item->unite;
        $this->status = $item->status;
        $this->mode = 'edit';
    }

    // Save/Create Item
    public function save()
    {
        $this->validate([
            'category_id' => 'required|exists:purple_carrot_categories,id',
            'label' => 'required|string|max:255',
            'component_details' => 'nullable|string',
            'unite' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
        ]);

        if ($this->mode === 'create') {
            PurpleCarrotItemMst::create($this->formData());
            $this->dispatch('notify', 'Item created successfully!');
        } else {
            PurpleCarrotItemMst::find($this->itemId)->update($this->formData());
            $this->dispatch('notify', 'Item updated successfully!');
        }

        $this->resetForm();
        $this->mode = 'list';
    }

    // Soft Delete (Inactive)
    public function delete($itemId)
    {
        $item = PurpleCarrotItemMst::findOrFail($itemId);
        $item->update(['status' => 'inactive']);
        $this->dispatch('notify', 'Item marked as inactive!');
    }

    // Back to List
    public function cancel()
    {
        $this->resetForm();
        $this->mode = 'list';
    }

    private function formData()
    {
        return [
            'category_id' => $this->category_id,
            'component_details' => $this->component_details,
            'label' => $this->label,
            'unite' => $this->unite,
            'status' => $this->status,
        ];
    }

    private function resetForm()
    {
        $this->itemId = null;
        $this->category_id = '';
        $this->component_details = '';
        $this->label = '';
        $this->unite = '';
        $this->status = 'active';
    }

    public function render()
    {
        $query = PurpleCarrotItemMst::with('category')
            ->when($this->selectedCategoryId, function ($query) {
                $query->where('category_id', $this->selectedCategoryId);
            });

        $items = $query->latest()->paginate(15);

        return view('livewire.purple-carrot-item-master-model', [
            'items' => $items
        ]);
    }
}
