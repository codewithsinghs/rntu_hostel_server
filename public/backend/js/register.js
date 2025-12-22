// register section javascript

const form = document.getElementById("registrationForm");
const steps = document.querySelectorAll(".form-step");
const tabs = document.querySelectorAll(".tab");
const formSlider = document.querySelector(".form-slider");
let currentStep = 0;

function updateFormPosition() {
  formSlider.style.transform = `translateX(-${currentStep * 100}%)`;
  tabs.forEach((tab, index) => {
    tab.classList.toggle("active", index === currentStep);
    tab.disabled = index > currentStepVisited;
  });
}

function validateCurrentStep() {
  let valid = true;
  const currentFields = steps[currentStep].querySelectorAll("input, select, textarea");
  currentFields.forEach(field => {
    const parent = field.closest(".form-group") || field.closest(".emergency-group");
    if (!field.checkValidity()) {
      field.classList.add("error");
      parent?.classList.add("error");
      valid = false;
    } else {
      field.classList.remove("error");
      parent?.classList.remove("error");
    }
  });
  return valid;
}

let currentStepVisited = 0;

document.querySelectorAll(".next-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    if (!validateCurrentStep()) return;
    currentStep = Math.min(currentStep + 1, steps.length - 1);
    currentStepVisited = Math.max(currentStepVisited, currentStep);
    updateFormPosition();
  });
});

document.querySelectorAll(".back-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    currentStep = Math.max(currentStep - 1, 0);
    updateFormPosition();
  });
});

tabs.forEach((tab, index) => {
  tab.addEventListener("click", () => {
    if (index <= currentStepVisited) {
      currentStep = index;
      updateFormPosition();
    }
  });
});

// Emergency contact add more
const addBtn = document.getElementById("addEmergency");
const container = document.getElementById("emergencyContacts");

addBtn.addEventListener("click", () => {
  const group = document.createElement("div");
  group.classList.add("emergency-group");
  group.innerHTML = `
    <input type="text" name="emergencyName[]" required placeholder="Name" />
    <input type="tel" name="emergencyContact[]" required placeholder="Contact Number" />
    <button type="button" class="deleteBtn">Delete</button>
  `;
  container.appendChild(group);

  group.querySelector(".deleteBtn").addEventListener("click", () => {
    group.remove();
  });
});

// Form submit
form.addEventListener("submit", e => {
  if (!validateCurrentStep()) {
    e.preventDefault();
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const loginBtn = document.querySelectorAll("#btn-login");
  const popup = document.getElementById("loginPopup");
  const closeBtn = document.getElementById("closePopup");
  const tabBtns = document.querySelectorAll(".tab-btn");
  const otpInputs = document.querySelectorAll(".otp-inputs input");
  const popupRight = document.querySelector(".popup-right");

  // Open popup
  loginBtn.forEach(btn =>
    btn.addEventListener("click", () => {
      popup.style.display = "flex";
    })
  );

  // Close popup
  closeBtn.addEventListener("click", () => {
    popup.style.display = "none";
  });

  // Tab switch with form slide
  tabBtns.forEach(btn => {
    btn.addEventListener("click", () => {
      tabBtns.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");

      const tab = btn.dataset.tab;

      // Add or remove sliding class
      if (tab === "staff") {
        popupRight.classList.add("xfw-staff-active");
      } else {
        popupRight.classList.remove("xfw-staff-active");
      }
    });
  });

  // OTP Input Auto Focus
  otpInputs.forEach((input, idx) => {
    input.addEventListener("input", () => {
      if (input.value && idx < otpInputs.length - 1) {
        otpInputs[idx + 1].focus();
      }
    });

    input.addEventListener("keydown", (e) => {
      if (e.key === "Backspace" && !input.value && idx > 0) {
        otpInputs[idx - 1].focus();
      }
    });
  });
});
