let globalParsedParams = {}
let globalCalendarsArray = {}

document.addEventListener('DOMContentLoaded', () => {
    const addButton = document.getElementById('add-button');
    // const firstTab = document.getElementById('tab-1');
    const filterButton = document.getElementById('filter-button-1');

    addButton.addEventListener('click', handleAddTabClick);
    //
    // firstTab.addEventListener('click',handleTabClick);
    // firstTab.classList.add("active-tab");

    filterButton.addEventListener('click', handleFiltering);

    flatpickr(`#start-input-1`,
        {
            dateFormat: "d-m-Y"
        }
    );

    flatpickr(`#end-input-1`,
        {
            dateFormat: "d-m-Y"
        }
    );

    // renderNewCalendar('calendar-1');

    parseUrlParams(urlParams);




});


