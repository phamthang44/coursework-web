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
// ------------- feature edit delete ----------
function setupEventListeners() {
  const optionsPostCard = document.querySelectorAll(".post-options");
  optionsPostCard.forEach((option) => {
    option.addEventListener("click", function (e) {
      const dropdown = option.nextElementSibling;
      dropdown.classList.toggle("hidden");

      document.addEventListener("click", function (e) {
        if (e.target.classList.contains("delete-action")) {
          e.preventDefault();
          // e.stopPropagation();
          checkExistingDropdown(e);
          checkExistingModal();
          const deleteConfirmCard = new ConfirmCard();
          deleteConfirmCard.openConfirmCard(`
<h2 class="text-red-600 dark:text-white confirm-title" data-url="${e.target.href}">
Are you sure you want to delete this post?
</h2>`);
        }
        if (e.target.classList.contains("edit-action-quick")) {
          // e.preventDefault();
          // e.stopPropagation();

          checkExistingDropdown(e);
          checkExistingModal();

          // Get the post ID from the clicked element's data attribute
          const postCard = e.target.closest(".post-card");
          const postId = postCard.dataset.postId;

          // Get post data (you'll need to add this data to your HTML elements)
          const postTitle = postCard.dataset.title || "";
          const postContent = postCard.dataset.content || "";
          const postModuleId = postCard.dataset.moduleId || "";
          const postModuleName = postCard.dataset.moduleName || "";
          const postImage = postCard.dataset.postImage || "hidden";

          const editModalQuick = new Modal();
          editModalQuick.openModal(`<h2 class="text-xl text-red-500 font-bold mb-4">Edit the post</h2>
        <form action="/posts/update/${postId}" method="POST" enctype="multipart/form-data" id="form-update-post" class="space-y-4">
            <!-- Title Field (Optional) -->
            <div class="form-group py-4 mb-4">
                <label for="title" class="block font-medium text-gray-700 dark:text-white mb-4">Title (Optional):</label>
                <input type="text" id="title" name="title" placeholder="Enter title (optional)"
                    class="w-full h-12 p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 dark:text-white"
                    value="${postTitle}">
                <span class="form-message text-red-500 text-sm"></span>
                <input type="hidden" name="user_id" value="<?php echo $user->getUserId(); ?>">
            </div>
            <!-- Content Field (Required) -->
            <div class="form-group">
                <label for="content" class="block font-medium text-gray-700 dark:text-white mb-4">Content (Required):</label>
                <textarea id="content" name="content" rows="5" placeholder="Enter content"
                    class="w-full h-40 resize-none p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 dark:text-white">${postContent}</textarea>
                <span class="form-message text-red-500 font-medium text-sm"></span>
            </div>
            <!-- Module Select -->
            <div class="form-group">
                <label for="module" class="block font-medium text-gray-700 dark:text-white mb-4">Module Name:</label>
                <select id="module" name="module"
                    class="w-50 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 text-black dark:text-white">
                    <option value="" class="text-black dark:text-white">Selected : ${postModuleName}</option>
                    <?php foreach ($modules as $module): ?>
                        <option class="text-black dark:text-white" value="<?php echo $module->getModuleId(); ?>"><?php echo $module->getModuleName(); ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="form-message text-red-500 font-medium text-sm ml-5"></span>
            </div>
            <!-- Image Upload -->
            <div class="form-group flex gap-7 relative">
                <div>
                    <label for="image" class="block font-medium text-gray-700 dark:text-white mb-2">Upload Image:</label>
                    <label class="custom-file-upload text-gray-700 dark:text-white">
                    <input type="file" id="image" name="image" accept="image/*"
                        class="w-2 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none title">
                        Choose an image
                    </label>
                    <span class="form-message text-red-500 font-medium text-sm"></span>
                </div>
                <!-- Preview Image -->
                <div id="preview-container" class="${postImage} absolute -top-[100px] left-[440px]">
                    <h3 class="font-medium text-gray-700 dark:text-white">Preview Image:</h3>
                    <img id="preview" src="/${postImage}" alt="Preview Image" class="w-80 h-40 object-cover mt-2 rounded-lg border border-gray-300" />
                </div>
            </div>
            <!-- Submit Button -->
            <div class="flex justify-end">
                <input class="btn bg-red-700 hover:bg-red-600 transition text-white font-bold" type="submit" value="Save">
            </div>
        </form>
`);
        }
      });
    });
  });
}
requestAnimationFrame(() => {
  setupEventListeners();
});

function checkExistingModal() {
  const existingModal = document.querySelector(".modal-backdrop");
  if (existingModal) {
    existingModal.remove();
  }
}

function checkExistingDropdown(e) {
  const dropdown = e.target.closest(".post-card-dropdown ");
  if (dropdown) {
    dropdown.classList.add("hidden");
  }
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
