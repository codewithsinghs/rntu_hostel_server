const thumpTimeData = [
    { morning: "12:00 PM", night: "12:00 PM" },
    { morning: "01:56 PM", night: "01:56 PM" },
    { morning: "09:30 AM", night: "09:30 PM" },
    { morning: "10:10 AM", night: "10:10 PM" },
    { morning: "11:00 AM", night: "11:00 PM" },
    { morning: "12:00 PM", night: "12:00 PM" },
    { morning: "01:56 PM", night: "01:56 PM" },
    { morning: "08:56 AM", night: "08:56 PM" },
    { morning: "07:00 AM", night: "07:00 PM" },
    { morning: "06:30 AM", night: "06:30 PM" },
  ];

  const leaveListData = [
    {
      out: "12:00 PM",
      date: "26 Feb 2025",
      duration: "5 Days",
      arrival: "02 March 2025",
      status: "Approve",
    },
    {
      out: "01:56 PM",
      date: "11 Feb 2025",
      duration: "2 Days",
      arrival: "13 March 2025",
      status: "Approve",
    },
    {
      out: "02:00 PM",
      date: "20 Jan 2025",
      duration: "3 Days",
      arrival: "23 Jan 2025",
      status: "Approve",
    },
    {
      out: "03:10 PM",
      date: "10 Mar 2025",
      duration: "4 Days",
      arrival: "14 Mar 2025",
      status: "Approve",
    },
    {
      out: "11:30 AM",
      date: "01 Apr 2025",
      duration: "2 Days",
      arrival: "03 Apr 2025",
      status: "Approve",
    },
    {
      out: "08:15 AM",
      date: "10 May 2025",
      duration: "1 Day",
      arrival: "11 May 2025",
      status: "Approve",
    },
  ];

  const AttendanceListData = [
    {
        breakfast: "Attend",
        lunch: "Absence",
        snacks: "Attend",
        dinner: "Attend",
    },
    {
        breakfast: "Absence",
        lunch: "Absence",
        snacks: "Absence",
        dinner: "Absence",
    }
  ]

  const CheckOutCheckinListData = [
    {
        date: "26 Feb 2025",
        checkOut: "12:00 PM",
        checkIn: "12:00 PM",
        urgency: "Yes",
        status: "Approve",
    },
    {
        date: "11 Feb 2025",
        checkOut: "12:00 PM",
        checkIn: "12:00 PM",
        urgency: "No",
        status: "Reject",
    }
  ]

  const FineListData = [
    {
        reason: "",
        fineAmount: "1000",
        payment: "Payment",
    },
    {
        reason: "",
        fineAmount: "5000",
        payment: "Pending",
    }
  ]

  const ItemListData = [
    {
        name: "Cooler",
        takenOn: "26 Feb 2025",
        duration: "5 Days",
        returnItem: "return",
        Price: "1000",
        payment: "Payment",
    },{
        name: "AC",
        takenOn: "11 Feb 2025",
        duration: "2 Days",
        returnItem: "13 March 2025",
        Price: "5000",
        payment: "Pending",
    }
  ]

  function paginate(data, page, rows) {
    const start = (page - 1) * rows;
    return data.slice(start, start + rows);
  }

  function renderThumpTimeTable(page = 1, rows = 5) {
    const tbody = document.getElementById("thumpTimeBody");
    const pagination = document.getElementById("thumpTimePagination");
    const data = paginate(thumpTimeData, page, rows);

    tbody.innerHTML = "";
    data.forEach((entry, index) => {
      tbody.innerHTML += `
        <tr>
          <td>${(page - 1) * rows + index + 1}</td>
          <td>${entry.morning}</td>
          <td>${entry.night}</td>
          <td><button>View</button></td>
        </tr>
      `;
    });

    generatePagination(thumpTimeData.length, page, rows, "thumpTime");
  }

  function renderLeaveListTable(page = 1, rows = 5) {
    const tbody = document.getElementById("leaveListBody");
    const pagination = document.getElementById("leaveListPagination");
    const data = paginate(leaveListData, page, rows);

    tbody.innerHTML = "";
    data.forEach((entry, index) => {
      tbody.innerHTML += `
        <tr>
          <td>${(page - 1) * rows + index + 1}</td>
          <td>${entry.out}</td>
          <td>${entry.date}</td>
          <td>${entry.duration}</td>
          <td>${entry.arrival}</td>
          <td><span class="status">${entry.status}</span></td>
          <td><button>View</button></td>
        </tr>
      `;
    });

    generatePagination(leaveListData.length, page, rows, "leaveList");
  }

  function renderAttendanceListTable(page = 1, rows = 5) {
    const tbody = document.getElementById("AttendanceListBody");
    const pagination = document.getElementById("AttendanceListPagination");
    const data = paginate(AttendanceListData, page, rows);

    tbody.innerHTML = "";
    data.forEach((entry, index) => {
      tbody.innerHTML += `
        <tr>
          <td>${(page - 1) * rows + index + 1}</td>
          <td style=""><span class="${entry.breakfast == "Absence" ? "absense" : "attend"}">${entry.breakfast}</span></td>
          <td style=""><span class="${entry.lunch == "Absence" ? "absense" : "attend"}">${entry.lunch}</span></td>
          <td style=""><span class="${entry.snacks == "Absence" ? "absense" : "attend"}">${entry.snacks}</span></td>
          <td style=""><span class="${entry.dinner == "Absence" ? "absense" : "attend"}">${entry.dinner}</span></td>
          <td><button>View</button></td>
        </tr>
      `;
    });

    generatePagination(AttendanceListData.length, page, rows, "attendanceList");

  }

  function renderStudentCheckOutListTable(page = 1, rows = 5) {
    const tbody = document.getElementById("StudentCheckOutListBody");
    const pagination = document.getElementById("StudentCheckOutListPagination");
    const data = paginate(CheckOutCheckinListData, page, rows);

    tbody.innerHTML = "";
    data.forEach((entry, index) => {
      tbody.innerHTML += `
        <tr>
          <td>${(page - 1) * rows + index + 1}</td>
          <td style=""><span class="">${entry.date}</span></td>
          <td style=""><span class="">${entry.checkOut}</span></td>
          <td style=""><span class="">${entry.checkIn}</span></td>
          <td style=""><span class="${entry.urgency == "No" ? "" : "absense"}">${entry.urgency}</span></td>
          <td style=""><span class="${entry.status == "Approve" ? "attend" : "absense"}">${entry.status}</span></td>
          <td><button>View</button></td>
        </tr>
      `;
    });

    generatePagination(CheckOutCheckinListData.length, page, rows, "studentCheckOutList");

  }

  function renderFineListTable(page = 1, rows = 5) {
    const tbody = document.getElementById("FineListBody");
    const pagination = document.getElementById("FineListPagination");
    const data = paginate(FineListData, page, rows);

    tbody.innerHTML = "";
    data.forEach((entry, index) => {
      tbody.innerHTML += `
        <tr>
          <td>${(page - 1) * rows + index + 1}</td>
          <td><span class="">${entry.reason}</span></td>
          <td style=""><span class="">${entry.fineAmount}</span></td>
          <td style=""><span class="${entry.payment == "Payment" ? "attend" : "pending"}">${entry.payment}</span></td>
        </tr>
      `;
    });

    generatePagination(FineListData.length, page, rows, "fineList");

  }

  function renderItemListTable(page = 1, rows = 5) {
    const tbody = document.getElementById("ItemListBody");
    const pagination = document.getElementById("ItemListPagination");
    const data = paginate(ItemListData, page, rows);

    tbody.innerHTML = "";
    data.forEach((entry, index) => {
      tbody.innerHTML += `
        <tr>
          <td>${(page - 1) * rows + index + 1}</td>
          <td><span class="">${entry.name}</span></td>
          <td style=""><span class="">${entry.takenOn}</span></td>
          <td style=""><span class="">${entry.duration}</span></td>
          <td style=""><span class="">${entry.returnItem}</span></td>
          <td style=""><span class="">${entry.price}</span></td>
          <td style=""><span class="${entry.payment == "Payment" ? "attend" : "pending"}">${entry.payment}</span></td>
        </tr>
      `;
    });

    generatePagination(ItemListData.length, page, rows, "itemList");

  }

  function generatePagination(total, currentPage, rows, type) {
  const totalPages = Math.ceil(total / rows);
  const container = document.getElementById(`${type}Pagination`);
  if (!container) {
    console.warn(`Pagination container with id="${type}Pagination" not found.`);
    return;
  }

  container.innerHTML = "";

  for (let i = 1; i <= totalPages; i++) {
    const btn = document.createElement("button");
    btn.textContent = i;
    btn.classList.toggle("active", i === currentPage);
    btn.onclick = () =>
      type === "thumpTime"
        ? renderThumpTimeTable(i, rows)
        : type === "leaveList"
        ? renderLeaveListTable(i, rows)
        : type === "attendanceList"
        ? renderAttendanceListTable(i, rows)
        : type === "studentCheckOutList"
        ? renderStudentCheckOutListTable(i, rows)
        : type === "fineList"
        ? renderFineListTable(i, rows)
        : type === "itemList"
        ? renderItemListTable(i, rows)
        : null;
    container.appendChild(btn);
  }
}


  function changeRows(selectEl, type) {
  const rows = parseInt(selectEl.value);

  switch (type) {
    case "thumpTime":
      renderThumpTimeTable(1, rows);
      break;
    case "leaveList":
      renderLeaveListTable(1, rows);
      break;
    case "attendanceList":
      renderAttendanceListTable(1, rows);
      break;
    case "studentCheckOutList":
      renderStudentCheckOutListTable(1, rows);
      break;
    case "fineList":
      renderFineListTable(1, rows);
      break;
    case "itemList":
      renderItemListTable(1, rows);
      break;
    default:
      console.warn(`Unknown type passed to changeRows(): ${type}`);
  }
}


  // Initialize tables
  renderThumpTimeTable();
  renderLeaveListTable();
  renderAttendanceListTable();
  renderStudentCheckOutListTable();
  renderFineListTable();
  renderItemListTable();