document.addEventListener('DOMContentLoaded', () => {


    const calendarEl = document.getElementById('calendar');

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin]
    });

    calendar.render();


});