<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, User!</h1>
    <a href="?page=logout">Logout</a>
    
    <!-- Post Creation Form -->
    <form action="?page=create_post" method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Post Title" required>
    <textarea name="content" placeholder="Post Content"></textarea>
    <input type="file" name="image" accept="image/*">
    <button type="submit">Create Post</button>
												</form>

    <!-- Display Posts -->
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