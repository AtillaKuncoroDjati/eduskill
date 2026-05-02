@php
    $crispId = config('crisp.website_id');
    $crispEnabled = config('crisp.enabled') && !empty($crispId);
    $authUser = auth()->user();
    $showWidget = $crispEnabled && $authUser && $authUser->permission === 'user';
@endphp

@if ($showWidget)
<script>
    window.$crisp = [];
    window.CRISP_WEBSITE_ID = @json($crispId);

    /* ── Tema visual: indigo (sesuai warna primary EduSkill #544AF5) ── */
    $crisp.push(["config", "color:theme",        ["indigo"]]);
    $crisp.push(["config", "position:reverse",   [false]]);  /* tetap di kanan */
    $crisp.push(["config", "container:index",    [9999]]);
    $crisp.push(["config", "hide:vacation",      [false]]);

    /* ── Identitas pengguna saat ini ── */
    var crispCurrentEmail    = @json($authUser->email);
    var crispCurrentName     = @json($authUser->name);
    var crispCurrentUserId   = @json($authUser->id);
    @if ($authUser->avatar && $authUser->avatar !== 'default-avatar.png')
    var crispCurrentAvatar   = @json(asset('uploads/avatar/' . $authUser->avatar));
    @else
    var crispCurrentAvatar   = null;
    @endif

    function crispApplyIdentity() {
        $crisp.push(["set", "user:email",    [crispCurrentEmail]]);
        $crisp.push(["set", "user:nickname", [crispCurrentName]]);
        if (crispCurrentAvatar) {
            $crisp.push(["set", "user:avatar", [crispCurrentAvatar]]);
        }
        $crisp.push(["set", "session:data", [[
            ["user_id",  crispCurrentUserId],
            ["platform", "EduSkill"],
            ["role",     "user"]
        ]]]);
    }

    crispApplyIdentity();

    /* ── Reset session jika user berbeda dari yang sebelumnya login
     * Mencegah history chat user lama menempel saat berganti akun
     * di browser yang sama.
     * ──────────────────────────────────────────────────────────── */
    $crisp.push(["on", "session:loaded", function () {
        try {
            var existingEmail = $crisp.get("user:email");
            if (existingEmail && existingEmail !== crispCurrentEmail) {
                $crisp.push(["do", "session:reset"]);
                /* Set ulang identitas baru setelah reset */
                setTimeout(crispApplyIdentity, 300);
            }
        } catch (e) { /* abaikan jika SDK belum siap */ }
    }]);

    /* ── Template "Tinggalkan Pesan" ───────────────────────────────────
     * Jika chat dibuka dan admin belum membalas dalam 8 detik,
     * pre-fill input dengan template tinggalkan pesan agar user
     * tetap bisa mengirim pesan meski admin sedang offline.
     * ──────────────────────────────────────────────────────────────── */
    (function () {
        var leaveTimer         = null;
        var operatorHasReplied = false;

        var LEAVE_MSG_TEMPLATE =
            "Halo Admin EduSkill! 👋\n\n" +
            "Saya menyadari Anda sedang tidak tersedia saat ini.\n" +
            "Berikut pesan yang ingin saya sampaikan:\n\n" +
            "[Tulis pertanyaan atau pesan Anda di sini]\n\n" +
            "Nama  : " + crispCurrentName + "\n" +
            "Email : " + crispCurrentEmail + "\n\n" +
            "Mohon balas secepatnya. Terima kasih! 🙏";

        $crisp.push(["on", "message:received", function () {
            operatorHasReplied = true;
            if (leaveTimer) clearTimeout(leaveTimer);
        }]);

        $crisp.push(["on", "chat:opened", function () {
            if (operatorHasReplied) return;
            leaveTimer = setTimeout(function () {
                if ($crisp.get("message:text") === "") {
                    $crisp.push(["set", "message:text", [LEAVE_MSG_TEMPLATE]]);
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
</script>
@endif
