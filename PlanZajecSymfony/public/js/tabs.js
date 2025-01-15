
function renderNewCalendar(calendarDivId){

    const calendarEl = document.getElementById(calendarDivId);

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth'
    });

    calendar.render();


}

function handleAddTabClick(){

    const container = document.getElementById('tabsBar');

    const lastTab = container.querySelector('.box:last-child');

    const lastValue = parseInt(lastTab.previousElementSibling.textContent, 10) || 1;

    const newTab = document.createElement('div');
    newTab.className = 'box calendar-tab';
    newTab.textContent = lastValue + 1;
    newTab.id = 'tab-' + (lastValue + 1);

    container.insertBefore(newTab, lastTab);

    newTab.addEventListener('click', handleTabClick);

    const newCalendar = document.createElement('div');
    newCalendar.className = 'container h-100 calendar-slide';
    newCalendar.id = 'calendar-' + (lastValue + 1);
    newCalendar.style.zIndex = '1';

    const gallery = document.getElementById('calendar-gallery');
    gallery.appendChild(newCalendar);

    renderNewCalendar(newCalendar.id)


}



function handleTabClick(event) {

    const tabText = event.target.textContent;
    const targetCalendarId = 'calendar-' + tabText;


    document.querySelectorAll('.calendar-slide').forEach(element => {
        element.style.zIndex = '1';
    });


    const targetCalendar = document.getElementById(targetCalendarId);
    targetCalendar.style.zIndex = '2';




}



