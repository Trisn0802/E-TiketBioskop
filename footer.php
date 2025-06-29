<style>
        /* Animasi fade untuk transisi */
        .fade-in {
                opacity: 0;
                transition: opacity 0.5s ease-in-out;
        }
        .fade-in.show {
                opacity: 1;
        }
</style>

<footer class="footer mt-3">
        <div class="text-center text-bg-dark">
                <p>Website Design By 
                        <!-- Tombol untuk mengaktifkan debug mode -->
                        <button id="debugModeBtn" style="background: none; border: none; color: inherit; font-weight: bold; padding: 0; cursor: default ">
                                Trisna Almuti
                        </button>
                </p>
        </div>
</footer>

<!-- JavaScript untuk Debuging -->
<script>

        // Tombol untuk memunculkan modal password
        document.getElementById("debugModeBtn").addEventListener("click", function() {
                // Reset tampilan modal
                document.getElementById("alertWrongPassword").style.display = "none"; // Sembunyikan alert saat modal muncul
                document.getElementById("passwordInputDebug").value = ""; // Bersihkan input
                $('#passwordModal').modal('show'); // Tampilkan modal
        });

        // Tombol submit untuk mengecek password
        document.getElementById("submitPassword").addEventListener("click", function() {
        const passwordInputDebug = document.getElementById("passwordInputDebug").value;

        // Kirim password ke server untuk verifikasi
        fetch('verify_password.php', { 
                // verify_password.php adalah lokasi penyimpanan password
                // agar tidak terlihat di inspect element !!
                method: 'POST',
                headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `password=${encodeURIComponent(passwordInputDebug)}`
        })
        .then(response => response.text())
        .then(result => {
                if (result === "success") {
                const debugContent = document.getElementById("debugContent");

                // Tampilkan div debug content dengan animasi fade in
                debugContent.style.display = "block";
                setTimeout(() => {
                        debugContent.classList.add("show");
                }, 10); // Delay kecil untuk memicu transisi

                // Tutup modal
                $('#passwordModal').modal('hide');
                } else {
                // Tampilkan alert jika password salah
                document.getElementById("alertWrongPassword").style.display = "block";
                }
        })
        .catch(error => console.error('Error:', error));
        });


        // Tombol untuk mematikan mode debug
        document.getElementById("turnOffDebugBtn").addEventListener("click", function() {
                const debugContent = document.getElementById("debugContent");
                const roleUnchange = document.getElementById("role");

                // Sembunyikan debugContent dengan animasi fade out
                debugContent.classList.remove("show");
                // Mengembalikkan role nya menjadi "User"
                roleUnchange.value = "user";
                setTimeout(() => {
                        debugContent.style.display = "none";
                }, 500); // Waktu yang sesuai dengan durasi animasi
        });
</script>

<!-- Bootstrap JS, Popper.js, dan jQuery -->
<script src="js/jquery.min.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>