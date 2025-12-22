// dashboard section javascript

const screenWidth = window.innerWidth;
// console.log(screenWidth);

// document.getElementById("toggle-btn").addEventListener("click", function () {
//     document.getElementById("sidebar").classList.toggle("collapsed");
// });

// document
//     .getElementById("toggle-btn-small-screen")
//     .addEventListener("click", function () {
//         document.getElementById("sidebar").classList.toggle("collapsed");
//     });

// ApexCharts Config
const options = {
    chart: {
        type: "area",
        height: 300,
        toolbar: { show: false },
    },
    series: [
        {
            name: "Registrations",
            data: [500, 200, 400, 600, 300, 700, 800, 650, 400, 300, 500, 700],
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
        enabled: false,
    },
    stroke: {
        curve: "smooth",
    },
};

const chart = new ApexCharts(
    document.querySelector("#student-registration-chart"),
    options
);
chart.render();

// // dashboard bottom table section javascript

// const hostelData = [
//   { name: "Hostel One", code: "H42", floors: 5, status: "Active" },
//   { name: "Hostel Two", code: "H465", floors: 12, status: "Inactive" },
//   { name: "Hostel Three", code: "H99", floors: 8, status: "Active" },
//   { name: "Hostel Four", code: "H101", floors: 3, status: "Inactive" },
//   { name: "Hostel Five", code: "H22", floors: 6, status: "Active" },
//   { name: "Hostel Six", code: "H303", floors: 7, status: "Active" },
//   { name: "Hostel Seven", code: "H909", floors: 9, status: "Inactive" },
//   { name: "Hostel Eight", code: "H777", floors: 10, status: "Active" },
//   { name: "Hostel Nine", code: "H121", floors: 4, status: "Active" },
//   { name: "Hostel Ten", code: "H555", floors: 2, status: "Inactive" },
// ];

// let currentPage = 1;
// let rowsPerPage = parseInt(document.getElementById("rowsPerPage").value);

// function renderTable() {
//   const tableBody = document.querySelector("#hostelTable tbody");
//   const totalCount = document.getElementById("totalCount");
//   const paginationControls = document.getElementById("paginationControls");

//   const start = (currentPage - 1) * rowsPerPage;
//   const end = start + rowsPerPage;
//   const paginatedData = hostelData.slice(start, end);

//   tableBody.innerHTML = "";
//   paginatedData.forEach((item, index) => {
//     const row = `
//       <tr>
//         <td>${start + index + 1}</td>
//         <td>${item.name}</td>
//         <td>${item.code}</td>
//         <td>${item.floors}</td>
//         <td>${item.status}</td>
//         <td class="actions">
//           <button class="edit-btn">Edit</button>
//           <button class="delete-btn">Delete</button>
//         </td>
//       </tr>`;
//     tableBody.innerHTML += row;
//   });

//   totalCount.textContent = `Showing ${start + 1} to ${Math.min(
//     end,
//     hostelData.length
//   )} of ${hostelData.length} entries`;

//   // Pagination buttons
//   const totalPages = Math.ceil(hostelData.length / rowsPerPage);
//   paginationControls.innerHTML = "";

//   for (let i = 1; i <= totalPages; i++) {
//     const btn = document.createElement("button");
//     btn.classList.add("page-btn");
//     if (i === currentPage) btn.classList.add("active");
//     btn.textContent = i;
//     btn.addEventListener("click", () => {
//       currentPage = i;
//       renderTable();
//     });
//     paginationControls.appendChild(btn);
//   }
// }

// document.getElementById("rowsPerPage").addEventListener("change", (e) => {
//   rowsPerPage = parseInt(e.target.value);
//   currentPage = 1;
//   renderTable();
// });

// renderTable();

// // bed status section javascript

// const bedData = [
//   {
//     hostel: "Hostel One",
//     floor: 42,
//     room: 5465,
//     type: 5,
//     bedNum: 4542,
//     status: "Occupied",
//   },
//   {
//     hostel: "Hostel Two",
//     floor: 465,
//     room: 4565,
//     type: 12,
//     bedNum: 45545,
//     status: "Available",
//   },
//   {
//     hostel: "Hostel Three",
//     floor: 4,
//     room: 400,
//     type: 2,
//     bedNum: 410,
//     status: "Occupied",
//   },
//   {
//     hostel: "Hostel Four",
//     floor: 7,
//     room: 789,
//     type: 1,
//     bedNum: 800,
//     status: "Available",
//   },
//   {
//     hostel: "Hostel Five",
//     floor: 10,
//     room: 900,
//     type: 4,
//     bedNum: 901,
//     status: "Occupied",
//   },
//   {
//     hostel: "Hostel Six",
//     floor: 6,
//     room: 321,
//     type: 3,
//     bedNum: 322,
//     status: "Available",
//   },
//   {
//     hostel: "Hostel Seven",
//     floor: 5,
//     room: 123,
//     type: 6,
//     bedNum: 124,
//     status: "Occupied",
//   },
//   {
//     hostel: "Hostel Eight",
//     floor: 3,
//     room: 111,
//     type: 5,
//     bedNum: 112,
//     status: "Available",
//   },
//   {
//     hostel: "Hostel Nine",
//     floor: 8,
//     room: 876,
//     type: 4,
//     bedNum: 877,
//     status: "Occupied",
//   },
//   {
//     hostel: "Hostel Ten",
//     floor: 2,
//     room: 101,
//     type: 2,
//     bedNum: 102,
//     status: "Available",
//   },
// ];

// let activePage = 1;
// let entriesPerPage = 5;

// const bedTableBody = document.getElementById("bedTableBody");
// const paginationControls = document.getElementById("paginationControls");
// const searchInput = document.getElementById("searchInput");
// const totalPagesText = document.getElementById("totalPagesText");
// const rowsPerPageSelect = document.getElementById("rowsPerPageSelect");

// function displayTable(data, page = 1) {
//   const start = (page - 1) * entriesPerPage;
//   const end = start + entriesPerPage;
//   const slicedData = data.slice(start, end);

//   bedTableBody.innerHTML = "";
//   slicedData.forEach((item, index) => {
//     bedTableBody.innerHTML += `
//       <tr>
//         <td>${start + index + 1}.</td>
//         <td>${item.hostel}</td>
//         <td>${item.floor}</td>
//         <td>${item.room}</td>
//         <td>${item.type}</td>
//         <td>${item.bedNum}</td>
//         <td>${item.status}</td>
//         <td class="actions">
//           <button class="edit-btn">Edit</button>
//           <button class="delete-btn">Delete</button>
//         </td>
//       </tr>`;
//   });

//   const totalPages = Math.ceil(data.length / entriesPerPage);
//   totalPagesText.innerText = `of ${totalPages}`;
//   displayPagination(data, totalPages);
// }

// function displayPagination(data, totalPages) {
//   paginationControls.innerHTML = "";

//   if (activePage > 1) {
//     paginationControls.innerHTML += `<button onclick="changePage(${
//       activePage - 1
//     })">‹</button>`;
//   }

//   for (let i = 1; i <= totalPages; i++) {
//     paginationControls.innerHTML += `
//       <button onclick="changePage(${i})" class="${
//       activePage === i ? "active" : ""
//     }">${i}</button>`;
//   }

//   if (activePage < totalPages) {
//     paginationControls.innerHTML += `<button onclick="changePage(${
//       activePage + 1
//     })">›</button>`;
//   }
// }

// function changePage(page) {
//   activePage = page;
//   filterAndRender();
// }

// function filterAndRender() {
//   const query = searchInput.value.toLowerCase();
//   const filtered = bedData.filter((b) =>
//     Object.values(b).some((val) => val.toString().toLowerCase().includes(query))
//   );
//   displayTable(filtered, activePage);
// }

// searchInput.addEventListener("input", () => {
//   activePage = 1;
//   filterAndRender();
// });

// rowsPerPageSelect.addEventListener("change", (e) => {
//   entriesPerPage = parseInt(e.target.value);
//   activePage = 1;
//   filterAndRender();
// });

// window.onload = () => {
//   filterAndRender();
// };
