function ConfirmCard() {
  this.openConfirmCard = (content) => {
    const backdrop = document.createElement("div");
    backdrop.className = "modal-backdrop";

    const confirmCard = document.createElement("div");
    confirmCard.className = "card dark:bg-darkmode dark:text-white";

    const closeButton = document.createElement("button");
    closeButton.className = "exit-button";
    closeButton.innerHTML = `<svg height="20px" viewBox="0 0 384 512">
        <path
          d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"
        ></path>
      </svg>`;
    //content like delete file, delete task, update, ....
    const cardConfirmContent = document.createElement("div");
    cardConfirmContent.className = "card-content";
    cardConfirmContent.innerHTML = content;

    //button wrapper yes / no
    const cardConfirmButtonWrapper = document.createElement("div");
    cardConfirmButtonWrapper.className = "card-button-wrapper";
    const buttonCancel = document.createElement("button");
    buttonCancel.className =
      "card-button secondary dark:bg-gray-600 dark:text-white dark:hover:bg-gray-700";
    buttonCancel.innerText = "Cancel";

    const buttonYes = document.createElement("button");
    buttonYes.className =
      "card-button primary dark:bg-red-500 dark:text-white dark:hover:bg-red-600";
    buttonYes.innerText = "Yes";

    cardConfirmButtonWrapper.append(buttonCancel, buttonYes);
    confirmCard.append(
      cardConfirmContent,
      cardConfirmButtonWrapper,
      closeButton
    );
    backdrop.append(confirmCard);
    document.body.append(backdrop);

    //to make this one have animation transition
    setTimeout(() => {
      backdrop.classList.add("show");
    }, 0);

    backdrop.onclick = (e) => {
      // console.log(e.target);
      if (e.target === backdrop) {
        this.closeConfirm(backdrop);
      }
    };

    buttonYes.onclick = (e) => {
      e.preventDefault();
      this.closeConfirm(backdrop);
      const url =
        cardConfirmContent.querySelector(".confirm-title").dataset.url;

      window.location.href = url;
    };

    closeButton.onclick = () => this.closeConfirm(backdrop);
    buttonCancel.onclick = () => this.closeConfirm(backdrop);

    this.closeConfirm = (modalElement) => {
      modalElement.classList.remove("show");
      // document.body.removeChild(modalElement);
      modalElement.ontransitionend = () => {
        modalElement.remove();
      };
    };

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        this.closeModal(backdrop);
      }
    });
  };
}

// ------------- MODAL BLOCK --------------------

function Modal() {
  this.openModal = (content) => {
    //Create modal elements
    const backdrop = document.createElement("div");
    backdrop.className = "modal-backdrop";

    const container = document.createElement("div");
    container.className = "modal-container bg-white dark:bg-darkmode";

    const closeBtn = document.createElement("button");
    closeBtn.className = "modal-close dark:bg-white text-black";
    closeBtn.innerHTML = "&times;";

    const cancelEdit = document.createElement("button");
    cancelEdit.className = "cancel";
    cancelEdit.innerText = "Cancel edit";

    const modalContent = document.createElement("div");
    modalContent.className = "modal-content";

    //Append content and elements
    modalContent.innerHTML = content;
    container.append(closeBtn, modalContent);
    backdrop.append(container);
    document.body.append(backdrop);

    //backdrop.classList.add("show");
    setTimeout(() => {
      backdrop.classList.add("show");
    }, 0);

    // Attach event listeners
    closeBtn.onclick = () => this.closeModal(backdrop);
    // backdrop.onclick = (e) => {
    //   // console.log(e.target);
    //   if (e.target === backdrop) {
    //     this.closeModal(backdrop);
    //   }
    // };
    // let backdropClickListener = (e) => {
    //   // console.log(e.target);
    //   if (e.target === backdrop) {
    //     this.closeModal(backdrop);
    //   }
    // };

    //backdrop.addEventListener("click", backdropClickListener);

    this.handleEscape = (e) => {
      if (e.key === "Escape") {
        const existingModal = document.querySelector(".modal-backdrop");
        if (existingModal) {
          this.closeModal(existingModal);
        }
      }
    };

    document.removeEventListener("keydown", this.handleEscape);
    document.addEventListener("keydown", this.handleEscape);
  };

  this.closeModal = (modalElement) => {
    modalElement.classList.remove("show");
    // document.body.removeChild(modalElement);
    modalElement.addEventListener("transitionend", () => {
      modalElement.remove();
    });
    // If transition does not work, still ensure Modal be removed after 300ms
    setTimeout(() => {
      if (document.body.contains(modalElement)) {
        modalElement.remove();
      }
    }, 300);
  };
}

// Toast message
function toast({
  title = "",
  message = "",
  type = "success",
  duration = 3000,
}) {
  const main = document.getElementById("toast");
  if (main) {
    const toast = document.createElement("div");

    //auto remove
    const autoRemoved = setTimeout(function () {
      main.removeChild(toast);
    }, duration + 1000);

    //remove toast when click
    toast.onclick = function (e) {
      if (e.target.closest(".toast__close")) {
        main.removeChild(toast);
        clearTimeout(autoRemoved);
      }
    };

    const icons = {
      success: "fas fa-check-circle",
      info: "fas fa-info-circle",
      warning: "fas fa-exclamation-circle",
      error: "fas fa-exclamation-circle",
    };

    const icon = icons[type];
    const delay = (duration / 1000).toFixed(2);
    toast.classList.add("toast", `toast--${type}`);
    toast.style.animation = `slideInLeft ease .3s, fadeOut linear 1s ${delay}s forwards`;

    toast.innerHTML = `
    <div class="toast__icon">
      <i class="${icon}"></i>
    </div>
    <div class="toast__body">
      <h3 class="toast__title">${title}</h3>
      <p class="toast__msg">${message}</p>
    </div>
    <div class="toast__close">
      <i class="fas fa-times"></i>`;
    main.appendChild(toast);
  }
}

function checkExistingModal() {
  const existingModal = document.querySelector(".modal-backdrop");
  if (existingModal) {
    existingModal.remove();
  }
}

function checkExistingDropdown(e) {
  const addQuestionDropDown = e.target.closest(".add-question-dropdown");
  const dropdown = e.target.closest(".post-card-dropdown ");
  if (dropdown) {
    dropdown.classList.add("hidden");
  }
  if (addQuestionDropDown) {
    addQuestionDropDown.classList.add("hidden");
  }
}
class SearchUI {
  constructor() {
    this.searchBar = document.querySelector(".search-input");
    this.overlay = document.querySelector(".overlay");
    this.searchResults = document.querySelector(".search-results");
    this.typingTimer = null;

    this.initEvents();
  }

  initEvents() {
    if (this.searchBar) {
      this.searchBar.addEventListener("focus", () => this.showOverlay());
      this.searchBar.addEventListener("blur", () => this.handleBlur());
      this.searchBar.addEventListener("input", () =>
        this.debouncedFetchResults()
      );
      this.overlay.addEventListener("click", () => {
        this.hideSearchResults();
        this.hideOverlay();
      });
    }
  }

  showOverlay() {
    this.overlay.classList.remove("hidden", "opacity-0");
  }

  hideOverlay() {
    this.overlay.classList.add("opacity-0");
    setTimeout(() => this.overlay.classList.add("hidden"), 400);
  }

  showSearchResults() {
    this.searchResults.classList.remove("hidden", "opacity-0", "scale-y-0");
    this.searchResults.classList.add("scale-y-100");
  }

  hideSearchResults() {
    this.searchResults.classList.add("scale-y-0", "opacity-0");
    setTimeout(() => {
      if (this.searchBar.value.trim().length === 0) {
        this.searchResults.classList.add("hidden");
      }
    }, 400);
  }

  debouncedFetchResults() {
    clearTimeout(this.typingTimer);
    this.typingTimer = setTimeout(async () => {
      const searchTerm = this.searchBar.value.trim();
      if (searchTerm.length > 0) {
        await this.fetchSearchResults(searchTerm);
      } else {
        this.hideSearchResults();
      }
    }, 500);
  }

  async fetchSearchResults(searchTerm) {
    if (!searchTerm.trim()) return;
    try {
      const res = await fetch(`/search/${encodeURIComponent(searchTerm)}`, {
        method: "GET",
        headers: { "Content-Type": "application/json" },
      });

      if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
      const text = await res.text();
      const data = text
        ? JSON.parse(text)
        : { success: false, message: "Empty response" };

      if (data.status) {
        this.renderResults(data.posts);
        this.showSearchResults();
      } else {
        this.renderNoResults();
      }
    } catch (error) {
      console.error("Error found :", error);
      this.renderNoResults();
    }
  }

  renderResults(posts) {
    this.searchResults.innerHTML = posts
      .map(
        (post) =>
          `<div class="p-2 border-b cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
            <a href="/post/view/${post.postId}" class="block">${post.title}</a>
            <p class="text-gray-500 text-sm line-clamp-3">${post.content}</p>
          </div>`
      )
      .join("");
  }

  renderNoResults() {
    this.searchResults.innerHTML = `<h2 class="p-2 text-gray-500">No posts found</h2>`;
    this.showSearchResults();
  }

  handleBlur() {
    setTimeout(() => {
      if (
        !this.searchBar.matches(":focus") &&
        this.searchBar.value.trim().length === 0
      ) {
        this.hideOverlay();
        this.hideSearchResults();
        this.searchBar.classList.remove("w-[400px]");
      }
    }, 200);
  }
}

new SearchUI();

// Upvote functionality

class VoteFeature {
  constructor() {
    this.init();
  }

  init() {
    document.addEventListener("click", (e) => this.handleVote(e));
  }

  handleVote(e) {
    const button = e.target.closest(".vote-btn");
    if (!button) return;

    const card = button.closest(".post-card");
    const postId = card.dataset.postId;
    const scoreElement = card.querySelector(".vote-score span");
    const isUpvote = button.classList.contains("upvote-btn");

    // voteType: 1 (upvote), -1 (downvote), 0 (remove vote)
    let voteType = isUpvote ? 1 : -1;
    if (button.classList.contains("active")) {
      voteType = 0; // If already vote then click again will remove vote
    }

    const requestBody = { postId };
    if (voteType !== 0) {
      requestBody.voteType = voteType;
    }

    // send request AJAX
    this.sendVote(requestBody)
      .then((data) => {
        if (data.status && data.voteScore !== undefined) {
          // update UI
          this.updateUI(button, scoreElement, voteType, data.voteScore);
        } else {
          const errorMsgModal = new Modal();
          errorMsgModal.openModal(`<div class="space-y-4 error-login-modal">
          <h2 class="text-red-500 text-2xl font-medium">Error !</h2>
          <p class="text-black dark:text-white font-medium">${data.message}</p>
          <a href="/login" class="flex items-center justify-center text-white block w-[100px] h-[40px] rounded-lg font-medium bg-red-600">Login</a>
          </div>
          `);
        }
      })
      .catch((error) => {
        console.error("Error found: ", error);
      });
  }

  async sendVote(requestBody) {
    const response = await fetch(`/api/vote/post`, {
      method: "POST",
      body: JSON.stringify(requestBody),
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "FetchRequest",
      },
    });
    if (!response.ok) {
      throw new Error(`Server error: ${response.status}`);
    }
    return response.json();
  }

  updateUI(button, scoreElement, voteType, newScore) {
    const card = button.closest(".post-card");

    // remove old state
    card
      .querySelectorAll(".vote-btn")
      .forEach((btn) => btn.classList.remove("active"));

    // If voteType != 0 (means it just vote) then add class active
    if (voteType !== 0) {
      button.classList.add("active");
    }

    // update score
    scoreElement.textContent = newScore > 0 ? `+${newScore}` : newScore;

    if (!scoreElement.classList.contains("right-[40px]")) {
      scoreElement.className =
        "absolute block font-bold " +
        (newScore > 0
          ? "text-green-600 dark:text-green-400"
          : newScore < 0
          ? "text-red-600 dark:text-red-400"
          : "");
    } else {
      scoreElement.className =
        "absolute right-[40px] block font-bold " +
        (newScore > 0
          ? "text-green-600 dark:text-green-400"
          : newScore < 0
          ? "text-red-600 dark:text-red-400"
          : "");
    }
  }
}

new VoteFeature();

// ------------- comment reply --------------------
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".reply-button").forEach((button) => {
    button.addEventListener("click", function () {
      const commentId = this.getAttribute("data-comment-id");

      // Ẩn tất cả form trước khi mở đúng form
      document.querySelectorAll(".reply-form").forEach((form) => {
        form.classList.add("hidden");
      });

      // Chỉ hiển thị form tương ứng với comment đang được bấm
      const replyForm = document.querySelector(
        `.reply-form[data-comment-id="${commentId}"]`
      );
      if (replyForm) {
        replyForm.classList.toggle("hidden");
      }
      const cancelReplyCommentButton = document.querySelectorAll(
        ".cancel-reply-comment"
      );

      cancelReplyCommentButton.forEach((button) => {
        button.addEventListener("click", function () {
          const form = this.closest(".reply-form");
          form.classList.add("hidden");
        });
      });
    });
  });
});

// ------------- comment reply --------------------
class CommentVoteFeature {
  constructor() {
    this.init();
  }

  init() {
    document.addEventListener("click", (e) => this.handleVote(e));
  }

  handleVote(e) {
    const button = e.target.closest(".like-button");
    if (!button) return;

    const commentCard = button.closest(".flex");
    const commentId = button.dataset.commentId;
    const scoreElement = commentCard.querySelector(".like-count");

    let isLiked = button.classList.contains("active");
    let voteType = isLiked ? 0 : 1;

    const requestBody = { commentId, voteType };

    this.sendVote(requestBody)
      .then((data) => {
        if (data.status && data.voteScore !== undefined) {
          this.updateUI(button, scoreElement, voteType, data.voteScore);
        } else {
          alert("Error: " + data.message);
        }
      })
      .catch((error) => {
        console.error("Error found: ", error);
      });
  }

  async sendVote(requestBody) {
    const response = await fetch(`/api/vote/comment`, {
      method: "POST",
      body: JSON.stringify(requestBody),
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "FetchRequest",
      },
    });
    if (!response.ok) {
      throw new Error(`Server error: ${response.status}`);
    }
    return response.json();
  }

  updateUI(button, scoreElement, voteType, newScore) {
    if (voteType === 1) {
      button.classList.add("active");
    } else {
      button.classList.remove("active");
    }
    const icon = button.querySelector("i");
    if (icon) {
      if (voteType === 1) {
        icon.classList.add("icon-active", "text-white");
      } else {
        icon.classList.remove("icon-active", "text-white");
      }
    }
    scoreElement.textContent = newScore;
  }
}

new CommentVoteFeature();

// --------------------------------------------------------
const privacyBtn = document.querySelector(".privacy");
if (privacyBtn) {
  privacyBtn.addEventListener("click", function () {
    const privacyModal = new Modal();
    checkExistingModal();
    privacyModal.openModal(`<div class="space-y-4 other-modal">
    <h2 class="text-black dark:text-white text-2xl font-medium">Privacy Policy</h2>
    <ul class="list-decimal pl-4">
      <li class="text-black dark:text-white mt-2">1. We are committed to protecting your personal information. Data like your name, email, or posts is only used to operate the website and won’t be shared with third parties without your consent.</li>
      <li class="text-black dark:text-white mt-2">2. Your comments and posts may be publicly visible on the platform, but you can request their removal anytime by contacting an admin.</li>
      <li class="text-black dark:text-white mt-2">3. We use cookies to improve your experience, though you can disable them in your browser settings.</li>
    </ul>
  `);
  });
}
const termBtn = document.querySelector(".terms");
if (termBtn) {
  termBtn.addEventListener("click", function () {
    const termModal = new Modal();
    checkExistingModal();
    termModal.openModal(`<div class="space-y-4 other-modal">
    <h2 class="text-black dark:text-white text-2xl font-medium">Terms</h2>
    <ul class="list-decimal pl-4">
      <li class="text-black dark:text-white mt-2">1. By using this website, you agree to follow our community rules. Only students and admins can post or comment.</li>
      <li class="text-black dark:text-white mt-2">2. Prohibited content includes offensive language, misinformation, or anything harmful to others.</li>
      <li class="text-black dark:text-white mt-2">3. Admins can remove inappropriate posts or comments without prior notice.</li>
      <li class="text-black dark:text-white mt-2">4. You’re responsible for what you post and must not copy others’ content without permission.</li>
      <li class="text-black dark:text-white mt-2">5. We may update these terms anytime, and you’ll be notified via email or an on-site announcement.</li>
    </ul>
  `);
  });
}

const helpBtn = document.querySelector(".help-btn");
if (helpBtn) {
  helpBtn.addEventListener("click", function () {
    const helpModal = new Modal();
    checkExistingModal();
    helpModal.openModal(`<div class="space-y-4 other-modal">
    <h2 class="text-black dark:text-white text-2xl font-medium">Terms</h2>
    <ul class="list-decimal pl-4">
      <li class="text-black dark:text-white mt-2">1. <span class="font-semibold text-yellow-600">How to post:</span> Click “Create Post,” enter your content, and submit. Your post will be reviewed before going live.</li>
      <li class="text-black dark:text-white mt-2">2. <span class="font-semibold text-yellow-600">How to comment:</span> Pick a post, type your thoughts in the comment box, and hit send.</li>
      <li class="text-black dark:text-white mt-2">3. <span class="font-semibold text-green-500">Need help?</span> Contact admins at <span class="text-red-600 font-semibold">Contact us</span> button</li>
      <li class="text-black dark:text-white mt-2">4. <span class="font-semibold text-blue-500">Forgot password?</span> Click <span class="font-semibold text-green-600">“Forgot Password”</span> on the login page to reset it.</li>
    </ul>
  `);
  });
}
