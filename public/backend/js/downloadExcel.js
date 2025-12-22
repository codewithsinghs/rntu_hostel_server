function downloadTableAsExcel(tableId, filename = "table_data.xls") {
    const table = document.getElementById(tableId);
    if (!table) {
      alert("Table not found: " + tableId);
      return;
    }

    // Clone the table and remove any elements you don’t want exported (e.g., action buttons)
    const clonedTable = table.cloneNode(true);
    const actionCells = clonedTable.querySelectorAll(".actions");
    actionCells.forEach(cell => cell.remove());

    // Create HTML for Excel
    const html = `
      <html xmlns:o="urn:schemas-microsoft-com:office:office"
            xmlns:x="urn:schemas-microsoft-com:office:excel"
            xmlns="http://www.w3.org/TR/REC-html40">
        <head>
          <!--[if gte mso 9]>
          <xml>
            <x:ExcelWorkbook>
              <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                  <x:Name>${filename}</x:Name>
                  <x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions>
                </x:ExcelWorksheet>
              </x:ExcelWorksheets>
            </x:ExcelWorkbook>
          </xml>
          <![endif]-->
        </head>
        <body>
          ${clonedTable.outerHTML}
        </body>
      </html>`;

    const blob = new Blob([html], { type: "application/vnd.ms-excel" });
    const url = URL.createObjectURL(blob);

    const a = document.createElement("a");
    a.href = url;
    a.download = filename;
    a.click();
    URL.revokeObjectURL(url);
  }