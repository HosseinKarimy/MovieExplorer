document.getElementById("review-form").addEventListener("submit", function (e) {
    e.preventDefault(); // جلوگیری از ارسال فرم
    
    // پاک کردن پیام‌های قبلی
    const errorMessages = document.querySelectorAll(".error-message");
    errorMessages.forEach((msg) => (msg.style.display = "none"));
    const successMessage = document.getElementById("success-message");
    successMessage.style.display = "none";

    // متغیرها
    const nameInput = document.getElementById("name");
    const emailInput = document.getElementById("email");
    const commentInput = document.getElementById("comment");
    let isValid = true;

    // بررسی نام (فقط حروف و فاصله)
    const nameRegex = /^[\u0600-\u06FF\s]+$/; // پشتیبانی از حروف فارسی
    if (!nameRegex.test(nameInput.value.trim())) {
        showError(nameInput, "نام فقط باید شامل حروف و فاصله باشد.");
        isValid = false;
    }

    // بررسی فرمت ایمیل
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // فرمت استاندارد ایمیل
    if (!emailRegex.test(emailInput.value.trim())) {
        showError(emailInput, "ایمیل معتبر وارد کنید.");
        isValid = false;
    }

    // بررسی نظر (نباید خالی باشد)
    if (commentInput.value.trim() === "") {
        showError(commentInput, "لطفاً نظر خود را وارد کنید.");
        isValid = false;
    }

    // اگر همه چیز درست باشد
    if (isValid) {
        successMessage.textContent = "کامنت شما ارسال شد.";
        successMessage.style.display = "block";
        document.getElementById("review-form").reset(); // پاک کردن فرم
    }
});

function showError(inputElement, message) {
    const errorElement = inputElement.nextElementSibling;
    errorElement.textContent = message;
    errorElement.style.display = "block";
}
