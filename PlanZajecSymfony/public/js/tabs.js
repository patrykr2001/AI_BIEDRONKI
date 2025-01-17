
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

    let lastValue = parseInt(lastTab.previousElementSibling.textContent, 10) || 1;

    if(lastValue == null){
        lastValue = 0
    }

    console.log('last value', lastValue);

    const newTab = document.createElement('div');
    newTab.className = 'box calendar-tab';
    newTab.textContent = (lastValue + 1).toString();
    newTab.id = ('tab-' + (lastValue + 1)).toString();

    container.insertBefore(newTab, lastTab);

    newTab.addEventListener('click', handleTabClick);

    const newCalendar = document.createElement('div');
    newCalendar.className = 'container calendar-slide';
    newCalendar.id = ('calendar-' + (lastValue + 1)).toString();
    newCalendar.style.zIndex = '1';

    const gallery = document.getElementById('slides_wrapper');
    gallery.appendChild(newCalendar);

    renderNewCalendar(newCalendar.id)

    insertNewFiltersContainer(lastValue + 1)


}


//! dla tab-1 wywala targetCalendar is null, to powoduje tez problem z filters-1
//! tak jakby ostatni element i pierwszy zawsze byly tym samym
function handleTabClick(event) {

    const targetTab = event.target;
    const tabText = targetTab.textContent;
    console.log(typeof(tabText))
    const targetCalendarId = 'calendar-' + tabText;
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



    targetTab.classList.add("active-tab");

    console.log(targetCalendarId)

    const targetCalendar = document.getElementById(targetCalendarId);
    targetCalendar.style.zIndex = '2';

    const targetFilterContainer = document.getElementById(targetFiltersContainerId);
    targetFilterContainer.style.zIndex = '2';


}



