<?php $this->startSecureSession(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>.error { color: red; } .success { color: green; }</style>
</head>
<body>
    <?php if (!empty($_SESSION['login_error'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['login_error']) ?></div>
        <?php unset($_SESSION['login_error']); ?>
    <?php endif; ?>
    
    <?php if (!empty($_GET['success'])): ?>
        <div class="success">Registration successful! Please login.</div>
    <?php endif; ?>

    <form method="POST" action="?page=login">
        <input type="email" name="email" required placeholder="Email">
        <input type="password" name="password" required placeholder="Password">
        <button type="submit">Login</button>
    </form>
    <a href="?page=register">Create account</a>
</body>
</html>