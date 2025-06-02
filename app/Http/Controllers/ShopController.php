<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Workshop::query();

        // Не показываем мастерские с датой закрытия
        $query->whereNull('close_date');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $shops = $query->get();

        return view('shops.index', compact('shops'));
    }

    public function add()
    {
        $services = \App\Models\Service::all();
        return view('shops.add', compact('services'));
    }

    public function info($id)
    {
        $shop = Workshop::findOrFail($id);
        $services = $shop->services()->pluck('name');
        $currentEmployee = $shop->currentEmployee();
        $openDate = $shop->open_date ? date('d.m.Y', strtotime($shop->open_date)) : '-';
        $openTime = $shop->open_time ? date('H:i', strtotime($shop->open_time)) : null;
        $closeTime = $shop->close_time ? date('H:i', strtotime($shop->close_time)) : null;
        $workTime = ($openTime && $closeTime) ? ($openTime . '-' . $closeTime) : '-';
        return view('shops.info', compact('shop', 'services', 'currentEmployee', 'openDate', 'workTime'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'open_date' => 'required|date',
            'open_time' => 'required',
            'close_time' => 'required',
            'address' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id',
        ], [
            'services.required' => 'Выберите хотя бы одну услугу.'
        ]);

        // Оставляем только цифры
        $phone = $validated['phone'] ? preg_replace('/\D/', '', $validated['phone']) : null;
        // Если телефон не заполнен или только +7, не сохраняем
        if (empty($phone) || $phone === '7') {
            $phone = null;
        } elseif (strlen($phone) < 11) {
            return back()->withErrors(['phone' => 'Введите корректный номер телефона'])->withInput();
        }
        // Если email пустой, сохраняем null
        $email = isset($validated['email']) && $validated['email'] !== '' ? $validated['email'] : null;

        $shop = Workshop::create([
            'name' => $validated['name'],
            'open_date' => $validated['open_date'],
            'open_time' => $validated['open_time'],
            'close_time' => $validated['close_time'],
            'address' => $validated['address'],
            'email' => $email,
            'phone' => $phone,
        ]);

        $shop->services()->sync($validated['services']);

        return redirect()->route('shops')->with('success', 'Торговая точка успешно добавлена!');
    }

    public function edit($id)
    {
        $shop = Workshop::findOrFail($id);
        $services = \App\Models\Service::all();
        $shopServices = $shop->services()->pluck('services.id')->toArray();
        return view('shops.edit', compact('shop', 'services', 'shopServices'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'open_date' => 'required|date',
            'open_time' => 'required',
            'close_time' => 'required',
            'address' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id',
        ], [
            'services.required' => 'Выберите хотя бы одну услугу.'
        ]);

        $phone = $validated['phone'] ? preg_replace('/\D/', '', $validated['phone']) : null;
        if (empty($phone) || $phone === '7') {
            $phone = null;
        } elseif (strlen($phone) < 11) {
            return back()->withErrors(['phone' => 'Введите корректный номер телефона'])->withInput();
        }
        $email = isset($validated['email']) && $validated['email'] !== '' ? $validated['email'] : null;

        $shop = Workshop::findOrFail($id);
        $shop->update([
            'name' => $validated['name'],
            'open_date' => $validated['open_date'],
            'open_time' => $validated['open_time'],
            'close_time' => $validated['close_time'],
            'address' => $validated['address'],
            'email' => $email,
            'phone' => $phone,
        ]);
        $shop->services()->sync($validated['services']);

        return redirect()->route('shops.info', $shop->id)->with('success', 'Торговая точка успешно обновлена!');
    }

    public function delete($id)
    {
        $shop = Workshop::findOrFail($id);
        $shop->close_date = now();
        $shop->save();
        return redirect()->route('shops')->with('success', 'Торговая точка успешно удалена (закрыта).');
    }

    public function servicesEdit(Request $request)
    {
        $services = \App\Models\Service::orderBy('name')->get();
        return view('shops.services-edit', compact('services'));
    }

    public function servicesUpdate(Request $request)
    {
        $request->validate([
            'services' => 'array',
            'services.*.id' => 'nullable|integer|exists:services,id',
            'services.*.name' => 'required|string|max:255',
        ]);
        // Удаляем отсутствующие
        $ids = collect($request->services)->pluck('id')->filter()->toArray();
        \App\Models\Service::whereNotIn('id', $ids)->delete();
        // Обновляем существующие и добавляем новые
        foreach ($request->services as $service) {
            if (!empty($service['id'])) {
                \App\Models\Service::where('id', $service['id'])->update(['name' => $service['name']]);
            } else {
                \App\Models\Service::create(['name' => $service['name']]);
            }
        }
        return redirect($request->input('back', route('shops')))->with('success', 'Услуги обновлены!');
    }
} 