// public/js/counter.js

document.addEventListener('DOMContentLoaded', () => {
    const addButton = document.getElementById('addButton');
    const container = document.getElementById('tabsBar');

    addButton.addEventListener('click', handleAddTabClick);
    const firstTab = document.getElementById('tab-1');
    firstTab.addEventListener('click',handleTabClick);

    renderNewCalendar('calendar-1');



});


