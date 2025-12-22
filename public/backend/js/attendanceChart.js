// DAILY ATTENDANCE LINE CHART
var optionsLine = {
  chart: {
    type: 'line',
    height: 280,
    toolbar: { show: false }
  },
  series: [{
    name: 'Attendance',
    data: [65, 78, 60, 82, 91, 55, 72, 40, 63, 77, 70, 58, 45, 69, 62, 71]
  }],
  xaxis: {
    categories: ['01 Aug', '02 Aug', '03 Aug', '04 Aug', '05 Aug', '06 Aug', '07 Aug', '08 Aug', '09 Aug', '10 Aug', '11 Aug', '12 Aug', '13 Aug', '14 Aug', '15 Aug', '16 Aug']
  },
  stroke: {
    curve: 'smooth',
    width: 3
  },
  markers: {
    size: 5,
    colors: ['#0b2948'],
    strokeColors: '#fff',
    strokeWidth: 2
  },
  dataLabels: {
    enabled: true,
    formatter: (val, { dataPointIndex }) => dataPointIndex === 6 ? '91%' : ''
  },
  tooltip: {
    y: {
      formatter: val => `${val}%`
    }
  },
  fill: {
    type: 'gradient',
    gradient: {
      shadeIntensity: 1,
      opacityFrom: 0.3,
      opacityTo: 0.1,
      stops: [0, 100]
    }
  },
  colors: ['#0b2948']
};

var chartLine = new ApexCharts(document.querySelector("#dailyAttendanceChart"), optionsLine);
chartLine.render();

// WEEKLY ATTENDANCE BAR CHART
var optionsBar = {
  chart: {
    type: 'bar',
    height: 220,
    toolbar: { show: false }
  },
  series: [{
    name: 'Attendance',
    data: [40, 60, 86, 55]
  }],
  xaxis: {
    categories: ['Week 1', 'Week 2', 'Week 3', 'Week 4']
  },
  dataLabels: {
    enabled: true,
    formatter: val => `${val}%`
  },
  plotOptions: {
    bar: {
      columnWidth: '40%',
      borderRadius: 6
    }
  },
  colors: ['#d1dce5', '#d1dce5', '#0b2948', '#d1dce5']
};

var chartBar = new ApexCharts(document.querySelector("#weeklyAttendanceChart"), optionsBar);
chartBar.render();
