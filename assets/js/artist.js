function submitFollow(artistId) {    
    fetch("submit_feedbacks.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
            type: "follow",
            artist_id: artistId
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