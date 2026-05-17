const BASE_URL = window.BASE_URL || "";

document.addEventListener("click", function (event) {

    // Clear flag
    if (event.target.classList.contains("clear-flag-btn")) {
        let reportId = event.target.dataset.reportId;

        fetch(BASE_URL + "/api/reports/" + reportId, {
            method: "DELETE"
        })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.success === false) {
                alert(data.message);
                return;
            }

            let row = document.getElementById("report-" + reportId);
            row.remove();
        })
        .catch(function () {
            alert("Something went wrong");
        });
    }

    // Admin delete comment
    if (event.target.classList.contains("admin-delete-comment-btn")) {
        let commentId = event.target.dataset.commentId;
        let reportId = event.target.dataset.reportId;

        let confirmDelete = confirm("Delete this comment?");

        if (!confirmDelete) {
            return;
        }

        fetch(BASE_URL + "/api/admin/comments/" + commentId, {
            method: "DELETE"
        })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.success === false) {
                alert(data.message);
                return;
            }

            let row = document.getElementById("report-" + reportId);
            row.remove();
        })
        .catch(function () {
            alert("Something went wrong");
        });
    }
});
