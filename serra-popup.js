jQuery(document).ready(function ($) {
    const popup = $('#serraPopup');
    if (!popup.length) return;

    const now = Date.now();

    // Default configuration from wp_localize_script (serraPopupData)
    const settings = Object.assign({
        close_on_redirect: '1',
        redirect_close_duration: '3',
        close_duration_days: '3',
        remind_later_active: '1',
        remind_later_type: 'pages',
        remind_later_value: '3',
        countdown_active: '0',
        countdown_end_time: '',
        countdown_label: 'Teklifin bitmesine kalan süre:',
        animation_style: 'pop',
        mobile_style: 'centered'
    }, typeof serraPopupData !== 'undefined' ? serraPopupData : {});

    // Cookie/LocalStorage keys
    const CLOSED_UNTIL_KEY = 'serra_popup_closed_until';
    const REMIND_PAGES_KEY = 'serra_popup_remind_pages';

    function checkVisibility() {
        const closedUntil = localStorage.getItem(CLOSED_UNTIL_KEY);
        if (closedUntil && now < parseInt(closedUntil, 10)) {
            popup.hide();
            return;
        }

        const remindPages = localStorage.getItem(REMIND_PAGES_KEY);
        if (remindPages !== null) {
            let count = parseInt(remindPages, 10);
            if (count > 0) {
                localStorage.setItem(REMIND_PAGES_KEY, count - 1);
                popup.hide();
                return;
            } else {
                localStorage.removeItem(REMIND_PAGES_KEY);
            }
        }

        popup.fadeIn();
    }

    // Initial check
    checkVisibility();

    // Live Countdown Timer logic
    function initCountdown() {
        if (settings.countdown_active !== '1' || !settings.countdown_end_time) return;

        const endTime = new Date(settings.countdown_end_time).getTime();
        if (isNaN(endTime)) return;

        function updateTimer() {
            const currentMs = Date.now();
            const diff = endTime - currentMs;

            if (diff <= 0) {
                $('#serraTimerDays').text('00');
                $('#serraTimerHours').text('00');
                $('#serraTimerMinutes').text('00');
                $('#serraTimerSeconds').text('00');
                return;
            }

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            $('#serraTimerDays').text(days < 10 ? '0' + days : days);
            $('#serraTimerHours').text(hours < 10 ? '0' + hours : hours);
            $('#serraTimerMinutes').text(minutes < 10 ? '0' + minutes : minutes);
            $('#serraTimerSeconds').text(seconds < 10 ? '0' + seconds : seconds);
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    }

    initCountdown();

    // Close Button (X)
    $('#serraPopupClose').click(function () {
        const days = parseInt(settings.close_duration_days, 10) || 3;
        const durationMs = days * 24 * 60 * 60 * 1000;
        localStorage.setItem(CLOSED_UNTIL_KEY, now + durationMs);
        localStorage.removeItem(REMIND_PAGES_KEY);
        popup.fadeOut();
    });

    // Action CTA Buttons (Redirection)
    $('.serra-popup-btn').click(function () {
        if (settings.close_on_redirect === '1') {
            const days = parseInt(settings.redirect_close_duration, 10) || 3;
            const durationMs = days * 24 * 60 * 60 * 1000;
            localStorage.setItem(CLOSED_UNTIL_KEY, now + durationMs);
            localStorage.removeItem(REMIND_PAGES_KEY);
            popup.fadeOut();
        }
    });

    // Remind Later Button
    $('#serraPopupRemindLater').click(function () {
        const val = parseInt(settings.remind_later_value, 10) || 3;
        const type = settings.remind_later_type || 'pages';

        if (type === 'pages') {
            localStorage.setItem(REMIND_PAGES_KEY, val);
            localStorage.removeItem(CLOSED_UNTIL_KEY);
        } else if (type === 'hours') {
            const durationMs = val * 60 * 60 * 1000;
            localStorage.setItem(CLOSED_UNTIL_KEY, now + durationMs);
            localStorage.removeItem(REMIND_PAGES_KEY);
        } else if (type === 'days') {
            const durationMs = val * 24 * 60 * 60 * 1000;
            localStorage.setItem(CLOSED_UNTIL_KEY, now + durationMs);
            localStorage.removeItem(REMIND_PAGES_KEY);
        }

        popup.fadeOut();
    });
});


