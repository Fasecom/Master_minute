<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class MasterController extends Controller
{
    // Форма добавления мастера
    public function create()
    {
        $skills = Skill::all();
        return view('masters.add', compact('skills'));
    }

    // Сохранение мастера
    public function store(Request $request)
    {
        $cleanPhone = preg_replace('/\D/', '', $request->phone);
        $request->validate([
            'full_name' => ['required', 'regex:/^\S+\s\S+.*$/u'],
            'phone' => [
                'required',
                'regex:/^\+7 \d{3} \d{3} \d{2} \d{2}$/',
                function($attribute, $value, $fail) use ($cleanPhone) {
                    if (\App\Models\User::where('phone', $cleanPhone)->exists()) {
                        $fail('Пользователь с таким телефоном уже существует.');
                    }
                }
            ],
            'password' => ['required', 'string', 'min:6'],
            'work_start_date' => ['required', 'date'],
            'skills' => ['required', 'array', 'min:1'],
        ], [
            'full_name.regex' => 'ФИО должно иметь минимум два слова.',
            'phone.regex' => 'Телефон должен быть в формате +7 000 000 00 00.',
            'skills.min' => 'Выберите хотя бы один навык.',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'full_name' => $request->full_name,
                'phone' => preg_replace('/\D/', '', $request->phone), // сохраняем только цифры
                'password' => Hash::make($request->password),
                'role_id' => 3, // Мастер
                'work_start_date' => $request->work_start_date,
            ]);
            $user->skills()->sync($request->skills);
        });

        return Redirect::route('masters')->with('success', 'Мастер успешно добавлен!');
    }

    // Форма редактирования мастера
    public function edit($id)
    {
        $master = User::findOrFail($id);
        $skills = \App\Models\Skill::all();
        $userSkills = $master->skills()->pluck('skills.id')->toArray();
        return view('masters.edit', compact('master', 'skills', 'userSkills'));
    }

    // Сохранение изменений
    public function update(Request $request, $id)
    {
        $cleanPhone = preg_replace('/\D/', '', $request->phone);
        $request->validate([
            'full_name' => ['required', 'regex:/^\S+\s\S+.*$/u'],
            'phone' => [
                'required',
                'regex:/^\+7 \d{3} \d{3} \d{2} \d{2}$/',
                function($attribute, $value, $fail) use ($cleanPhone, $id) {
                    if (User::where('phone', $cleanPhone)->where('id', '!=', $id)->exists()) {
                        $fail('Пользователь с таким телефоном уже существует.');
                    }
                }
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'work_start_date' => ['required', 'date'],
            'skills' => ['required', 'array', 'min:1'],
        ], [
            'full_name.regex' => 'ФИО должно иметь минимум два слова.',
            'phone.regex' => 'Телефон должен быть в формате +7 000 000 00 00.',
            'skills.min' => 'Выберите хотя бы один навык.',
        ]);

        DB::transaction(function () use ($request, $id, $cleanPhone) {
            $user = User::findOrFail($id);
            $user->full_name = $request->full_name;
            $user->phone = $cleanPhone;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->work_start_date = $request->work_start_date;
            $user->save();
            $user->skills()->sync($request->skills);
        });

        return Redirect::route('masters.info', ['id' => $id])->with('success', 'Данные успешно обновлены!');
    }

    // "Удаление" мастера (ставим дату окончания работы)
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->work_end_date = now();
        $user->save();
        return Redirect::route('masters')->with('success', 'Мастер успешно удалён (дата окончания работы установлена).');
    }

    // Страница редактирования навыков
    public function skillsEdit()
    {
        $skills = Skill::orderBy('name')->get();
        return view('masters.skills-edit', compact('skills'));
    }

    // Сохранение изменений навыков
    public function skillsUpdate(Request $request)
    {
        $request->validate([
            'skills' => 'array',
            'skills.*.id' => 'nullable|integer|exists:skills,id',
            'skills.*.name' => 'required|string|max:255',
        ]);
        // Удаляем отсутствующие
        $ids = collect($request->skills)->pluck('id')->filter()->toArray();
        Skill::whereNotIn('id', $ids)->delete();
        // Обновляем существующие и добавляем новые
        foreach ($request->skills as $skill) {
            if (!empty($skill['id'])) {
                Skill::where('id', $skill['id'])->update(['name' => $skill['name']]);
            } else {
                Skill::create(['name' => $skill['name']]);
            }
        }
        return redirect($request->input('back', route('masters')))->with('success', 'Навыки обновлены!');
    }
} 