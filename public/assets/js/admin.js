/* ══ NAVIGATION ══ */
const titles = {
    dashboard: "Dashboard",
    patients: "Patients",
    doctors: "Doctors",
    appointments: "Appointments",
    blog: "Blog / News",
};

function showPage(id, el) {
    document
        .querySelectorAll(".page-content")
        .forEach((p) => p.classList.remove("active"));
    document
        .querySelectorAll(".nav-item")
        .forEach((n) => n.classList.remove("active"));
    document.getElementById("page-" + id).classList.add("active");
    el.classList.add("active");
    document.getElementById("topbarTitle").textContent = titles[id] || id;
    if (window.innerWidth < 900) closeSidebar();
}

/* ══ SIDEBAR MOBILE ══ */
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("open");
    document.getElementById("overlay").classList.toggle("show");
}

function closeSidebar() {
    document.getElementById("sidebar").classList.remove("open");
    document.getElementById("overlay").classList.remove("show");
}

// appadmin.js

function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.add("open");
    } else {
        console.warn(
            `Modal Error: Element with ID "${id}" was not found on this page.`,
        );
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove("open");
    }
}
/* ══ CHART TABS ══ */
document.querySelectorAll(".chart-tab").forEach((btn) => {
    btn.addEventListener("click", function () {
        this.closest(".chart-tabs")
            .querySelectorAll(".chart-tab")
            .forEach((b) => b.classList.remove("active"));
        this.classList.add("active");
    });
});

/* ══ STATUS TABS ══ */
document.querySelectorAll(".status-tab").forEach((btn) => {
    btn.addEventListener("click", function () {
        this.closest(".status-filter")
            .querySelectorAll(".status-tab")
            .forEach((b) => b.classList.remove("active"));
        this.classList.add("active");
    });
});

/* ══ CHARTS ══ */
window.addEventListener("load", () => {
    /* Main area chart */
    const ctx = document.getElementById("mainChart");
    if (ctx) {
        new Chart(ctx, {
            type: "line",
            data: {
                labels: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec",
                ],
                datasets: [
                    {
                        label: "Patients",
                        data: [
                            3200, 3800, 4100, 4600, 4200, 5100, 5500, 5200,
                            5900, 6100, 5800, 6400,
                        ],
                        borderColor: "#1A8A6E",
                        backgroundColor: "rgba(26,138,110,0.08)",
                        borderWidth: 2.5,
                        pointRadius: 4,
                        pointBackgroundColor: "#1A8A6E",
                        pointBorderColor: "#fff",
                        pointBorderWidth: 2,
                        fill: true,
                        tension: 0.4,
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: "#1C2B3A",
                        titleColor: "#fff",
                        bodyColor: "rgba(255,255,255,0.7)",
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: {
                            color: "#9AAABB",
                            font: { size: 11, family: "'DM Sans', sans-serif" },
                        },
                    },
                    y: {
                        grid: { color: "rgba(0,0,0,0.04)" },
                        border: { display: false, dash: [4, 4] },
                        ticks: {
                            color: "#9AAABB",
                            font: { size: 11, family: "'DM Sans', sans-serif" },
                        },
                    },
                },
            },
        });
    }

    /* Donut chart */
    const dctx = document.getElementById("donutChart");
    if (dctx) {
        new Chart(dctx, {
            type: "doughnut",
            data: {
                datasets: [
                    {
                        data: [38, 24, 20, 18],
                        backgroundColor: [
                            "#1A8A6E",
                            "#2563EB",
                            "#D97706",
                            "#7C3AED",
                        ],
                        borderWidth: 0,
                        hoverOffset: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                cutout: "72%",
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: "#1C2B3A",
                        titleColor: "#fff",
                        bodyColor: "rgba(255,255,255,0.7)",
                        padding: 10,
                        cornerRadius: 8,
                    },
                },
            },
        });
    }
});
