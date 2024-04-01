// theme-toggle.js

function toggleDarkMode() {
  var body = document.body;
  body.classList.toggle("dark-mode");
  var isChecked = document.getElementById("darkModeToggle").checked;
  localStorage.setItem('darkMode', isChecked ? "enabled" : "disabled");
}

// Check for saved 'darkMode' in localStorage
window.onload = function() {
  var darkModeStatus = localStorage.getItem('darkMode');
  if (darkModeStatus === "enabled") {
    document.body.classList.add("dark-mode");
    document.getElementById("darkModeToggle").checked = true;
  }
}

document.getElementById("darkModeToggle").addEventListener('change', toggleDarkMode);
