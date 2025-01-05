<nav>
    <div class="nav-right">
        <a href="index.html">صفحه اصلی</a>
        <a href="#">دسته بندی ها</a>
        <a href="#">تماس با ما</a>
        <a href="#" id="change-theme" onclick="changeColor()"> تغییر تم </a>
    </div>
     <div class="nav-left">
         <?php
          $islogin = false;

          if($islogin)
            echo '<span class="username">حسین</span>
          <img src="assets\images\user_icon.png" alt="User Icon" class="user-icon">';
          else 
            echo '<a href="signup.html">ثبت‌نام</a>'
        ?>  
    </div> 


</nav>