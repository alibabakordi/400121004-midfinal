<?php $this->startSecureSession(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>.error { color: red; }</style>
</head>
<body>
    <?php if (!empty($_SESSION['register_error'])): ?>
        <div class="error"><?= $_SESSION['register_error'] ?></div>
        <?php unset($_SESSION['register_error']); ?>
    <?php endif; ?>

    <form method="POST" action="?page=register">
        <input type="text" name="username" 
               value="<?= htmlspecialchars($_SESSION['old_input']['username'] ?? '') ?>" 
               required placeholder="Username">
        <input type="email" name="email" 
               value="<?= htmlspecialchars($_SESSION['old_input']['email'] ?? '') ?>" 
               required placeholder="Email">
        <input type="password" name="password" required placeholder="Password">
        <button type="submit">Register</button>
    </form>
    <?php unset($_SESSION['old_input']); ?>
    <a href="?page=login">Already have an account?</a>
</body>
</html>