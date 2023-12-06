const main = () => {
    const second = 1000
    const minute = second * 60
    const hour = minute * 60
    const day = hour * 24

    setInterval(() => {
        let dates = document.querySelectorAll('input');
        for (let i = 0; i < dates.length; i++) {
            const eventDate = new Date(dates[i].value);
            const countDown = new Date(eventDate).getTime();

            const now = new Date().getTime();
            const distance = countDown - now;

            document.getElementById('days-' + dates[i].id).innerText = (Math.floor(distance / day) <= 0) ? 0 : Math.floor(distance / day);
            document.getElementById('hours-' + dates[i].id).innerText = (Math.floor((distance % day) / (hour)) <= 0) ? 0 : Math.floor((distance % day) / (hour));
            document.getElementById('minutes-' + dates[i].id).innerText = (Math.floor((distance % hour) / (minute)) <= 0) ? 0 : Math.floor((distance % hour) / (minute));
            document.getElementById('seconds-' + dates[i].id).innerText = (Math.floor((distance % minute) / second) <= 0) ? 0 : Math.floor((distance % minute) / second);
        }
    }, 0)
}
main();