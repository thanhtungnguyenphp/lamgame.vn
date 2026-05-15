/**
 * Forum Real-time — Laravel Echo integration
 * Requires: laravel-echo, pusher-js (or reverb client)
 */
document.addEventListener('DOMContentLoaded', () => {
    if (typeof window.Echo === 'undefined') return;

    const postId = document.querySelector('[data-post-id]')?.dataset.postId;
    if (!postId) return;

    // Listen for new comments
    window.Echo.private(`forum.post.${postId}`)
        .listen('.comment.new', (e) => {
            const container = document.getElementById('comments-list');
            if (!container) return;
            const el = document.createElement('div');
            el.className = 'comment-item new-comment';
            el.innerHTML = `<strong>${e.comment.author}</strong>: ${e.comment.content}`;
            container.prepend(el);
        })
        .listen('.comment.voted', (e) => {
            const voteEl = document.querySelector(`[data-comment-id="${e.commentId}"] .vote-count`);
            if (voteEl) voteEl.textContent = e.voteCount;
        })
        .listenForWhisper('typing', (e) => {
            const indicator = document.getElementById('typing-indicator');
            if (indicator) {
                indicator.textContent = `${e.userName} đang viết...`;
                indicator.classList.remove('hidden');
                clearTimeout(window._typingTimeout);
                window._typingTimeout = setTimeout(() => indicator.classList.add('hidden'), 3000);
            }
        });

    // Notification bell (user channel)
    const userId = document.querySelector('[data-user-id]')?.dataset.userId;
    if (userId) {
        window.Echo.private(`user.${userId}`)
            .listen('.notification.new', (e) => {
                const badge = document.getElementById('notification-badge');
                if (badge) {
                    const count = parseInt(badge.textContent || '0') + 1;
                    badge.textContent = count;
                    badge.classList.remove('hidden');
                }
            });
    }

    // Send typing whisper
    const commentInput = document.getElementById('comment-input');
    if (commentInput) {
        let lastTyping = 0;
        commentInput.addEventListener('input', () => {
            if (Date.now() - lastTyping > 2000) {
                lastTyping = Date.now();
                window.Echo.private(`forum.post.${postId}`).whisper('typing', {
                    userName: document.querySelector('[data-user-name]')?.dataset.userName || 'User'
                });
            }
        });
    }
});
