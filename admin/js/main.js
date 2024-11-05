document.addEventListener("DOMContentLoaded", function() {
  const hamBurger = document.querySelector(".toggle-btn");
  const sidebar = document.querySelector("#sidebar");

  if(hamBurger && sidebar) {
      hamBurger.addEventListener("click", function () {
          sidebar.classList.toggle("expand");
      });
  }
});

// // Tampilin data pakai js
// document.addEventListener("DOMContentLoaded", function() {
//   // Menangani klik pada tautan sidebar
//   const sidebarLinks = document.querySelectorAll('.sidebar-link');

//   sidebarLinks.forEach(link => {
//       link.addEventListener('click', function(event) {
//           event.preventDefault(); // Mencegah navigasi default
//           const url = this.getAttribute('data-url'); // Ambil URL dari atribut data-url
//           loadContent(url); // Panggil fungsi untuk memuat konten
//       });
//   });

//   function loadContent(url) {
//       var xhttp = new XMLHttpRequest();
//       xhttp.onreadystatechange = function() {
//           if (this.readyState == 4 && this.status == 200) {
//               document.getElementById('tampil_data').innerHTML = this.responseText; // Tampilkan konten
//           }
//       };
//       xhttp.open("GET", url, true);
//       xhttp.send();
//   }
// });