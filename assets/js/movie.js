document.getElementById("review-form").addEventListener("submit", function (e) {   
    e.preventDefault(); // جلوگیری از ارسال فرم
    
    // پاک کردن پیام‌های قبلی
    const errorMessages = document.querySelectorAll(".error-message");
    errorMessages.forEach((msg) => (msg.style.display = "none"));
    const successMessage = document.getElementById("success-message");
    successMessage.style.display = "none";

    // متغیرها
    const commentInput = document.getElementById("comment");
    let isValid = true;

    
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


let slideIndex = 0;
showSlides();

function showSlides() {
    const slides = document.querySelectorAll(".slide");
    slides.forEach((slide) => (slide.style.display = "none")); // پنهان کردن همه اسلایدها
    slideIndex++;
    if (slideIndex > slides.length) {
        slideIndex = 1;
    }
    slides[slideIndex - 1].style.display = "block"; // نمایش اسلاید فعلی
    setTimeout(showSlides, 5000); // تغییر اسلاید هر 5 ثانیه
}


function submitComment(movieId) {

    const comment = document.querySelector("#comment").value.trim();
    fetch("submit_feedbacks.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
            type: "comment",
            movie_id: movieId,
            comment: comment,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert(data.message);
                location.reload(); // بازنشانی صفحه
            } else {
                alert(data.message);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

function submitRate(movieId) {
    const rating = document.querySelector("#rating").value.trim();

    if (rating < 1 || rating > 10) {
        alert("امتیاز باید بین ۱ تا ۱۰ باشد.");
        return;
    }

    fetch("submit_feedbacks.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
            type: "rate",
            movie_id: movieId,
            rating: rating,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert(data.message);
                location.reload(); // بازنشانی صفحه
            } else {
                alert(data.message);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}


function likeComment(commentId) {
    fetch('submit_feedbacks.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ 
            type: "like",
            comment_id: commentId }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('لایک ثبت شد!');
                location.reload(); // بارگذاری مجدد صفحه
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

// نمایش فرم پاسخ
function showReplyForm(parentId , movieId) {
    const replyForm = `
        <div class="reply-form">
            <textarea id="reply-comment${parentId}" placeholder="پاسخ خود را بنویسید"></textarea>
            <button onclick="submitReply(${parentId} , ${movieId})">ارسال پاسخ</button>
        </div>
    `;
    document.getElementById(`reply-${parentId}`).innerHTML = replyForm;
}

// ارسال پاسخ
function submitReply(parentId , movieId) {
    const comment = document.getElementById("reply-comment" + parentId).value.trim();

    if (!comment) {
        alert("لطفاً پاسخ خود را وارد کنید.");
        return;
    }

    fetch("submit_feedbacks.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
            type: "reply",
            movie_id : movieId,
            parent_id: parentId,
            comment: comment,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert("پاسخ شما ثبت شد!");
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}



