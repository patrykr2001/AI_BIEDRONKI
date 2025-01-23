
function renderNewCalendar(calendarDivId){


    const calendarEl = document.getElementById(calendarDivId);

    const calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar:{
            start: 'listDay,dayGridWeek,dayGridMonth',
            center: 'title',
            end: 'prev,today,next'
        },
        buttonText:{
            today: 'dziś',
            listDay: 'dzień',
            dayGridWeek: 'tydzień',
            dayGridMonth: 'miesiąc'

        },
        initialView: 'dayGridMonth',
        events: [
            {
                id: 'a',
                title: 'my event',
                start: '2025-01-23'
            }
        ]
    });

    calendar.render();

    globalCalendarsArray[calendarDivId] = calendar



}

function handleAddTabClick(){

    const container = document.getElementById('tabs-bar');
    let lastValue;
    if( container.childElementCount === 1){
        lastValue = 0
    }else{
        lastValue = parseInt(container.lastElementChild.previousElementSibling.textContent)
    }

    createNewCalendarView(lastValue + 1)

}


function handleTabClick(event) {

    const targetTab = event.target;
    switchToTab(targetTab)

}

function switchToTab(tabElement){

    const tabText = tabElement.textContent
    const targetCalendarId = 'cal-' + tabText;
    const targetFiltersContainerId = 'filters-' + tabText;

    document.querySelectorAll('.calendar-slide').forEach(element => {
        element.style.zIndex = '1';
    });

    document.querySelectorAll('.filters-container').forEach(element => {
        element.style.zIndex = '1';
    });

    document.querySelectorAll('.calendar-tab').forEach(element => {
        element.classList.remove("active-tab")
    });

    tabElement.classList.add("active-tab");


    const targetCalendar = document.getElementById(targetCalendarId);
    targetCalendar.style.zIndex = '2';

    const targetFilterContainer = document.getElementById(targetFiltersContainerId);
    targetFilterContainer.style.zIndex = '2';


}

function createNewTab(tabNr){

    let newTab = document.createElement('div');
    newTab.className = 'box calendar-tab';
    newTab.textContent = tabNr.toString();
    newTab.id = 'tab-' + tabNr.toString();

    newTab.addEventListener('click', handleTabClick);

    return newTab;

}

function createNewCalendar(calendarNr){

    let newCalendar = document.createElement('div');
    newCalendar.className = 'container calendar-slide';
    newCalendar.id = ( 'cal-' + calendarNr.toString() );
    newCalendar.style.zIndex = '1';

    return newCalendar

}

function createNewCalendarView(tabNr){

    const container = document.getElementById('tabs-bar');

    const newTab = createNewTab(tabNr)
    container.insertBefore(newTab, container.lastElementChild);

    const newCalendar = createNewCalendar(tabNr)

    const gallery = document.getElementById('slides_wrapper');
    gallery.appendChild(newCalendar);

    renderNewCalendar(newCalendar.id)

    insertNewFiltersContainer(tabNr)

    switchToTab(newTab)

}




