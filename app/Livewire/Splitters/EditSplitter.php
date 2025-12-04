<?php

namespace App\Livewire\Splitters;

use Livewire\Component;
use App\Models\Splitter;
use App\Models\Napbox;
use Hashids\Hashids;
use Auth;

class EditSplitter extends Component
{
    public $splitterId;
    public $name;
    public $description;
    public $is_active;
    public $napbox_id;
    public $sector_id;
    public $pon_id;
    public $napboxes;
    public $module=["sector","pon","napbox"];
    

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:splitters,name,' . $this->splitterId,
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ];
    }

    protected $listeners = [
        'splitter-added' => '$refresh',
        'napbox-updated' => 'setNapbox',
    ];

    public function setNapbox($data)
    {
        $this->napbox_id = $data['napbox_id'];
    }

    public function mount($hash)
    {
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $decoded = $hashids->decode($hash);

        abort_if(empty($decoded), 404, 'Invalid Splitter');

        $this->splitterId = $decoded[0];

        $splitter = Splitter::findOrFail($this->splitterId);

        $this->napboxes = Napbox::where('is_active', true)->orderBy('name')->get();
        
        

        $this->name = $splitter->name;
        $this->description = $splitter->description;
        $this->sector_id = $splitter->napbox ? $splitter->napbox->pon->sector->id : null;
        $this->pon_id = $splitter->napbox ? $splitter->napbox->pon->id : null;
        $this->napbox_id = $splitter->napbox ? $splitter->napbox_id :null;
        $this->is_active = (bool) $splitter->is_active;
    }

    public function save()
    {
        if (!Auth::user()->can('edit splitters'))
        {
            abort(403, 'Unauthorized action');
        }
        $this->validate();

        $splitter = Splitter::findOrFail($this->splitterId);

        $splitter->update([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'napbox_id' => $this->napbox_id,
        ]);

        $this->dispatch('show-toast', [
            'message' => 'Splitter updated successfully!',
            'type' => 'success',
            'duration' => 3000,
        ]);
    }

    public function render()
    {
        if (!Auth::user()->can('edit splitters'))
        {
            abort(403, 'You are not allowed to this page');
        }
        return view('livewire.splitters.edit-splitter');
    }
}
