window.addEventListener('resize', drawCharts);
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawCharts);
function drawCharts(){
// wczytanie API
  fetch('dane_piechart.php')
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        console.error('Błąd z API:', data.error);
        return;
      }
      // Sprawdzenie czy dane nie są puste i wczytanie domyślnych danych
        if (!data.incomes || data.incomes.length <= 1) {
            data.incomes = [['Kategoria', 'Kwota'], ['Brak danych', 1]];
        }

        if (!data.expenses || data.expenses.length <= 1) {
            data.expenses = [['Kategoria', 'Kwota'], ['Brak danych', 1]];
        }

        // Teraz możemy tworzyć DataTable
        const incomesData = google.visualization.arrayToDataTable(data.incomes);
        const expensesData = google.visualization.arrayToDataTable(data.expenses);

//piechart incomes
      var incomesOptions = {
        title: 'Przychody',
        height: 300,
        pieSliceText: 'value', 
        is3D: true,
        backgroundColor:"rgb(238, 229, 188)",
      };
      const incomesChart = new google.visualization.PieChart(document.getElementById('incomesPiechart'));
      incomesChart.draw(incomesData, incomesOptions);
//piechart expenses
      var expensesOptions = {
        title: 'Przychody',
        height: 300,
        pieSliceText: 'value', 
        is3D: true,
        backgroundColor:"rgb(238, 229, 188)",
      };
      const expensesChart = new google.visualization.PieChart(document.getElementById('expensesPiechart'));
      expensesChart.draw(expensesData, expensesOptions);
    })
    .catch(error => console.error('Błąd pobierania danych:', error));



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
      x: {
        reverse: true,
        gridLines: {
          color: "rgba(0,0,0,0.05)"
        }
      },
      y: {
        borderDash: [5, 5],
        gridLines: {
          color: "rgba(0,0,0,0)",
          fontColor: "#fff"
        }
      }
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
