<?php

namespace App\Http\Controllers\Ngo;

use App\Http\Controllers\Controller;
use App\Models\Drive;
use App\Models\NgoDriveSupport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriveSupportController extends Controller
{
    /**
     * Toggle support for a drive
     */
    public function toggle(Request $request, Drive $drive): RedirectResponse
    {
        $user = Auth::user();

        // Find existing support record
        $support = NgoDriveSupport::where('user_id', $user->id)
            ->where('drive_id', $drive->id)
            ->first();

        if ($support) {
            // Toggle existing support
            $support->toggle();
            $message = $support->is_active 
                ? 'You are now supporting this drive.' 
                : 'You have withdrawn your support for this drive.';
        } else {
            // Create new support
            NgoDriveSupport::create([
                'user_id' => $user->id,
                'drive_id' => $drive->id,
                'is_active' => true,
            ]);
            $message = 'You are now supporting this drive.';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * List drives the NGO supports
     */
    public function index(): \Illuminate\View\View
    {
        $user = Auth::user();
        
        $supportedDrives = $user->supportedDrives()
            ->with('driveItems')
            ->latest()
            ->paginate(10);

        return view('ngo.supports.index', compact('supportedDrives'));
    }
}
