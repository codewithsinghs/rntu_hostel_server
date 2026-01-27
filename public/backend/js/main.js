// Sidebar menu mobile responsive start

document.addEventListener("DOMContentLoaded", () => {
    const aside = document.querySelector(".sidebar");
    const header = document.querySelector(".topbar");
    const toggleBtn = document.getElementById("toggle-btn");
    const mq = window.matchMedia("(max-width: 768px)");

    // ✅ Setup sidebar based on screen size
    function setSidebarState(e) {
        if (e.matches) {
            // 📱 Small screen – collapsed by default
            aside.classList.add("collapsed");
            header.classList.remove("topbar-part");
            header.classList.add("topbar-full");
        } else {
            // 💻 Desktop – open by default
            aside.classList.remove("collapsed");
            header.classList.remove("topbar-full");
            header.classList.add("topbar-part");
        }
    }

    // Run on page load
    setSidebarState(mq);

    // Run when screen size changes
    mq.addEventListener("change", setSidebarState);

    // ✅ Toggle button logic
    toggleBtn.innerHTML = "☰";
    toggleBtn.addEventListener("click", () => {
        aside.classList.toggle("collapsed");

        if (aside.classList.contains("collapsed")) {
            header.classList.remove("topbar-part");
            header.classList.add("topbar-full");
        } else {
            header.classList.remove("topbar-full");
            header.classList.add("topbar-part");
        }
    });
});

// Sidebar menu mobile responsive End

// --------------------------------------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------

// ApexCharts Config
const options = {
    chart: {
        type: "area",
        height: 300,
        toolbar: { show: true },
    },
    series: [
        {
            name: "Attendance ",
            data: [0, 5, 10, 15, 10, 25, 5, 26, 28, 5, 15, 20],
        },
    ],
    xaxis: {
        categories: [
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
    },
    colors: ["#6DD5FA"],
    fill: {
        type: "gradient",
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.1,
            stops: [0, 90, 100],
        },
    },
    dataLabels: {
        enabled: true,
    },
    stroke: {
        curve: "smooth",
    },
};

// const chart = new ApexCharts(
//   document.querySelector("#student-registration-chart"),
//   options
// );
// chart.render();

document.addEventListener("DOMContentLoaded", () => {
    const el = document.querySelector("#student-registration-chart");

    if (!el) return; // silently skip

    const chart = new ApexCharts(el, options);
    chart.render();
});

// Reusing initialization of chart
// const studentOptions = {
//   chart: {
//     type: "line",
//     height: 300,
//   },
//   series: [
//     {
//       name: "Students",
//       data: [10, 20, 15, 30, 25],
//     },
//   ],
//   xaxis: {
//     categories: ["Jan", "Feb", "Mar", "Apr", "May"],
//   },
// };

// function safeChart(selector, options) {
//     const el = document.querySelector(selector);
//     if (!el) return;

//     const chart = new ApexCharts(el, options);
//     chart.render();
// }

// safeChart("#student-registration-chart", studentOptions);
// // safeChart("#teacher-chart", teacherOptions);
// // safeChart("#fees-chart", feesOptions);

// --------------------------------------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------

// App Php script
//  app.blade.php JS

// $(function () {
//     const token = localStorage.getItem("token");
//     const currentPath = window.location.pathname;

//     const dashboards = {
//         admin: "/admin/dashboard",
//         admission: "/admission/dashboard",
//         warden: "/warden/dashboard",
//         resident: "/resident/dashboard",
//         hod: "/hod/dashboard",
//         guest: "/guest/dashboard",
//     };

//     const fallbackRoute = "/login";

//     // ===== Helper: Cached profile =====
//     function getCachedProfile() {
//         const cached = localStorage.getItem("userProfile");
//         if (!cached) return null;
//         const data = JSON.parse(cached);
//         const now = Date.now();
//         if (now - data.timestamp > 10 * 60 * 1000) {
//             // 10 min cache
//             localStorage.removeItem("userProfile");
//             return null;
//         }
//         return data.profile;
//     }

//     function cacheProfile(profile) {
//         localStorage.setItem(
//             "userProfile",
//             JSON.stringify({
//                 profile: profile,
//                 timestamp: Date.now(),
//             })
//         );
//     }

//     // ===== Redirect if no token =====
//     if (!token) {
//         redirectTo(fallbackRoute);
//         return;
//     }

//     // ===== Try from cache first =====
//     let profile = getCachedProfile();

//     if (profile) {
//         processProfile(profile);
//     } else {
//         $.ajax({
//             url: "/api/profile",
//             method: "GET",
//             headers: {
//                 Authorization: "Bearer " + token,
//             },
//             success: function (res) {
//                 if (!res.success || !res.data) {
//                     handleLogout(true);
//                     return;
//                 }
//                 cacheProfile(res.data);
//                 processProfile(res.data);
//             },
//             error: function () {
//                 handleLogout(true);
//             },
//         });
//     }

//     // ===== Core Function =====
//     function processProfile(data) {
//         const role = data.role;
//         const allowedPrefix = "/" + role;

//         // Restrict cross-role access
//         if (!currentPath.startsWith(allowedPrefix)) {
//             Swal.fire({
//                 icon: "warning",
//                 title: "Unauthorized Access",
//                 text: "You don’t have permission to view this page.",
//                 confirmButtonColor: "#3085d6",
//             }).then(() => {
//                 redirectTo(dashboards[role] || fallbackRoute);
//             });
//             return;
//         }

//         // ==== Dynamically inject user info ====
//         $("#userName").text(data.name || "User");
//         $("#userRole").text(
//             role ? role.charAt(0).toUpperCase() + role.slice(1) : "Guest"
//         );
//         $("#userAvatar").text(
//             data.name ? data.name.charAt(0).toUpperCase() : "U"
//         );

//         // ==== Dynamic links ====
//         $("#dashboardLink").attr("href", dashboards[role] || "#");
//         $("#profileLink").attr("href", `/${role}/profile`);
//         $("#changePasswordLink").attr("href", `/${role}/change-password`);
//         $("#settingsLink").attr("href", `/${role}/settings`);
//         $("#helpLink").attr("href", `/${role}/help`);

//         // ==== Bind Logout (always working) ====
//         $(document)
//             .off("click", "#logoutBtn")
//             .on("click", "#logoutBtn", function (e) {
//                 e.preventDefault();
//                 Swal.fire({
//                     title: "Logout?",
//                     text: "You’ll be logged out from this session.",
//                     icon: "question",
//                     showCancelButton: true,
//                     confirmButtonText: "Yes, logout",
//                     cancelButtonText: "Cancel",
//                 }).then((result) => {
//                     if (result.isConfirmed) handleLogout();
//                 });
//             });
//     }

//     // ===== Logout =====
//     function handleLogout(force = false) {
//         if (!force && !token) {
//             forceLogout();
//             return;
//         }

//         $.ajax({
//             url: "/api/logout",
//             type: "POST",
//             headers: {
//                 Authorization: "Bearer " + token,
//             },
//             complete: function () {
//                 localStorage.clear();
//                 Swal.fire({
//                     icon: "success",
//                     title: "Logged out successfully",
//                     timer: 1000,
//                     showConfirmButton: false,
//                 }).then(() => redirectTo(fallbackRoute));
//             },
//         });
//     }

//     // ===== Utility Helpers =====
//     function redirectTo(path) {
//         window.location.replace(path); // Prevent back navigation
//     }

//     function forceLogout() {
//         localStorage.clear();
//         redirectTo(fallbackRoute);
//     }

//     // ===== Prevent back after logout =====
//     window.onpageshow = function (event) {
//         if (
//             event.persisted ||
//             window.performance.getEntriesByType("navigation")[0].type ===
//                 "back_forward"
//         ) {
//             if (!localStorage.getItem("token")) redirectTo(fallbackRoute);
//         }
//     };
// });
//  app.blade.php JS
// End

// --------------------------------------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------

// Admin page

// document.addEventListener("DOMContentLoaded", function () {
//     // Initially set content to 'Select an option' message
//     document.getElementById("adminContent").innerHTML = `
//             <h3>Select an option from the admin panel</h3>
//         `;

//     // Optionally, add more sections and functionality here
// });

// --------------------------------------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------

// DataTable // hidden on 20122025

function InitializeDatatable() {
    $("table").DataTable({
        responsive: {
            details: {
                type: "column",
                target: "tr",
            },
        },
        columnDefs: [
            {
                className: "dtr-control",
                orderable: false,
                targets: 0,
            }, // for +/− icon
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 },
        ],
        pageLength: 10,
        dom: "Bfrtip",
        buttons: [
            {
                extend: "copy",
                className: "btn btn-sm btn-outline-primary",
            },
            {
                extend: "csv",
                className: "btn btn-sm btn-outline-success",
            },
            {
                extend: "excel",
                className: "btn btn-sm btn-outline-info",
            },
            {
                extend: "pdfHtml5",
                className: "btn btn-sm btn-outline-danger",
            },
            {
                extend: "print",
                className: "btn btn-sm btn-outline-secondary",
            },
        ],
    });

    // ✅ Highlight row on click
    // $("table tbody").on("click", "tr", function () {
    //     if (!$(this).hasClass("child")) {
    //         $("table tbody tr").removeClass("row-selected");
    //         $(this).addClass("row-selected");
    //     }
    // });

    // ✅ Optional: Open responsive details (dropdown) automatically when row is clicked
    $("table tbody").on("click", "tr", function () {
        var row = table.row(this);
        if (row.child.isShown()) {
            row.child.hide();
            $(this).removeClass("shown");
        } else {
            row.child.show();
            $(this).addClass("shown");
        }
    });
}
