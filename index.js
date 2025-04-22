window.addEventListener('resize', drawCharts);
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawCharts);

function drawCharts() {

  var data1 = google.visualization.arrayToDataTable([
    ['Task', 'Hours per Day'],
    ['Rachunki', 800],
    ['Żywność', 1350],
    ['Ubrania', 350],
    ['Hobby', 200],
    ['Inne', 120],
  ]);

  var data2 = google.visualization.arrayToDataTable([
    ['Task', 'Hours per Day'],
    ['Wynagrodzenie', 6700],
    ['Premia', 1300],
    ['Sprzedaż Allegro', 350],
    ['Sprzedaz OLX', 140],
    ['Inne', 50]
  ]);

  var options1 = {
    title: 'Wydatki',
    height: 300,
    pieSliceText: 'value', 
    is3D: true,
    backgroundColor:"rgb(238, 229, 188)",
  };

  var options2 = {
    title: 'Przychody',
    height: 300,
    colors: ['#4CAF50', '#FFC107', '#03A9F4', '#E91E63', '#9C27B0'],
    pieSliceText: 'value', // procenty
    is3D: true,
    backgroundColor:"rgb(238, 229, 188)",
  };

  // Rysowanie
  var chart1 = new google.visualization.PieChart(document.getElementById('piechart1'));
  chart1.draw(data1, options1);

  var chart2 = new google.visualization.PieChart(document.getElementById('piechart2'));
  chart2.draw(data2, options2);
}
window.theme = {
    primary: '#007bff' // kolor główny wykresu
  };

  new Chart(document.getElementById("chartjs-line"), {
    type: "line",
    data: {
      labels: ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"],
      datasets: [{
        label: "Dochody (PLN)",
        fill: true,
        backgroundColor: "transparent",
        borderColor: window.theme.primary,
        data: [2115, 1562, 1584, 1892, 1487, 2223, 2966, 2448, 2905, 3838, 2917, 3327]
      }, {
        label: "Wydatki (PLN)",
        fill: true,
        backgroundColor: "transparent",
        borderColor: "#adb5bd",
        borderDash: [4, 4],
        data: [958, 724, 629, 883, 915, 1214, 1476, 1212, 1554, 2128, 1466, 1827]
      }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
      scales: {
        xAxes: [{
          reverse: true,
          gridLines: {
            color: "rgba(0,0,0,0.05)"
          }
        }],
        yAxes: [{
          borderDash: [5, 5],
          gridLines: {
            color: "rgba(0,0,0,0)",
            fontColor: "#fff"
          }
        }]
      }
    }
  });
  function toggleList() {
    const lista = document.getElementById("listaDanych");
    lista.classList.toggle("pokazana");
  }
  function toggleList2() {
    const lista = document.getElementById("listaDanych2");
    lista.classList.toggle("pokazana");
  }
  function toggleList3() {
    const lista = document.getElementById("listaDanych3");
    lista.classList.toggle("pokazana");
  }