<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Announcement::with('creator');
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        // Filter published/unpublished
        if ($request->filled('is_published')) {
            $query->where('is_published', $request->is_published);
        } else {
            // Default: only show published for students
            $user = Auth::user();
            if ($user->isStudent()) {
                $query->where('is_published', true)
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>=', now());
                    });
            }
        }
        
        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $announcements = $query->orderBy('priority', 'desc')
            ->orderBy('published_at', 'desc')
            ->paginate(15);
        
        return view('announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $buildings = \App\Models\Building::with('dormitory')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('announcements.create', compact('buildings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,maintenance,payment,event,emergency,rule',
            'priority' => 'required|in:low,medium,high,urgent',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'is_published' => 'nullable|boolean',
            'send_email' => 'nullable|boolean',
            'send_notification' => 'nullable|boolean',
            'target_audience' => 'nullable|string',
            'building_id' => 'nullable|exists:buildings,id'
        ]);
        
        $validated['created_by'] = Auth::id();
        
        // Handle boolean fields
        $validated['is_published'] = $request->has('is_published') ? true : false;
        $validated['send_email'] = $request->has('send_email') ? true : false;
        $validated['send_notification'] = $request->has('send_notification') ? true : false;
        
        // Auto-publish if published_at is now or past
        if ($request->filled('published_at') && now()->gte($validated['published_at'])) {
            $validated['is_published'] = true;
        }
        
        // Handle target_audience and target_id
        $targetAudience = $request->filled('target_audience') ? $request->target_audience : 'all';
        $targetId = null;
        
        if ($targetAudience === 'building' && $request->filled('building_id')) {
            $targetId = (int)$request->building_id;
        }
        
        // Remove from validated to set separately
        unset($validated['target_audience']);
        unset($validated['building_id']);
        
        // Create announcement
        $announcement = Announcement::create($validated);
        
        // Set target_audience and target_id
        $announcement->target_audience = $targetAudience;
        $announcement->target_id = $targetId;
        $announcement->save();
        
        return redirect()->route('announcements.show', $announcement)
            ->with('success', 'Thông báo đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        $announcement->load('creator');
        
        // Check if user can view this announcement
        $user = Auth::user();
        if ($user->isStudent() && (!$announcement->is_published || ($announcement->expires_at && $announcement->expires_at < now()))) {
            return redirect()->route('announcements.index')
                ->with('error', 'Thông báo không tồn tại hoặc đã hết hạn.');
        }
        
        return view('announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        $buildings = \App\Models\Building::with('dormitory')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('announcements.edit', compact('announcement', 'buildings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,maintenance,payment,event,emergency,rule',
            'priority' => 'required|in:low,medium,high,urgent',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'is_published' => 'nullable|boolean',
            'send_email' => 'nullable|boolean',
            'send_notification' => 'nullable|boolean',
            'target_audience' => 'nullable|string|in:all,students,staff,building,room',
            'building_id' => 'nullable|exists:buildings,id'
        ]);
        
        // Handle boolean fields
        $validated['is_published'] = $request->has('is_published') ? true : false;
        $validated['send_email'] = $request->has('send_email') ? true : false;
        $validated['send_notification'] = $request->has('send_notification') ? true : false;
        
        // Handle target_audience and target_id
        $targetAudience = $request->filled('target_audience') ? $request->target_audience : 'all';
        $targetId = null;
        
        if ($targetAudience === 'building' && $request->filled('building_id')) {
            $targetId = (int)$request->building_id;
        }
        
        // Remove from validated to set separately
        unset($validated['target_audience']);
        unset($validated['building_id']);
        
        // Update announcement
        $announcement->update($validated);
        
        // Set target_audience and target_id
        $announcement->target_audience = $targetAudience;
        $announcement->target_id = $targetId;
        $announcement->save();
        
        return redirect()->route('announcements.show', $announcement)
            ->with('success', 'Thông báo đã được cập nhật.');
    }

    /**
     * Publish announcement
     */
    public function publish(Announcement $announcement)
    {
        $announcement->update([
            'is_published' => true,
            'published_at' => $announcement->published_at ?? now()
        ]);
        
        return redirect()->route('announcements.show', $announcement)
            ->with('success', 'Thông báo đã được xuất bản.');
    }

    /**
     * Unpublish announcement
     */
    public function unpublish(Announcement $announcement)
    {
        $announcement->update(['is_published' => false]);
        
        return redirect()->route('announcements.show', $announcement)
            ->with('success', 'Thông báo đã được gỡ khỏi xuất bản.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        
        return redirect()->route('announcements.index')
            ->with('success', 'Thông báo đã được xóa.');
    }
}
