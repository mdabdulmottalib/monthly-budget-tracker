</div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
      var options = {
        series: [
          {
            name: "series1",
            data: [31, 40, 28, 51, 42, 109, 100],
          },
          {
            name: "series2",
            data: [11, 32, 45, 32, 34, 52, 41],
          },
        ],
        chart: {
          height: 350,
          type: "area",
          toolbar: {
            show: false,
          },
        },
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: "smooth",
        },
        xaxis: {
          type: "datetime",
          categories: [
            "2018-09-19T00:00:00.000Z",
            "2018-09-19T01:30:00.000Z",
            "2018-09-19T02:30:00.000Z",
            "2018-09-19T03:30:00.000Z",
            "2018-09-19T04:30:00.000Z",
            "2018-09-19T05:30:00.000Z",
            "2018-09-19T06:30:00.000Z",
          ],
        },
        tooltip: {
          x: {
            format: "dd/MM/yy HH:mm",
          },
        },
      };

      var chart = new ApexCharts(document.querySelector("#chart"), options);
      chart.render();

      const data1 = {
        labels: ["Red", "Blue", "Yellow"],
        datasets: [
          {
            label: "My First Dataset",
            data: [300, 50, 100],
            backgroundColor: [
              "rgb(255, 99, 132)",
              "rgb(54, 162, 235)",
              "rgb(255, 205, 86)",
            ],
            hoverOffset: 4,
          },
        ],
      };

      const data2 = {
        labels: ["Green", "Purple", "Orange"],
        datasets: [
          {
            label: "My Second Dataset",
            data: [200, 150, 250],
            backgroundColor: [
              "rgb(75, 192, 192)",
              "rgb(153, 102, 255)",
              "rgb(255, 159, 64)",
            ],
            hoverOffset: 4,
          },
        ],
      };

      const config1 = {
        type: "pie",
        data: data1,
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false, // Hide the legend
            },
            tooltip: {
              enabled: true,
            },
          },
        },
      };

      const config2 = {
        type: "pie",
        data: data2,
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false, // Hide the legend
            },
            tooltip: {
              enabled: true,
            },
          },
        },
      };

      // Render the first pie chart
      const myPieChart1 = new Chart(
        document.getElementById("myPieChart1"),
        config1
      );

      // Render the second pie chart
      const myPieChart2 = new Chart(
        document.getElementById("myPieChart2"),
        config2
      );

      const months = new Date().getMonth(); // Get the current month (0-11)

      // Array of month names
      const monthNames = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ];
      const showMonth = document.getElementById("show-month");
      showMonth.textContent = monthNames[months];

      const menuButton = document.querySelectorAll(".menu-button");
      const sideBarMenu = document.querySelector(".side-bar-menu");

      menuButton.forEach((button) => {
        button.addEventListener("click", () => {
          sideBarMenu.classList.toggle("translate-x-0");
        });
      });
    </script>
  </body>
</html>