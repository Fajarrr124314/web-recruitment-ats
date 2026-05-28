<?php

namespace App\Livewire\Hrd\Components;

use Livewire\Component;

class DashboardCalendar extends Component
{
    public int $calendarYear;
    public int $calendarMonth;
    public string $newNoteText = '';
    public string $selectedDate = '';
    public array $notes = [];
    public string $floatingReminder = '';
    public bool $showCalendar = false;

    public function mount()
    {
        $this->calendarYear = (int) now()->year;
        $this->calendarMonth = (int) now()->month;
        $this->loadCalendarNotes();
        $this->checkTodayNotes();
    }

    public function loadCalendarNotes()
    {
        $notesVal = \App\Models\RecruitmentSetting::getValue('calendar_notes', '[]');
        $this->notes = json_decode($notesVal, true) ?: [];
    }

    public function saveCalendarNotes()
    {
        \App\Models\RecruitmentSetting::setValue('calendar_notes', json_encode($this->notes));
    }

    public function selectCalendarDate($date)
    {
        $this->selectedDate = $date;
        $this->newNoteText = '';
    }

    public function addCalendarNote()
    {
        if (!$this->selectedDate) return;
        
        if (empty($this->newNoteText)) return;

        if (!isset($this->notes[$this->selectedDate])) {
            $this->notes[$this->selectedDate] = [];
        }
        $this->notes[$this->selectedDate][] = $this->newNoteText;
        $this->saveCalendarNotes();
        
        $this->newNoteText = '';
        $this->floatingReminder = "Catatan berhasil ditambahkan pada tanggal " . $this->selectedDate;
    }

    public function removeCalendarNote($date, $index)
    {
        if (isset($this->notes[$date][$index])) {
            unset($this->notes[$date][$index]);
            $this->notes[$date] = array_values($this->notes[$date]);
            if (empty($this->notes[$date])) {
                unset($this->notes[$date]);
            }
            $this->saveCalendarNotes();
        }
    }

    public function prevMonth()
    {
        $this->calendarMonth--;
        if ($this->calendarMonth < 1) {
            $this->calendarMonth = 12;
            $this->calendarYear--;
        }
    }

    public function nextMonth()
    {
        $this->calendarMonth++;
        if ($this->calendarMonth > 12) {
            $this->calendarMonth = 1;
            $this->calendarYear++;
        }
    }

    public function checkTodayNotes()
    {
        $today = now()->format('Y-m-d');
        if (isset($this->notes[$today]) && count($this->notes[$today]) > 0) {
            $this->floatingReminder = "Pengingat Hari Ini: " . implode(', ', $this->notes[$today]);
        }
    }

    public function render()
    {
        return view('livewire.hrd.components.dashboard-calendar');
    }
}
