@php
    $crispId   = config('crisp.website_id');
    $crispEnabled = config('crisp.enabled') && !empty($crispId);
    $authUser  = auth()->user();
    $showWidget = $crispEnabled && $authUser && $authUser->permission === 'user';
@endphp

@if ($showWidget)
<script>
(function () {
    /* ════════════════════════════════════════════════════════════════════
     * LANGKAH 1 — Bersihkan sesi Crisp lama SEBELUM widget dimuat.
     *
     * Crisp menyimpan Client-Assigned-ID (CAID) di localStorage & cookie.
     * Kalau dua akun berbeda pakai browser yang sama, Crisp akan reuse
     * sesi lama → history menumpuk jadi 1 percakapan.
     *
     * Caranya: simpan EduSkill user-ID di localStorage sendiri
     * (key: edu_crisp_uid). Setiap halaman dimuat, bandingkan user saat
     * ini dengan yang terakhir. Kalau beda → hapus semua data Crisp
     * (localStorage + cookies) SEBELUM window.$crisp dibuat, sehingga
     * Crisp memulai sesi baru yang bersih untuk user yang baru.
     * ════════════════════════════════════════════════════════════════════ */
    var STORE_KEY  = 'edu_crisp_uid';
    var currentUid = @json($authUser->id);

    try {
        var lastUid = localStorage.getItem(STORE_KEY);

        if (lastUid !== null && lastUid !== currentUid) {
            /* ── Hapus semua localStorage Crisp ── */
            var keysToDelete = [];
            for (var i = 0; i < localStorage.length; i++) {
                var k = localStorage.key(i);
                if (k && k.indexOf('crisp-client') === 0) {
                    keysToDelete.push(k);
                }
            }
            keysToDelete.forEach(function (k) { localStorage.removeItem(k); });

            /* ── Hapus cookie Crisp (caid = Client Assigned ID) ── */
            ['caid', 'crisp-client', '__crisp-client'].forEach(function (name) {
                [
                    'path=/; domain=' + location.hostname,
                    'path=/; domain=.' + location.hostname,
                    'path=/',
                ].forEach(function (attr) {
                    document.cookie = name + '=; Max-Age=0; ' + attr;
                });
            });
        }

        /* Simpan user saat ini untuk pengecekan berikutnya */
        localStorage.setItem(STORE_KEY, currentUid);
    } catch (e) { /* abaikan error StorageAccess */ }

    /* ════════════════════════════════════════════════════════════════════
     * LANGKAH 2 — Inisialisasi Crisp dengan identitas user yang baru.
     *
     * Karena data lama sudah dibersihkan di atas, Crisp akan membuat
     * sesi baru → percakapan baru di dashboard admin.
     * ════════════════════════════════════════════════════════════════════ */
    window.$crisp = [];
    window.CRISP_WEBSITE_ID = @json($crispId);

    /* ── Tema visual: indigo sesuai warna primary EduSkill #544AF5 ── */
    $crisp.push(["config", "color:theme",      ["indigo"]]);
    $crisp.push(["config", "position:reverse", [false]]);   /* kanan */

    /* ── Identitas pengguna ── */
    $crisp.push(["set", "user:email",    [@json($authUser->email)]]);
    $crisp.push(["set", "user:nickname", [@json($authUser->name)]]);
    @if ($authUser->avatar && $authUser->avatar !== 'default-avatar.png')
    $crisp.push(["set", "user:avatar",   [@json(asset('uploads/avatar/' . $authUser->avatar))]]);
    @endif

    /* ── Metadata sesi (tampil di dashboard Crisp admin) ── */
    $crisp.push(["set", "session:data", [[
        ["user_id",  @json($authUser->id)],
        ["platform", "EduSkill"],
        ["role",     "user"]
    ]]]);

    /* ════════════════════════════════════════════════════════════════════
     * LANGKAH 3 — Template "Tinggalkan Pesan"
     *
     * Jika chat dibuka dan admin belum membalas dalam 8 detik,
     * pre-fill input agar user tetap bisa mengirim pesan saat admin
     * sedang offline.
     * ════════════════════════════════════════════════════════════════════ */
    (function () {
        var leaveTimer         = null;
        var operatorHasReplied = false;

        var TEMPLATE =
            "Halo Admin EduSkill! 👋\n\n" +
            "Saya menyadari Anda sedang tidak tersedia saat ini.\n" +
            "Berikut pesan yang ingin saya sampaikan:\n\n" +
            "[Tulis pertanyaan atau pesan Anda di sini]\n\n" +
            "Nama  : " + @json($authUser->name) + "\n" +
            "Email : " + @json($authUser->email) + "\n\n" +
            "Mohon balas secepatnya. Terima kasih! 🙏";

        $crisp.push(["on", "message:received", function () {
            operatorHasReplied = true;
            if (leaveTimer) clearTimeout(leaveTimer);
        }]);

        $crisp.push(["on", "chat:opened", function () {
            if (operatorHasReplied) return;
            leaveTimer = setTimeout(function () {
                if ($crisp.get("message:text") === "") {
                    $crisp.push(["set", "message:text", [TEMPLATE]]);
                }
            }, 8000);
        }]);

        $crisp.push(["on", "chat:closed", function () {
            if (leaveTimer) clearTimeout(leaveTimer);
        }]);
    })();

    /* ── Muat skrip Crisp ── */
    (function () {
        var d = document, s = d.createElement("script");
        s.src   = "https://client.crisp.chat/l.js";
        s.async = 1;
        d.getElementsByTagName("head")[0].appendChild(s);
    })();
})();
</script>
@endif
