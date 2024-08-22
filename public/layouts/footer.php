</main>
    </div>

    <style>
      .dropDown {
        position: relative;
      }

      .dropDownMenu {
        display: none;
      }

      .dropDown:hover > .dropDownMenu {
        display: block;
        position: absolute;
        width: 200px;
        border-radius: 10px;
        top: 100%;
        right: 10px;
      }
    </style>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        var menuItems = document.querySelectorAll(".menu-item");
        menuItems.forEach(function (item) {
          item.addEventListener("click", function () {
            var submenu = this.nextElementSibling;
            if (submenu && submenu.classList.contains("submenu")) {
              submenu.classList.toggle("submenu-open");
              menuItems.forEach(function (el) {
                if (el !== item) {
                  var otherSubmenu = el.nextElementSibling;
                  if (
                    otherSubmenu &&
                    otherSubmenu.classList.contains("submenu")
                  ) {
                    otherSubmenu.classList.remove("submenu-open");
                  }
                }
              });
            }
          });
        });

        // Highlight the active menu item
        var activePage = '<?php echo $currentPage; ?>';
        var links = document.querySelectorAll("nav a, .submenu a");
        links.forEach(function (link) {
          if (link.href.includes(activePage)) {
            link.classList.add("active");
          }
        });

        // Check sidebar state from localStorage
        const sidebarState = localStorage.getItem("sidebarState");
        const sidebar = document.getElementById("sidebar");
        const mainNavMenu = document.querySelector(".main-nav-menu");
        const navListHeading = document.querySelector(".navListHeading");
        const allNavLinkText = document.querySelectorAll(".listen");

        if (sidebarState === "collapsed") {
          sidebar.classList.add("sidebar-collapsed");
          mainNavMenu.classList.add("MainNav");
          navListHeading.style.display = "none";
          allNavLinkText.forEach((item) => {
            item.style.display = "none";
          });
        } else {
          sidebar.classList.add("sidebar-expanded");
        }
      });

      function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        const mainNavMenu = document.querySelector(".main-nav-menu");
        const navListHeading = document.querySelector(".navListHeading");
        const allNavLinkText = document.querySelectorAll(".listen");

        if (sidebar.classList.contains("sidebar-expanded")) {
          sidebar.classList.remove("sidebar-expanded");
          sidebar.classList.add("sidebar-collapsed");
          mainNavMenu.classList.add("MainNav");
          navListHeading.style.display = "none";
          allNavLinkText.forEach((item) => {
            item.style.display = "none";
          });
          localStorage.setItem("sidebarState", "collapsed");
        } else {
          sidebar.classList.remove("sidebar-collapsed");
          sidebar.classList.add("sidebar-expanded");
          mainNavMenu.classList.remove("MainNav");
          navListHeading.style.display = "block";
          allNavLinkText.forEach((item) => {
            item.style.display = "inline";
          });
          localStorage.setItem("sidebarState", "expanded");
        }
      }
    </script>
    <!-- Toggle end -->
       <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <!-- Bill Reminder Calendar -->
       <script>
        document.querySelectorAll('.checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    this.nextElementSibling.style.textDecoration = 'line-through';
                    this.nextElementSibling.style.backgroundColor = '#D1D5DB';
                } else {
                    this.nextElementSibling.style.textDecoration = 'none';
                    this.nextElementSibling.style.backgroundColor = 'transparent';
                }
            });
        });
    </script>
    <!-- Bill Reminder End -->
</body>
</html>

<?php
// End output buffering and send the output
ob_end_flush();
?>