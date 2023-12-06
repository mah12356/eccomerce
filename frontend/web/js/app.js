const layoutContainer = document.querySelector(".layout-container");
const overlay = document.querySelector(".overlay-sidebar");
const primaryNav = document.querySelector(".primary-navigation");
const toggleButton = document.querySelector(".toggle-button");

const toggleSidebar = () => {
    const isHidden = primaryNav.dataset.hidden === "true";
    const isExpanded = toggleButton.getAttribute("aria-expanded") === "true";

    primaryNav.dataset.hidden = !isHidden;
    toggleButton.setAttribute("aria-expanded", !isExpanded);
    overlay.classList.add("active");
};

const closeFromOutside = (e) => {
    if (!primaryNav.parentElement.contains(e.target)) {
        toggleButton.setAttribute("aria-expanded", false);
        primaryNav.dataset.hidden = true;
        overlay.classList.remove("active");
    }
};

toggleButton.addEventListener("click", toggleSidebar);
layoutContainer.addEventListener("click", closeFromOutside);


function changeSections(id) {
    if (id == 'live') {
        document.getElementById('passed').classList.remove('select');
        document.getElementById('live').classList.add('select');

        document.getElementById('passed-tab').style.display = 'none';
        document.getElementById('live-tab').style.display = 'block';
    }else {
        document.getElementById('passed').classList.add('select');
        document.getElementById('live').classList.remove('select');

        document.getElementById('passed-tab').style.display = 'block';
        document.getElementById('live-tab').style.display = 'none';
    }
}