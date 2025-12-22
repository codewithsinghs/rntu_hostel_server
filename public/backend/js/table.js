// ========== HOSTEL TABLE ========== //
// if (document.querySelector("#hostelListSection")) {
//     (function setupHostelTable() {
//         const hostelData = [
//             { name: "Hostel One", code: "H42", floors: 5, status: "Active" },
//             {
//                 name: "Hostel Two",
//                 code: "H465",
//                 floors: 12,
//                 status: "Inactive",
//             },
//             { name: "Hostel Three", code: "H99", floors: 8, status: "Active" },
//             {
//                 name: "Hostel Four",
//                 code: "H101",
//                 floors: 3,
//                 status: "Inactive",
//             },
//             { name: "Hostel Five", code: "H22", floors: 6, status: "Active" },
//             { name: "Hostel Six", code: "H303", floors: 7, status: "Active" },
//             {
//                 name: "Hostel Seven",
//                 code: "H909",
//                 floors: 9,
//                 status: "Inactive",
//             },
//             {
//                 name: "Hostel Eight",
//                 code: "H777",
//                 floors: 10,
//                 status: "Active",
//             },
//             { name: "Hostel Nine", code: "H121", floors: 4, status: "Active" },
//             { name: "Hostel Ten", code: "H555", floors: 2, status: "Inactive" },
//         ];

//         const tableBody = document.querySelector("#hostelTable tbody");
//         const totalCount = document.getElementById("totalCount");
//         const paginationControls = document.getElementById(
//             "hostelPaginationControls"
//         );
//         const rowsPerPageSelect = document.getElementById("rowsPerPage");

//         let currentPage = 1;
//         let rowsPerPage = parseInt(rowsPerPageSelect.value);

//         function renderTable() {
//             const start = (currentPage - 1) * rowsPerPage;
//             const end = start + rowsPerPage;
//             const paginatedData = hostelData.slice(start, end);

//             tableBody.innerHTML = "";
//             paginatedData.forEach((item, index) => {
//                 tableBody.innerHTML += `
//             <tr>
//               <td>${start + index + 1}</td>
//               <td>${item.name}</td>
//               <td>${item.code}</td>
//               <td>${item.floors}</td>
//               <td>${item.status}</td>
//               <td class="actions">
//                 <button class="edit-btn">Edit</button>
//                 <button class="delete-btn">Delete</button>
//               </td>
//             </tr>`;
//             });

//             totalCount.textContent = `Showing ${start + 1} to ${Math.min(
//                 end,
//                 hostelData.length
//             )} of ${hostelData.length} entries`;

//             const totalPages = Math.ceil(hostelData.length / rowsPerPage);
//             renderPagination(totalPages);
//         }

//         function renderPagination(totalPages) {
//             paginationControls.innerHTML = "";

//             for (let i = 1; i <= totalPages; i++) {
//                 const btn = document.createElement("button");
//                 btn.classList.add("page-btn");
//                 if (i === currentPage) btn.classList.add("active");
//                 btn.textContent = i;
//                 btn.addEventListener("click", () => {
//                     currentPage = i;
//                     renderTable();
//                 });
//                 paginationControls.appendChild(btn);
//             }
//         }

//         rowsPerPageSelect.addEventListener("change", (e) => {
//             rowsPerPage = parseInt(e.target.value);
//             currentPage = 1;
//             renderTable();
//         });

//         renderTable();
//     })();
// }

// ========== BED TABLE ========== //
// (function setupBedTable() {
//     const bedData = [
//         {
//             hostel: "Hostel One",
//             floor: 42,
//             room: 5465,
//             type: 5,
//             bedNum: 4542,
//             status: "Occupied",
//         },
//         {
//             hostel: "Hostel Two",
//             floor: 465,
//             room: 4565,
//             type: 12,
//             bedNum: 45545,
//             status: "Available",
//         },
//         {
//             hostel: "Hostel Three",
//             floor: 4,
//             room: 400,
//             type: 2,
//             bedNum: 410,
//             status: "Occupied",
//         },
//         {
//             hostel: "Hostel Four",
//             floor: 7,
//             room: 789,
//             type: 1,
//             bedNum: 800,
//             status: "Available",
//         },
//         {
//             hostel: "Hostel Five",
//             floor: 10,
//             room: 900,
//             type: 4,
//             bedNum: 901,
//             status: "Occupied",
//         },
//         {
//             hostel: "Hostel Six",
//             floor: 6,
//             room: 321,
//             type: 3,
//             bedNum: 322,
//             status: "Available",
//         },
//         {
//             hostel: "Hostel Seven",
//             floor: 5,
//             room: 123,
//             type: 6,
//             bedNum: 124,
//             status: "Occupied",
//         },
//         {
//             hostel: "Hostel Eight",
//             floor: 3,
//             room: 111,
//             type: 5,
//             bedNum: 112,
//             status: "Available",
//         },
//         {
//             hostel: "Hostel Nine",
//             floor: 8,
//             room: 876,
//             type: 4,
//             bedNum: 877,
//             status: "Occupied",
//         },
//         {
//             hostel: "Hostel Ten",
//             floor: 2,
//             room: 101,
//             type: 2,
//             bedNum: 102,
//             status: "Available",
//         },
//     ];

//     const tableBody = document.getElementById("bedTableBody");
//     const paginationControls = document.getElementById("bedPaginationControls");
//     const searchInput = document.getElementById("searchInput"); // may be null
//     const totalPagesText = document.getElementById("totalPagesText");
//     const rowsPerPageSelect = document.getElementById("rowsPerPageSelect");

//     let activePage = 1;
//     let entriesPerPage = parseInt(rowsPerPageSelect?.value || 5);

//     function filterData() {
//         const query = searchInput?.value?.toLowerCase() || "";
//         return bedData.filter((b) =>
//             Object.values(b).some((val) =>
//                 val.toString().toLowerCase().includes(query)
//             )
//         );
//     }

//     function renderTable(data, page = 1) {
//         const start = (page - 1) * entriesPerPage;
//         const end = start + entriesPerPage;
//         const paginated = data.slice(start, end);

//         tableBody.innerHTML = "";
//         paginated.forEach((item, index) => {
//             tableBody.innerHTML += `
//         <tr>
//           <td>${start + index + 1}.</td>
//           <td>${item.hostel}</td>
//           <td>${item.floor}</td>
//           <td>${item.room}</td>
//           <td>${item.type}</td>
//           <td>${item.bedNum}</td>
//           <td>${item.bedNum}</td>
//           <td>${item.status}</td>
//           <td class="actions">
//             <button class="edit-btn">Edit</button>
//             <button class="delete-btn">Delete</button>
//           </td>
//         </tr>`;
//         });

//         const totalPages = Math.ceil(data.length / entriesPerPage);
//         totalPagesText.innerText = `of ${totalPages}`;
//         renderPagination(data, totalPages);
//     }

//     function renderPagination(data, totalPages) {
//         paginationControls.innerHTML = "";

//         if (activePage > 1) {
//             const prev = document.createElement("button");
//             prev.textContent = "‹";
//             prev.addEventListener("click", () => changePage(activePage - 1));
//             paginationControls.appendChild(prev);
//         }

//         for (let i = 1; i <= totalPages; i++) {
//             const pageBtn = document.createElement("button");
//             pageBtn.textContent = i;
//             if (i === activePage) pageBtn.classList.add("active");
//             pageBtn.addEventListener("click", () => changePage(i));
//             paginationControls.appendChild(pageBtn);
//         }

//         if (activePage < totalPages) {
//             const next = document.createElement("button");
//             next.textContent = "›";
//             next.addEventListener("click", () => changePage(activePage + 1));
//             paginationControls.appendChild(next);
//         }
//     }

//     function changePage(page) {
//         activePage = page;
//         const filtered = filterData();
//         renderTable(filtered, activePage);
//     }

//     // Attach search input listener only if input exists
//     if (searchInput) {
//         searchInput.addEventListener("input", () => {
//             activePage = 1;
//             const filtered = filterData();
//             renderTable(filtered, activePage);
//         });
//     }

//     rowsPerPageSelect.addEventListener("change", (e) => {
//         entriesPerPage = parseInt(e.target.value);
//         activePage = 1;
//         const filtered = filterData();
//         renderTable(filtered, activePage);
//     });

//     // Initial render
//     const filtered = filterData();
//     renderTable(filtered, activePage);
// })();
