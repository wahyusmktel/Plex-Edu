<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\ForumTopic;
use App\Models\ForumPost;
use App\Models\ForumBookmark;
use App\Models\ForumMute;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumApiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $schoolId = $user->school_id;
        $classId = $user->siswa?->kelas_id;

        $forums = Forum::withoutGlobalScope('school')
            ->with(['creator' => function($q) {
                $q->withoutGlobalScope('school');
            }])
            ->where('is_active', true)
            ->where(function ($query) use ($schoolId, $classId) {
                // Access rules based on visibility
                $query->where('visibility', 'all')
                    ->orWhere(function ($q) use ($schoolId) {
                        $q->where('visibility', 'school')
                          ->where('school_id', $schoolId);
                    })
                    ->orWhere(function ($q) use ($classId) {
                        $q->where('visibility', 'class')
                          ->where('class_id', $classId);
                    })
                    ->orWhere(function ($q) use ($schoolId) {
                        $q->where('visibility', 'specific_schools')
                          ->whereHas('allowedSchools', function ($sub) use ($schoolId) {
                              $sub->where('school_id', $schoolId);
                          });
                    });
            })
            ->withCount('topics')
            ->latest()
            ->get()
            ->map(function ($forum) {
                return [
                    'id' => $forum->id,
                    'title' => $forum->title,
                    'description' => $forum->description,
                    'creator' => $forum->creator?->name ?? 'N/A',
                    'visibility' => $forum->visibility,
                    'topics_count' => $forum->topics_count,
                    'created_at' => $forum->created_at->format('d M Y'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $forums
        ]);
    }

    public function show($id)
    {
        $user = Auth::user();
        
        $forum = Forum::withoutGlobalScope('school')
            ->with(['creator'])
            ->findOrFail($id);

        // Visibility check
        if (!$this->canAccessForum($forum, $user)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $topics = ForumTopic::withoutGlobalScope('school')
            ->where('forum_id', $id)
            ->with(['user'])
            ->withCount('posts')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($topic) {
                return [
                    'id' => $topic->id,
                    'title' => $topic->title,
                    'content' => $topic->content,
                    'user' => $topic->user?->name ?? 'Anonim',
                    'posts_count' => $topic->posts_count,
                    'is_pinned' => $topic->is_pinned,
                    'is_locked' => $topic->is_locked,
                    'status' => $topic->status,
                    'created_at' => $topic->created_at->format('d M Y H:i'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'forum' => [
                    'id' => $forum->id,
                    'title' => $forum->title,
                    'description' => $forum->description,
                    'creator' => $forum->creator?->name ?? 'N/A',
                ],
                'topics' => $topics
            ]
        ]);
    }

    public function showTopic($id)
    {
        $user = Auth::user();

        $topic = ForumTopic::withoutGlobalScope('school')
            ->with(['user', 'forum'])
            ->findOrFail($id);

        // Visibility check
        if (!$this->canAccessForum($topic->forum, $user)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $posts = ForumPost::where('topic_id', $id)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'content' => $post->content,
                    'user' => $post->user?->name ?? 'Anonim',
                    'created_at' => $post->created_at->format('d M Y H:i'),
                    'replies' => $post->replies->map(function ($reply) {
                        return [
                            'id' => $reply->id,
                            'content' => $reply->content,
                            'user' => $reply->user?->name ?? 'Anonim',
                            'created_at' => $reply->created_at->format('d M Y H:i'),
                        ];
                    }),
                ];
            });

        $isBookmarked = ForumBookmark::where('user_id', $user->id)->where('topic_id', $id)->exists();
        $isMuted = ForumMute::where('user_id', $user->id)->where('topic_id', $id)->exists();

        return response()->json([
            'success' => true,
            'data' => [
                'topic' => [
                    'id' => $topic->id,
                    'title' => $topic->title,
                    'content' => $topic->content,
                    'user' => $topic->user?->name ?? 'Anonim',
                    'is_locked' => $topic->is_locked,
                    'status' => $topic->status,
                    'created_at' => $topic->created_at->format('d M Y H:i'),
                ],
                'posts' => $posts,
                'is_bookmarked' => $isBookmarked,
                'is_muted' => $isMuted,
            ]
        ]);
    }

    public function storePost(Request $request, $topic_id)
    {
        $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:forum_posts,id',
        ]);

        $user = Auth::user();

        if ($user->is_suspended_from_forum) {
            return response()->json(['success' => false, 'message' => 'Anda ditangguhkan dari forum.'], 403);
        }

        $topic = ForumTopic::withoutGlobalScope('school')->findOrFail($topic_id);

        if ($topic->is_locked || $topic->status === 'suspended') {
            return response()->json(['success' => false, 'message' => 'Topik ini ditutup atau ditangguhkan.'], 403);
        }

        // Visibility check
        if (!$this->canAccessForum($topic->forum, $user)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $post = ForumPost::create([
            'topic_id' => $topic_id,
            'user_id' => $user->id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        // Send notification
        $recipient = null;
        $title = "";
        $message = "";

        if ($post->parent_id) {
            $parentPost = ForumPost::find($post->parent_id);
            if ($parentPost && $parentPost->user_id !== $user->id) {
                $recipient = $parentPost->user;
                $title = "Komentar Anda dibalas";
                $message = "{$user->name} membalas komentar Anda di topik: {$topic->title}";
            }
        } elseif ($topic->user_id !== $user->id) {
            $recipient = $topic->user;
            $title = "Ada tanggapan di topik Anda";
            $message = "{$user->name} menanggapi topik: {$topic->title}";
        }

        if ($recipient) {
            $recipient->notify(new GeneralNotification([
                'type' => 'forum',
                'title' => $title,
                'message' => $message,
                'action_type' => 'forum_topic',
                'action_id' => $topic->id
            ]));
        }

        return response()->json([
            'success' => true,
            'message' => 'Balasan berhasil dikirim.'
        ]);
    }

    private function canAccessForum(Forum $forum, $user): bool
    {
        if ($forum->visibility === 'all') {
            return true;
        }

        if ($forum->visibility === 'school' && $forum->school_id === $user->school_id) {
            return true;
        }

        if ($forum->visibility === 'class' && $forum->class_id === $user->siswa?->kelas_id) {
            return true;
        }

        if ($forum->visibility === 'specific_schools') {
            return $forum->allowedSchools()->where('school_id', $user->school_id)->exists();
        }

        return false;
    }
}
