
function search(query) {
    const dropdown = document.getElementById("autocomplete-results");

    // Clear dropdown if query is empty
    if (query.trim() === "") {
        dropdown.innerHTML = "";
        return;
    }

    // Send request to the backend
    fetch("search.php?q=" + encodeURIComponent(query))
        .then((response) => response.json())
        .then((data) => {
            // Clear previous results
            dropdown.innerHTML = "";

            // Append new results
            if (data.length > 0) {
                data.forEach((item) => {
                    const li = document.createElement("li");
                    li.textContent = item.name;
                    li.addEventListener("click", () => {
                        window.location.href = "/movie.php?id=" + item.id;
                        exit;
                    });
                    dropdown.appendChild(li);
                });
            } else {
                dropdown.innerHTML = "<li>No results found</li>";
            }
        })
        .catch((error) => {
            console.error("Error fetching data:", error);
        });
}



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
