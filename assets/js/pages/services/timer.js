// Таймер обратного отсчета
function initCountdown() {
    const countdownElement = document.getElementById('countdown');
    if (!countdownElement) return;

    function updateCountdown() {
        let [hours, minutes, seconds] = countdownElement.textContent.split(':').map(Number);
        
        seconds--;
        if (seconds < 0) {
            seconds = 59;
            minutes--;
        }
        if (minutes < 0) {
            minutes = 59;
            hours--;
        }
        if (hours < 0) {
            hours = minutes = seconds = 0;
            clearInterval(timerInterval);
        }
        
        countdownElement.textContent = 
            `${hours.toString().padStart(2, '0')}:` +
            `${minutes.toString().padStart(2, '0')}:` +
            `${seconds.toString().padStart(2, '0')}`;
    }

    const timerInterval = setInterval(updateCountdown, 1000);
}
// Инициализация при загрузке
document.addEventListener('DOMContentLoaded', initCountdown);
