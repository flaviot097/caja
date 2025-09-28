// Responsive menu toggle for mobile
function initMobileMenu() {
  const header = document.querySelector(".header");
  const nav = document.querySelector(".nav");

  // Create mobile menu button
  const mobileMenuBtn = document.createElement("button");
  mobileMenuBtn.className = "mobile-menu-btn";
  mobileMenuBtn.innerHTML = "☰";
  mobileMenuBtn.style.cssText = `
        display: none;
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        padding: 8px;
    `;

  nav.appendChild(mobileMenuBtn);

  // Add mobile menu styles
  const mobileStyle = document.createElement("style");
  mobileStyle.textContent = `
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block !important;
            }
            
            .nav-center {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #e0e0e0;
                flex-direction: column;
                padding: 10px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }
            
            .nav-center.active {
                display: flex !important;
            }
            
            .nav-item {
                padding: 10px;
                border-bottom: 1px solid #ccc;
            }
        }
    `;
  document.head.appendChild(mobileStyle);

  // Toggle mobile menu
  mobileMenuBtn.addEventListener("click", () => {
    const navCenter = document.querySelector(".nav-center");
    navCenter.classList.toggle("active");
  });
}

// Initialize mobile menu
initMobileMenu();

// Add fade out animation
const style = document.createElement("style");
style.textContent = `
    @keyframes fadeOut {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(-100%); }
    }
`;
document.head.appendChild(style);

// Theme Toggle Functionality
const themeToggle = document.getElementById("themeToggle");
const body = document.body;

// Check for saved theme preference or default to light mode
const currentTheme = localStorage.getItem("theme") || "light";
if (currentTheme === "dark") {
  body.classList.add("dark-mode");
  themeToggle.textContent = "☀️";
}

themeToggle.addEventListener("click", () => {
  body.classList.toggle("dark-mode");

  // Update button icon and save preference
  if (body.classList.contains("dark-mode")) {
    themeToggle.textContent = "☀️";
    localStorage.setItem("theme", "dark");

    //dark mode styles for form
    const fomD = document.querySelector(".responsive-form");
    fomD.classList.toggle("form-dark-m");
    const darkInputs = document.querySelectorAll("#responsive-form input");
    darkInputs.forEach((input) => {
      input.classList.toggle("inputs-dark-m");
    });
    const titlesD = document.querySelectorAll("#titles-sectores");
    titlesD.forEach((title) => {
      title.classList.toggle("titles-dar-m");
    });
  } else {
    themeToggle.textContent = "🌙";
    localStorage.setItem("theme", "light");

    //dark mode styles for form
    const fomD = document.querySelector(".responsive-form");
    fomD.classList.toggle("form-dark-m");
    const darkInputs = document.querySelectorAll("#responsive-form input");
    darkInputs.forEach((input) => {
      input.classList.toggle("inputs-dark-m");
    });
    const titlesD = document.querySelectorAll("#titles-sectores");
    titlesD.forEach((title) => {
      title.classList.toggle("titles-dar-m");
    });
  }
});
