<?php

namespace App\Http\Livewire;

use App\Models\Events;
use Livewire\Component;

class Calendar extends Component
{
    public $event_id;
    public $title;
    public $start;
    public $end;

    protected function resetField()
    {
        $this->title = null;
        $this->start = null;
        $this->end = null;
    }

    public function save()
    {
        Events::create([
            'title' => $this->title,
            'start' => $this->start,
            'end' => $this->end,            
        ]);

        $this->resetField();
        $this->dispatchBrowserEvent('closeModalCreate', ['close' => true]);
        $this->dispatchBrowserEvent('ModalCreateMessageSuccess', ['success' => true]);
        $this->dispatchBrowserEvent('refreshEventCalendar', ['refresh' => true]);
    }

    public function delete()
    {
        Events::findOrFail($this->event_id)->delete();
        $this->dispatchBrowserEvent('closeModalEdit', ['close' => true]);
        $this->dispatchBrowserEvent('ModalDeleteMessageSuccess', ['success' => true]);
        $this->dispatchBrowserEvent('refreshEventCalendar', ['refresh' => true]);
    }

    public function update()
    {
        Events::findOrFail($this->event_id)->update([
            'title' => $this->title,
            'start' => $this->start,
            'end' => $this->end,
        ]);

        $this->dispatchBrowserEvent('closeModalEdit', ['close' => true]);
        $this->dispatchBrowserEvent('ModalEditMessageSuccess', ['success' => true]);
        $this->dispatchBrowserEvent('refreshEventCalendar', ['refresh' => true]);
    }

    public function render()
    {
        return view('livewire.calendar');
    }
}
