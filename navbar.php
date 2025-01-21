<nav>
  <div class="nav-right">
    <a href="/Index.php">صفحه اصلی</a>
    <a href="#">دسته بندی ها</a>
    <a href="#">تماس با ما</a>
    <a href="#" id="change-theme" onclick="changeColor()">تغییر تم</a>
  </div>
  <div class="nav-left">
    <?php
    $islogin = isset($_SESSION['user_id']);
    if ($islogin) {
      echo '<a href="/logout.php" class="logout"> خروج </a>';
      echo '<a href="/user.php" class="username">' . htmlspecialchars($_SESSION['username']) . '</a>';
      echo '<img src="/' . htmlspecialchars($_SESSION['profile_image']) . '" alt="User Icon" class="user-icon">';
    } else {
      // If the user is not logged in, show the signup link
      echo '<a href="/StaticPages/signup.html">ثبت‌نام</a>';
    }
    ?>
  </div>
</nav>
