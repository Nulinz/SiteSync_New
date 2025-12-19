<!-- GSAP -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.3/gsap.min.js"></script>

<!-- Icons -->
<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>

<!-- Lazy Loading -->
<script src="https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js"></script>

<!-- Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<!-- Script -->
<script src="{{ asset('assets/js/script.js') }}"></script>

<!-- Bootstrap Tooltip -->
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl));
</script>

<!-- File Input Filename -->
<script>
    function updateFileName(inputId, textId) {
        const fileInput = document.getElementById(inputId);
        const fileText = document.getElementById(textId);
        fileText.textContent = fileInput.files.length > 0 ? fileInput.files[0].name : "Click to upload image";
    }
</script>

<!-- Dark Theme Logo and Theme Switching -->
<script>
    const themeSwitch = document.querySelector('#themeSwitcher');
    const lightLogo = document.querySelector('.lightLogo');
    const darkLogo = document.querySelector('.darkLogo');
    const defaultTheme = localStorage.getItem('theme') || 'theme-light';
    setTheme(defaultTheme);
    themeSwitch.checked = defaultTheme === 'theme-dark';
    themeSwitch.addEventListener('change', () => {
        const selectedTheme = themeSwitch.checked ? 'theme-dark' : 'theme-light';
        setTheme(selectedTheme);
    });
    function setTheme(theme) {
        document.documentElement.className = theme;
        localStorage.setItem('theme', theme);

        if (theme === 'theme-dark') {
            lightLogo.style.display = 'none';
            darkLogo.style.display = 'block';
        } else {
            lightLogo.style.display = 'block';
            darkLogo.style.display = 'none';
        }
    }
</script>

</body>

</html>