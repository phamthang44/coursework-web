function ConfirmCard() {
  this.openConfirmCard = (content) => {
    const backdrop = document.createElement("div");
    backdrop.className = "modal-backdrop";

    const confirmCard = document.createElement("div");
    confirmCard.className = "card";

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
    buttonCancel.className = "card-button secondary";
    buttonCancel.innerText = "Cancel";

    const buttonYes = document.createElement("button");
    buttonYes.className = "card-button primary";
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

    buttonYes.onclick = (e) => {
      this.closeConfirm(backdrop);
      const chosenTaskCard = e.target.closest(".card");
      const taskInfo = chosenTaskCard.querySelector(
        ".card-description.card-info .strong-text"
      );

      const taskId = +taskInfo.dataset.taskId;

      if (taskId) {
        const task = tasks.find((task) => task.id === taskId);

        if (!task) return alert("Task not found!");
        deleteTask(task);
      } else return alert("Task ID is missing!");
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
    container.className = "modal-container";

    const closeBtn = document.createElement("button");
    closeBtn.className = "modal-close";
    closeBtn.innerHTML = "&times;";

    const editableBtn = document.createElement("button");
    editableBtn.className = "modal-editable";
    editableBtn.innerText = "Click to edit";

    const cancelEdit = document.createElement("button");
    cancelEdit.className = "cancel";
    cancelEdit.innerText = "Cancel edit";

    const modalContent = document.createElement("div");
    modalContent.className = "modal-content";

    //Append content and elements
    modalContent.innerHTML = content;
    container.append(closeBtn, modalContent, editableBtn);
    backdrop.append(container);
    document.body.append(backdrop);

    //backdrop.classList.add("show");
    setTimeout(() => {
      backdrop.classList.add("show");
    }, 0);

    // Attach event listeners
    closeBtn.onclick = () => this.closeModal(backdrop);
    // backdrop.onclick = (e) => {
    //   console.log(e.target);
    //   if (e.target === backdrop) {
    //     this.closeModal(backdrop);
    //   }
    // };
    let backdropClickListener = (e) => {
      console.log(e.target);
      if (e.target === backdrop) {
        this.closeModal(backdrop);
      }
    };

    backdrop.addEventListener("click", backdropClickListener);

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        this.closeModal(backdrop);
      }
    });

    editableBtn.onclick = () => {
      backdrop.removeEventListener("click", backdropClickListener);
      this.changeEditableState(modalContent);
      container.append(cancelEdit);
      closeBtn.remove();
    };

    cancelEdit.onclick = () => {
      this.cancelEditChange();
      this.closeModal(backdrop);
    };
  };

  this.closeModal = (modalElement) => {
    modalElement.classList.remove("show");
    // document.body.removeChild(modalElement);
    modalElement.ontransitionend = () => {
      modalElement.remove();
    };
  };

  this.cancelEditChange = () => {
    this.originalValues.forEach((value, element) => {
      let newElement = document.createElement(element.tagName.toLowerCase());
      newElement.className = element.className;
      newElement.textContent = value;
      element.replaceWith(newElement);
    });
    this.originalValues.clear();
  };

  this.changeEditableState = () => {
    this.originalValues = new Map(); // Map save original content
    const details = document.querySelectorAll(".modal-detail");
    details.forEach((detail) => {
      if (detail.classList.contains("date")) return;

      this.originalValues.set(detail, detail.innerHTML.trim());

      let newElement;
      let fieldName = detail.classList[1] || "field";
      let currentValue = detail.textContent.trim();

      if (detail.classList.contains("description")) {
        // If description use textarea
        newElement = document.createElement("textarea");
        newElement.value = detail.textContent.trim();
        newElement.name = "description";
        newElement.required = true;
      } else if (
        detail.classList.contains("priority") ||
        detail.classList.contains("status")
      ) {
        newElement = document.createElement("select");

        let options = [];
        if (detail.classList.contains("priority")) {
          options = ["Low", "Medium", "High"];
        } else if (detail.classList.contains("status")) {
          options = ["To start", "In progress", "Done"];
        }

        options.forEach((option) => {
          let optElement = document.createElement("option");
          optElement.value = option;
          optElement.textContent = option;
          if (option === currentValue) optElement.selected = true;
          newElement.appendChild(optElement);
        });
      } else {
        // other -> text
        newElement = document.createElement("input");
        newElement.type = "text";
      }
      newElement.value = detail.textContent.trim();
      newElement.name = fieldName;
      newElement.required = true;
      newElement.classList.add(...detail.classList);
      detail.replaceWith(newElement);
    });
  };
}
