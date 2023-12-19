document.addEventListener("DOMContentLoaded", function () {
    const countdownElement = document.getElementById("timer");
    const articleDate = countdownElement.getAttribute('data-date');

    if (articleDate) {
        const countdownDate = new Date(articleDate).getTime();
        const timerInterval = setInterval(updateCountdown, 1000);

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = countdownDate - now;

            if (distance <= 0) {
                clearInterval(timerInterval);
                countdownElement.innerHTML = "<span class='expired'>EXPIRED</span>";
            } else {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const formattedTime = formatTime(days) + " days, " + formatTime(hours) + " hours, " + formatTime(minutes) + " minutes";
                countdownElement.innerHTML = `<span class='countdown'>Expired in: ${formattedTime}</span>`;
            }
        }

        function formatTime(value) {
            return value < 10 ? `0${value}` : value;
        }
    } else {
        countdownElement.innerHTML = "<span class='expired'>No date provided for countdown.</span>";
    }
});
