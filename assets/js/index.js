// انتخاب تمام سوال‌ها
const faqQuestions = document.querySelectorAll(".faq-question");

// افزودن رویداد کلیک به هر سوال
faqQuestions.forEach((question) => {
    question.addEventListener("click", () => {
        // دسترسی به پاسخ مرتبط با سوال
        const answer = question.nextElementSibling;

        // تغییر وضعیت نمایش پاسخ (نمایش یا پنهان)
        if (answer.style.display === "block") {
            answer.style.display = "none";
        } else {
            answer.style.display = "block";
        }
    });
});
