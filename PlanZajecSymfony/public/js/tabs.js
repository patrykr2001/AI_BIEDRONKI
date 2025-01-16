
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
    newCalendar.className = 'container calendar-slide';
    newCalendar.id = 'calendar-' + (lastValue + 1);
    newCalendar.style.zIndex = '1';

    const gallery = document.getElementById('slides_wrapper');
    gallery.appendChild(newCalendar);

    renderNewCalendar(newCalendar.id)


}


//! nie działa dla tab-1...
function handleTabClick(event) {

    const targetTab = event.target;
    const tabText = targetTab.textContent;
    const targetCalendarId = 'calendar-' + tabText;


    document.querySelectorAll('.calendar-slide').forEach(element => {
        element.style.zIndex = '1';
    });

    document.querySelectorAll('.calendar-tab').forEach(element => {
        element.classList.remove("active-tab")
    });



    targetTab.classList.add("active-tab");

    console.log(targetCalendarId)

    const targetCalendar = document.getElementById(targetCalendarId);
    targetCalendar.style.zIndex = '2';




}



