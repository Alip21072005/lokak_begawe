<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class KelolalowonganController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        $jobs = Lowongan::with('mitra')
            ->when($search, function ($query, $search) {
                $query->where('judul_lowongan', 'like', "%{$search}%")
                    ->orWhereHas('mitra', function ($q) use ($search) {
                        $q->where('nama_mitra', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->get();

        return Inertia::render('Admin/Kelolalowongan', [
            'auth' => ['user' => $user],
            'jobs' => $jobs,
            'filters' => ['search' => $search]
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected,pending'
        ]);

        $lowongan = Lowongan::findOrFail($id);
        $lowongan->update([
            'status_lowongan' => $request->status
        ]);

        return redirect()->back();
    }

    public function destroy($id)
    {
        $lowongan = Lowongan::findOrFail($id);
        $lowongan->delete();

        return redirect()->back();
    }
}