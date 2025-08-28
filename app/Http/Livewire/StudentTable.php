<?php

namespace App\Http\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use App\Models\Student;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

class StudentTable extends DataTableComponent
{
    protected $model = Student::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
{
    $user = Auth::user();

    return Student::query()
        ->whereHas('guardians', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })
        ->when($this->columnSearch['name'] ?? null, function ($query, $name) {
            return $query->where('name', 'like', '%' . $name . '%');
        });
}


    public function columns(): array
    {
        return [
            Column::make("NO.", "id")
                ->sortable(),
            Column::make("Student Name", "name")
                ->sortable()
                ->searchable(),
            Column::make("Identification Card", "ic")
                ->sortable(),
            Column::make("Age", "age")
                ->sortable(),
            Column::make("School Name", "school_id")
                ->sortable()
                ->format(function ($value) {
                    $school = School::find($value);
                    return $school ? $school->name : 'N/A';
                }),
            ButtonGroupColumn::make('Actions')
                ->attributes(function ($row) {
                    return [
                        'class' => 'space-x-2',
                    ];
                })
                ->buttons([
                    LinkColumn::make('Edit')
                        ->title(fn ($row) => 'Edit ')
                        ->location(fn ($row) => route('student.edit', $row))
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-sm btn-primary me-1',
                            ];
                    }),
                    LinkColumn::make('Delete') 
                        ->title(fn ($row) => 'Delete ')
                        ->location(fn ($row) => route('student.delete', $row->id))
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-sm btn-danger',
                                'onclick' => "return confirm('Are you sure you want to delete this student?');",
                            ];
                        }),
                ]),
        ];
    }
}
