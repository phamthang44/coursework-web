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
    backdrop.onclick = (e) => {
      // console.log(e.target);
      if (e.target === backdrop) {
        this.closeModal(backdrop);
      }
    };
    let backdropClickListener = (e) => {
      // console.log(e.target);
      if (e.target === backdrop) {
        this.closeModal(backdrop);
      }
    };

    backdrop.addEventListener("click", backdropClickListener);

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
