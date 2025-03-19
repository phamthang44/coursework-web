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

const searchBar = document.querySelector(".search-input");
const overLay = document.querySelector(".overlay");
const searchResults = document.querySelector(".search-results");
searchBar.addEventListener("focus", () => {
  overLay.classList.remove("hidden");
  setTimeout(() => {
    overLay.classList.remove("opacity-0");
  }, 10);

  searchResults.innerHTML = "";
});
searchBar.addEventListener("blur", () => {
  overLay.classList.add("opacity-0");
  setTimeout(() => {
    overLay.classList.add("hidden");
  }, 300);
  setTimeout(() => {
    searchResults.classList.add("-translate-y-1/2");
  }, 10);
  setTimeout(() => {
    searchResults.classList.add("hidden");
  }, 300);
});
searchBar.addEventListener("input", () => {
  if (searchBar.value.length > 0) {
    setTimeout(() => {
      searchResults.classList.add("translate-y-1/2");
    }, 10);
    setTimeout(() => {
      searchResults.classList.remove("hidden");
    }, 500);
  } else {
    setTimeout(() => {
      searchResults.classList.add("-translate-y-1/2");
    }, 10);
    setTimeout(() => {
      searchResults.classList.add("hidden");
    }, 500);
  }
});
