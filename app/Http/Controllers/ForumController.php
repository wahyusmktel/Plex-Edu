<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\ForumTopic;
use App\Models\ForumPost;
use App\Models\ForumBookmark;
use App\Models\ForumMute;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index()
    {
        $forums = Forum::withCount('topics')->where('is_active', true)->get();
        return view('forum.index', compact('forums'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'visibility' => 'required|in:all,school,class,specific_schools',
            'class_id' => 'nullable|required_if:visibility,class|exists:kelas,id',
            'allowed_schools' => 'nullable|required_if:visibility,specific_schools|array',
            'allowed_schools.*' => 'exists:schools,id',
        ]);

        if (!in_array(Auth::user()->role, ['guru', 'admin', 'dinas'])) {
            return back()->with('error', 'Hanya guru, admin, atau dinas yang dapat membuat forum.');
        }

        $forum = Forum::create([
            'created_by' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'visibility' => $request->visibility,
            'class_id' => $request->visibility === 'class' ? $request->class_id : null,
        ]);

        if ($request->visibility === 'specific_schools' && $request->allowed_schools) {
            $forum->allowedSchools()->attach($request->allowed_schools);
        }

        return back()->with('success', 'Forum berhasil dibuat.');
    }

    public function show($id)
    {
        $forum = Forum::findOrFail($id);
        $topics = ForumTopic::where('forum_id', $id)
            ->with(['user'])
            ->withCount('posts')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('forum.show', compact('forum', 'topics'));
    }

    public function storeTopic(Request $request, $forum_id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if (Auth::user()->is_suspended_from_forum) {
            return back()->with('error', 'Anda ditangguhkan dari forum.');
        }

        ForumTopic::create([
            'forum_id' => $forum_id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Topik berhasil dibuat.');
    }

    public function showTopic($id)
    {
        $topic = ForumTopic::with(['user', 'forum'])->findOrFail($id);
        $posts = ForumPost::where('topic_id', $id)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->orderBy('created_at', 'asc')
            ->get();

        $isBookmarked = ForumBookmark::where('user_id', Auth::id())->where('topic_id', $id)->exists();
        $isMuted = ForumMute::where('user_id', Auth::id())->where('topic_id', $id)->exists();

        return view('forum.topic', compact('topic', 'posts', 'isBookmarked', 'isMuted'));
    }

    public function storePost(Request $request, $topic_id)
    {
        $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:forum_posts,id',
        ]);

        $topic = ForumTopic::findOrFail($topic_id);
        if ($topic->is_locked || $topic->status === 'suspended') {
            return back()->with('error', 'Topik ini ditutup atau ditangguhkan.');
        }

        if (Auth::user()->is_suspended_from_forum) {
            return back()->with('error', 'Anda ditangguhkan dari forum.');
        }

        ForumPost::create([
            'topic_id' => $topic_id,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function toggleBookmark($topic_id)
    {
        $bookmark = ForumBookmark::where('user_id', Auth::id())->where('topic_id', $topic_id)->first();
        if ($bookmark) {
            $bookmark->delete();
            return back()->with('success', 'Bookmark dihapus.');
        }

        ForumBookmark::create([
            'user_id' => Auth::id(),
            'topic_id' => $topic_id,
        ]);

        return back()->with('success', 'Topik di-bookmark.');
    }

    public function toggleMute($topic_id)
    {
        $mute = ForumMute::where('user_id', Auth::id())->where('topic_id', $topic_id)->first();
        if ($mute) {
            $mute->delete();
            return back()->with('success', 'Mute dihapus.');
        }

        ForumMute::create([
            'user_id' => Auth::id(),
            'topic_id' => $topic_id,
        ]);

        return back()->with('success', 'Topik di-mute.');
    }

    public function moderateTopic(Request $request, $id)
    {
        if (!in_array(Auth::user()->role, ['guru', 'admin', 'dinas'])) {
            return back()->with('error', 'Unauthorized.');
        }

        $topic = ForumTopic::findOrFail($id);
        $action = $request->action; // pin, lock, suspend

        if ($action === 'pin') {
            $topic->update(['is_pinned' => !$topic->is_pinned]);
        } elseif ($action === 'lock') {
            $topic->update(['is_locked' => !$topic->is_locked]);
        } elseif ($action === 'suspend') {
            $topic->update(['status' => $topic->status === 'active' ? 'suspended' : 'active']);
        }

        return back()->with('success', 'Aksi berhasil dilakukan.');
    }

    public function suspendUser($user_id)
    {
        if (!in_array(Auth::user()->role, ['guru', 'admin', 'dinas'])) {
            return back()->with('error', 'Unauthorized.');
        }

        $user = User::findOrFail($user_id);
        $user->update(['is_suspended_from_forum' => !$user->is_suspended_from_forum]);

        return back()->with('success', $user->is_suspended_from_forum ? 'User ditangguhkan.' : 'Tangguhan user dilepas.');
    }
}
