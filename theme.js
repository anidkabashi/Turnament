const body = document.body;
const toggleBtn = document.getElementById("themeToggle");
const icon = document.getElementById("themeIcon");

const saved = localStorage.getItem("theme");
if(saved === "dark") {
    body.classList.add("dark-theme");
    icon.classList.replace("fa-moon", "fa-sun");
}

toggleBtn?.addEventListener("click", () => {
    body.classList.toggle("dark-theme");
    if(body.classList.contains("dark-theme")){
        localStorage.setItem("theme", "dark");
        icon.classList.replace("fa-moon","fa-sun");
    } else {
        localStorage.setItem("theme","light");
        icon.classList.replace("fa-sun","fa-moon");
    }
});
