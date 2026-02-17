jQuery(document).ready(function ($) {
    const popup = $('#serraPopup');
    const now = Date.now();

    // Cookie/LocalStorage keys
    const CLOSED_UNTIL_KEY = 'serra_popup_closed_until';
    const REMIND_PAGES_KEY = 'serra_popup_remind_pages';

    function checkVisibility() {
        const closedUntil = localStorage.getItem(CLOSED_UNTIL_KEY);
        if (closedUntil && now < parseInt(closedUntil)) {
            popup.hide();
            return;
        }

        const remindPages = localStorage.getItem(REMIND_PAGES_KEY);
        if (remindPages !== null) {
            let count = parseInt(remindPages);
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

    // Close Button - 3 Days Delay
    $('#serraPopupClose').click(function () {
        const threeDays = 3 * 24 * 60 * 60 * 1000;
        localStorage.setItem(CLOSED_UNTIL_KEY, now + threeDays);
        localStorage.removeItem(REMIND_PAGES_KEY);
        popup.fadeOut();
    });

    // Remind Later Button - 3 Page Views Delay
    $('#serraPopupRemindLater').click(function () {
        localStorage.setItem(REMIND_PAGES_KEY, 3);
        localStorage.removeItem(CLOSED_UNTIL_KEY);
        popup.fadeOut();
    });
});
