<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TemporaryStudentCredential;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CredentialController extends Controller
{
    /**
     * Display the credential generation form
     */
    public function showGenerateForm()
    {
        return view('admin.credentials.generate');
    }

    /**
     * Generate new student credentials
     */
    public function generateCredentials(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:50',
            'notes' => 'nullable|string|max:500'
        ]);

        $quantity = $request->input('quantity', 1);
        $notes = $request->input('notes');
        $credentials = [];

        for ($i = 0; $i < $quantity; $i++) {
            $studentId = $this->generateUniqueStudentId();
            $password = $this->generateSecurePassword();

            $credential = TemporaryStudentCredential::create([
                'student_id' => $studentId,
                'password' => $password, // Store as plain text for viewing
                'created_by_admin_id' => Auth::guard('admin')->id(),
                'notes' => $notes
            ]);

            $credentials[] = [
                'id' => $credential->id,
                'student_id' => $studentId,
                'password' => $password, // Store plain password for display
                'notes' => $notes
            ];
        }

        // Store credentials in session for display
        session()->flash('generated_credentials', $credentials);

        return redirect()->route('admin.credentials.show-generated');
    }

    /**
     * Display generated credentials
     */
    public function showGenerated()
    {
        $credentials = session('generated_credentials');

        if (!$credentials) {
            return redirect()->route('admin.credentials.index')
                ->with('error', 'No credentials found to display.');
        }

        return view('admin.credentials.show-generated', compact('credentials'));
    }

    /**
     * List all generated credentials
     */
    public function index(Request $request)
    {
        $query = TemporaryStudentCredential::with(['createdByAdmin', 'usedByStudent'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'used') {
                $query->used();
            } elseif ($request->status === 'unused') {
                $query->unused();
            }
        }

        // Filter by admin
        if ($request->has('admin_id') && $request->admin_id) {
            $query->createdBy($request->admin_id);
        }

        $credentials = $query->paginate(20);

        // Get statistics for the dashboard
        $stats = [
            'total' => TemporaryStudentCredential::count(),
            'used' => TemporaryStudentCredential::where('is_used', true)->count(),
            'unused' => TemporaryStudentCredential::where('is_used', false)->count(),
            'today' => TemporaryStudentCredential::whereDate('created_at', today())->count(),
        ];

        return view('admin.credentials.index', compact('credentials', 'stats'));
    }

    /**
     * Delete unused credentials
     */
    public function destroy(TemporaryStudentCredential $credential)
    {
        if ($credential->is_used) {
            return back()->with('error', 'Cannot delete used credentials.');
        }

        $credential->delete();

        return back()->with('success', 'Credential deleted successfully.');
    }

    /**
     * Bulk delete unused credentials
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'credential_ids' => 'required|array',
            'credential_ids.*' => 'exists:temporary_student_credentials,id'
        ]);

        $deleted = TemporaryStudentCredential::whereIn('id', $request->credential_ids)
            ->unused()
            ->delete();

        return back()->with('success', "Deleted {$deleted} unused credentials.");
    }

    /**
     * Generate a unique student ID
     */
    private function generateUniqueStudentId(): string
    {
        $year = date('Y');

        // Find the highest existing number for the current year
        $existingIds = collect();

        // Check existing students
        $studentIds = Student::where('student_id', 'LIKE', $year . '-%')
            ->pluck('student_id');
        $existingIds = $existingIds->merge($studentIds);

        // Check existing temporary credentials
        $tempIds = TemporaryStudentCredential::where('student_id', 'LIKE', $year . '-%')
            ->pluck('student_id');
        $existingIds = $existingIds->merge($tempIds);

        // Extract numbers and find the highest
        $highestNumber = 0;
        foreach ($existingIds as $id) {
            if (preg_match('/^' . $year . '-(\d+)$/', $id, $matches)) {
                $number = (int)$matches[1];
                if ($number > $highestNumber) {
                    $highestNumber = $number;
                }
            }
        }

        // Generate next sequential number
        $nextNumber = $highestNumber + 1;
        $studentId = $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return $studentId;
    }

    /**
     * Generate a secure password
     */
    private function generateSecurePassword(): string
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*';

        // Ensure at least one character from each category
        $password = '';
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $symbols[random_int(0, strlen($symbols) - 1)];

        // Fill the rest randomly
        $allChars = $uppercase . $lowercase . $numbers . $symbols;
        for ($i = 4; $i < 12; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        // Shuffle the password
        return str_shuffle($password);
    }
}
