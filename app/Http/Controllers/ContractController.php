<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ContractController extends Controller
{
    /**
     * Kiểm tra quyền admin
     */
    private function checkAdmin()
    {
        if (!auth()->check() || !auth()->user()->hasRole('admin')) {
            abort(403, 'Chỉ quản trị viên mới có quyền truy cập.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkAdmin();
        
        $query = Contract::with(['user', 'creator'])->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('contract_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('contract_type')) {
            $query->where('contract_type', $request->contract_type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $contracts = $query->paginate(15)->withQueryString();
        $employees = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['employee', 'part_time']);
        })->orderBy('name')->get();

        return view('contracts.index', compact('contracts', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAdmin();
        $employees = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['employee', 'part_time']);
        })->orderBy('name')->get();

        return view('contracts.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkAdmin();
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'contract_type' => 'required|in:full_time,part_time,intern,temporary',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'salary' => 'required|numeric|min:0',
            'position' => 'required|string|max:255',
            'job_description' => 'nullable|string',
            'benefits' => 'nullable|string',
            'status' => 'required|in:active,expired,terminated,pending',
            'signed_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Tạo số hợp đồng tự động
        $contractNumber = 'HD-' . strtoupper(Str::random(8)) . '-' . date('Y');

        $contract = Contract::create([
            'user_id' => $validated['user_id'],
            'contract_number' => $contractNumber,
            'contract_type' => $validated['contract_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'salary' => $validated['salary'],
            'position' => $validated['position'],
            'job_description' => $validated['job_description'] ?? null,
            'benefits' => $validated['benefits'] ?? null,
            'status' => $validated['status'],
            'signed_date' => $validated['signed_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Tạo hợp đồng thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        $this->checkAdmin();
        $contract->load(['user.profile.department', 'creator']);
        return view('contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        $this->checkAdmin();
        $employees = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['employee', 'part_time']);
        })->orderBy('name')->get();

        return view('contracts.edit', compact('contract', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        $this->checkAdmin();
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'contract_type' => 'required|in:full_time,part_time,intern,temporary',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'salary' => 'required|numeric|min:0',
            'position' => 'required|string|max:255',
            'job_description' => 'nullable|string',
            'benefits' => 'nullable|string',
            'status' => 'required|in:active,expired,terminated,pending',
            'signed_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $contract->update($validated);

        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Cập nhật hợp đồng thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        $this->checkAdmin();
        $contract->delete();

        return redirect()->route('contracts.index')
            ->with('success', 'Xóa hợp đồng thành công!');
    }
}
