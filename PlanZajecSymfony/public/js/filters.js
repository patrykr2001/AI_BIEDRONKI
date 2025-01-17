function insertNewFiltersContainer(calendarNumber){

    const containerHTML = `
        <div class="container filters-container" style="z-index: 1;" id="filters-${calendarNumber}">
            <p class="text-start mb-1">Wykładowca</p>
            <div class="input-group mb-1">
                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <p class="text-start mb-1">Sala</p>
            <div class="input-group mb-1">
                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <p class="text-start mb-1">Przedmiot</p>
            <div class="input-group mb-1">
                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <p class="text-start mb-1">Grupa</p>
            <div class="input-group mb-1">
                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <p class="text-start mb-1">Numer albumu</p>
            <div class="input-group mb-1">
                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <div class="text-center">
                <button class="btn btn-primary">Wyszukaj</button>
                <button class="btn btn-light">Wyczyść filtry</button>
            </div>
        </div>`;

    const parentElement = document.getElementById('filters-wrapper')

    console.log(parentElement)

    parentElement.insertAdjacentHTML('beforeend', containerHTML);



}