<!DOCTYPE html>
<html>
<head>
    <title>Explore Posts</title>
    <style>
        .post { margin-bottom: 20px; border: 1px solid #ccc; padding: 10px; }
    </style>
</head>
<body>
    <h1>Explore Posts</h1>
    <a href="?page=dashboard">Dashboard</a>
    
    <div class="posts">
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h3><?= htmlspecialchars($post['title']) ?></h3>
                <p><?= htmlspecialchars($post['content']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
<?php foreach ($posts as $post): ?>
    <div class="post">
        <h3><?= htmlspecialchars($post['title']) ?></h3>
        <p><?= htmlspecialchars($post['content']) ?></p>
        <?php if ($post['image_path']): ?>
            <img src="<?= $post['image_path'] ?>" alt="Post Image" style="max-width: 200px;">
        <?php endif; ?>
    </div>
<?php endforeach; ?>
<script>
    let page = 1;
    const postsContainer = document.querySelector('.posts');

    function loadPosts() {
        fetch(`?page=load_posts&page=${++page}`)
            .then(response => response.json())
            .then(posts => {
                posts.forEach(post => {
                    const postHTML = `
                        <div class="post">
                            <h3>${escapeHtml(post.title)}</h3>
                            <p>${escapeHtml(post.content)}</p>
                            ${post.image_path ? `<img src="${post.image_path}" alt="Post Image" style="max-width: 200px;">` : ''}
                        </div>
                    `;
                    postsContainer.innerHTML += postHTML;
                });
            });
    }

    // Basic HTML escaping (for security)
    function escapeHtml(str) {
        return str.replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    // Trigger load when scrolling to bottom
    window.addEventListener('scroll', () => {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
            loadPosts();
        }
    });

    // Initial load
    loadPosts();
</script>