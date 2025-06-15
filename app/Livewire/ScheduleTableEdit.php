<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\WorkingShift;
use App\Models\Workshop;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ScheduleTableEdit extends Component
{
    public string $monthYear;

    public int $currentPage = 1;

    private int $workshopsPerPage = 7;

    public array $changes = []; // cellKey => userId

    protected $listeners = ['monthYearUpdated' => 'onMonthYear', 'saveShifts' => 'saveShifts', 'cardAdded' => 'onCardAdded', 'cardRemoved' => 'onCardRemoved', 'cardMoved' => 'onCardMoved'];

    public function mount(): void
    {
        $this->monthYear = session('schedule.edit.monthYear', session('schedule.monthYear', now()->format('Y-m')));
        $this->currentPage = session('schedule.edit.currentPage', 1);
        $this->changes = session('schedule.edit.changes', []);
    }

    public function updated($propertyName): void
    {
        $baseProperty = explode('.', $propertyName)[0];
        if (property_exists($this, $baseProperty)) {
            session(['schedule.edit.' . $baseProperty => $this->{$baseProperty}]);
        }
    }

    public function onMonthYear(string $monthYear): void
    {
        $this->monthYear = $monthYear;
        $this->currentPage = 1;
    }

    public function goPrevPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function goNextPage(): void
    {
        if ($this->currentPage < $this->getTotalPages()) {
            $this->currentPage++;
        }
    }

    private function getTotalPages(): int
    {
        [$totalWorkshops] = $this->calculateWorkshops();
        return max(1, (int)ceil($totalWorkshops / $this->workshopsPerPage));
    }

    private function calculateWorkshops(): array
    {
        $startDate = Carbon::parse($this->monthYear)->startOfMonth();
        $endDate = Carbon::parse($this->monthYear)->endOfMonth();

        // Все открытые мастерские
        $workshopsQuery = Workshop::whereNull('close_date');

        $totalWorkshops = $workshopsQuery->count();
        $offset = ($this->currentPage - 1) * $this->workshopsPerPage;

        $workshops = $workshopsQuery->skip($offset)->take($this->workshopsPerPage)->get();

        return [$totalWorkshops, $workshops];
    }

    private function formatFullName(string $fullName): string
    {
        $parts = explode(' ', $fullName);
        if (count($parts) >= 3) {
            return $parts[0] . ' ' . mb_substr($parts[1], 0, 1) . '. ' . mb_substr($parts[2], 0, 1) . '.';
        }
        return $fullName;
    }

    private function getShifts($displayedWorkshopIds, Carbon $startDate, Carbon $endDate)
    {
        return WorkingShift::with(['user', 'workshop'])
            ->whereBetween('date', [$startDate, $endDate])
            ->whereIn('workshop_id', $displayedWorkshopIds)
            ->get()
            ->map(function ($shift) {
                $shift->user->formatted_name = $this->formatFullName($shift->user->full_name);
                return $shift;
            })
            ->groupBy('workshop_id');
    }

    private function getDays(Carbon $startDate, Carbon $endDate): array
    {
        $days = [];
        $currentDate = $startDate->copy();
        $weekDays = ['вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб'];

        while ($currentDate <= $endDate) {
            $days[] = [
                'day' => $currentDate->day,
                'short' => $weekDays[$currentDate->dayOfWeek],
                'isWeekend' => $currentDate->isWeekend(),
                'date' => $currentDate->format('Y-m-d'),
            ];
            $currentDate->addDay();
        }

        return $days;
    }

    public function onCardAdded(?int $userId = null, ?string $cell = null): void
    {
        // Совместимость с старым вызовом, когда приходил массив-пэйлоад
        if(is_array($userId)){
            $payload = $userId;
            $cell = $payload['cell'] ?? null;
            $userId = $payload['userId'] ?? null;
        }
        if(!$cell || !$userId) return;
        // Убираем предыдущую ячейку для этого userId
        foreach($this->changes as $key => $uid){
            if($uid == $userId){ unset($this->changes[$key]); }
        }
        $this->changes[$cell] = (int)$userId;
        session(['schedule.edit.changes' => $this->changes]);
    }

    public function onCardRemoved(?int $userId = null, ?string $cell = null): void
    {
        if(is_array($userId)){
            $payload = $userId;
            $cell = $payload['cell'] ?? null;
            $userId = $payload['userId'] ?? null;
        }
        if($cell && isset($this->changes[$cell])) unset($this->changes[$cell]);
        if(!$cell && $userId){
            // удалить по userId
            foreach($this->changes as $key => $uid){ if($uid == $userId) unset($this->changes[$key]); }
        }
        session(['schedule.edit.changes' => $this->changes]);
    }

    public function onCardMoved(?int $userId = null, ?string $from = null, ?string $to = null): void
    {
        if(is_array($userId)){
            $payload = $userId;
            $from = $payload['from'] ?? null;
            $to = $payload['to'] ?? null;
            $userId = $payload['userId'] ?? null;
        }
        if($from){
            $this->changes[$from] = null; // помечаем удаление
        }
        if($to && $userId){
            $this->changes[$to] = (int)$userId;
        }
        session(['schedule.edit.changes' => $this->changes]);
    }

    public function saveShifts()
    {
        DB::beginTransaction();
        try {
            foreach($this->changes as $cellKey => $uid){
                [$workshopId,$date] = explode('_',$cellKey);
                $date = Carbon::parse($date)->startOfDay();

                // удаление
                if($uid === null){
                    WorkingShift::where('workshop_id',$workshopId)
                        ->whereDate('date',$date)
                        ->delete();
                    continue;
                }

                // create or update
                $shift = WorkingShift::firstOrNew([
                    'workshop_id'=>$workshopId,
                    'date'=>$date,
                ]);
                $shift->user_id = $uid;
                // если новая смена – выручку = 0
                if(!$shift->exists){
                    $shift->cash_revenue = 0;
                    $shift->cashless_revenue = 0;
                }
                $shift->save();
            }
            DB::commit();

            // Очистить локальные изменения
            $this->changes = [];
            session()->forget(['schedule.edit.changes']);

            session()->flash('success','График сохранён');
        } catch(\Throwable $e){
            DB::rollBack();
            session()->flash('error','Не удалось сохранить изменения');
        }

        return redirect()->route('schedule');
    }

    public function render()
    {
        $startDate = Carbon::parse($this->monthYear)->startOfMonth();
        $endDate = Carbon::parse($this->monthYear)->endOfMonth();

        [$totalWorkshops, $workshops] = $this->calculateWorkshops();
        $totalPages = max(1, (int)ceil($totalWorkshops / $this->workshopsPerPage));
        $this->currentPage = min($this->currentPage, $totalPages);

        $displayedWorkshopIds = $workshops->pluck('id')->toArray();
        $shifts = $this->getShifts($displayedWorkshopIds, $startDate, $endDate);

        $days = $this->getDays($startDate, $endDate);

        // overlay changes
        foreach($this->changes as $cellKey => $uid){
            [$workshopId,$date] = explode('_',$cellKey);
            if($uid===null){
                // помечено как удаление
                if(isset($shifts[$workshopId])){
                    $shifts[$workshopId] = $shifts[$workshopId]->reject(fn($s)=>$s->date==$date);
                }
                continue;
            }
            $dummy = new \stdClass();
            $dummy->user_id = $uid;
            $dummy->date = $date;
            $dummy->user = User::find($uid);
            if(!$dummy->user) continue;
            $dummy->user->formatted_name = $this->formatFullName($dummy->user->full_name);
            if(!isset($shifts[$workshopId])) $shifts[$workshopId]=collect();
            // удалить возможное существующее
            $shifts[$workshopId] = $shifts[$workshopId]->reject(fn($s)=>$s->user_id==$uid && $s->date==$date);
            $shifts[$workshopId]->push($dummy);
        }

        return view('livewire.schedule-table-edit', [
            'workshops' => $workshops,
            'shifts' => $shifts,
            'days' => $days,
            'totalPages' => $totalPages,
            'currentPage' => $this->currentPage,
        ]);
    }
}
