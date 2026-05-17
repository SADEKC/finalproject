const BASE_URL = window.BASE_URL || "";
const articleId = window.articleId;

function escapeHtml(text) {
    let div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}

// Post comment
let postCommentBtn = document.getElementById("postCommentBtn");

if (postCommentBtn) {
    postCommentBtn.addEventListener("click", function () {
        let body = document.getElementById("commentBody").value.trim();

        let formData = new FormData();
        formData.append("article_id", articleId);
        formData.append("body", body);

        fetch(BASE_URL + "/api/comments", {
            method: "POST",
            body: formData
        })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.success === false) {
                alert(data.message);
                return;
            }

            let comment = data.comment;
            let commentsList = document.getElementById("commentsList");

            let newComment = document.createElement("div");
            newComment.id = "comment-" + comment.id;
            newComment.className = "comment-box";
            newComment.style.border = "1px solid #ddd";
            newComment.style.padding = "10px";
            newComment.style.marginBottom = "10px";

            let deleteButton = "";

            if (comment.can_delete) {
                deleteButton = `
                    <button 
                        class="delete-comment-btn" 
                        data-comment-id="${comment.id}"
                    >
                        Delete
                    </button>
                `;
            }

            newComment.innerHTML = `
                <strong>${escapeHtml(comment.name)}</strong>
                <p>${escapeHtml(comment.body)}</p>
                <small>${escapeHtml(comment.created_at)}</small>
                <br><br>

                <button 
                    class="report-btn" 
                    data-comment-id="${comment.id}"
                >
                    🚩 Report
                </button>

                ${deleteButton}
            `;

            commentsList.prepend(newComment);

            document.getElementById("commentBody").value = "";
        })
        .catch(function () {
            alert("Something went wrong");
        });
    });
}

// Report and delete button handler
document.addEventListener("click", function (event) {

    // Open report form
    if (event.target.classList.contains("report-btn")) {
        let commentId = event.target.dataset.commentId;
        let commentBox = document.getElementById("comment-" + commentId);

        let oldForm = commentBox.querySelector(".report-form");

        if (oldForm) {
            oldForm.remove();
            return;
        }

        let reportForm = document.createElement("div");
        reportForm.className = "report-form";
        reportForm.style.marginTop = "10px";

        reportForm.innerHTML = `
            <input 
                type="text" 
                class="report-reason" 
                placeholder="Reason"
            >

            <button 
                class="submit-report-btn" 
                data-comment-id="${commentId}"
            >
                Submit Report
            </button>
        `;

        event.target.after(reportForm);
    }

    // Submit report
    if (event.target.classList.contains("submit-report-btn")) {
        let commentId = event.target.dataset.commentId;
        let commentBox = document.getElementById("comment-" + commentId);
        let reasonInput = commentBox.querySelector(".report-reason");
        let reason = reasonInput.value.trim();

        if (reason === "") {
            alert("Reason is required");
            return;
        }

        let formData = new FormData();
        formData.append("comment_id", commentId);
        formData.append("reason", reason);

        fetch(BASE_URL + "/api/reports", {
            method: "POST",
            body: formData
        })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.success === false) {
                alert(data.message);
                return;
            }

            let reportButton = commentBox.querySelector(".report-btn");
            reportButton.textContent = "Reported ✓";
            reportButton.disabled = true;

            let reportForm = commentBox.querySelector(".report-form");
            reportForm.remove();
        })
        .catch(function () {
            alert("Something went wrong");
        });
    }

    // Delete comment
    if (event.target.classList.contains("delete-comment-btn")) {
        let commentId = event.target.dataset.commentId;

        let confirmDelete = confirm("Delete this comment?");

        if (!confirmDelete) {
            return;
        }

        fetch(BASE_URL + "/api/comments/" + commentId, {
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

            let commentBox = document.getElementById("comment-" + commentId);
            commentBox.remove();
        })
        .catch(function () {
            alert("Something went wrong");
        });
    }
});
