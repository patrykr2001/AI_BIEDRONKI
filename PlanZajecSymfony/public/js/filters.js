function insertNewFiltersContainer(viewNumber) {

    const containerHTML = `
        <div class="container filters-container" style="z-index: 1;" id="filters-${viewNumber}">
            <p class="text-start mb-1">Wykładowca</p>
            <div class="input-group mb-1">
                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="wykladowca-input-${viewNumber}">
            </div>
            <p class="text-start mb-1">Sala</p>
            <div class="input-group mb-1">
                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="sala-input-${viewNumber}">
            </div>
            <p class="text-start mb-1">Przedmiot</p>
            <div class="input-group mb-1">

                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="przedmiot-input-${viewNumber}">
            </div>
            <p class="text-start mb-1">Grupa</p>

            <div class="input-group mb-1">
                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="grupa-input-${viewNumber}">
            </div>
            <p class="text-start mb-1">Numer albumu</p>
            <div class="input-group mb-1">
                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" id="album-input-${viewNumber}">
            </div>
            <div class="input-group mb-1">
                 <input type="date" id="start-input-${viewNumber}">
            </div>
            <div class="input-group mb-1">
                 <input type="date" id="end-input-${viewNumber}">
            </div>
            <div class="text-center">
                <button class="btn btn-primary" id="filter-button-${viewNumber}">Wyszukaj</button>
                <button class="btn btn-light" id="clean-filters-button-${viewNumber}">Wyczyść filtry</button>
            </div>
        </div>`;

    const parentElement = document.getElementById('filters-wrapper')

    parentElement.insertAdjacentHTML('beforeend', containerHTML);

    const filterButton = document.getElementById('filter-button-' + viewNumber)

    const clearFiltersButton = document.getElementById('clean-filters-button-' + viewNumber)
    clearFiltersButton.addEventListener('click', handleFiltersClearing)

    filterButton.addEventListener('click', handleFiltering)

    console.log('view', viewNumber)

    flatpickr(`#start-input-${viewNumber}`,
        {
            dateFormat: "d-m-Y"
        }
    );

    flatpickr(`#end-input-${viewNumber}`,
        {
            dateFormat: "d-m-Y"
        }
    );

    //TODO: dodac czyszcenie filtrów
}

function saveInitialFilters(viewNumber, filtersValues) {


    const wykladowcaInputValue = getFilterValue('wykladowca', viewNumber)
    const salaInputValue = getFilterValue('sala', viewNumber)
    const przedmiotInputValue = getFilterValue('przedmiot', viewNumber)
    const grupaInputValue = getFilterValue('grupa', viewNumber)
    const albumInputValue = getFilterValue('album', viewNumber)
    const startInputValue = getFilterValue('start', viewNumber)
    const endInputValue = getFilterValue('end', viewNumber)

    filtersValues = {
        "nr": viewNumber.toString(),
        "wykladowca": wykladowcaInputValue,
        "sala": salaInputValue,
        "przedmiot": przedmiotInputValue,
        "grupa": grupaInputValue,
        "album": albumInputValue,
        "start": startInputValue,
        "end": endInputValue
    }

    console.log('filtersValues: ', filtersValues)
}

function updateUrlFilters(viewNumber) {

    console.log('updating URL params')
    const paramsKey = 'cal' + viewNumber
    globalParsedParams[paramsKey] = {
        nr: viewNumber,
        wykladowca: getFilterValue('wykladowca', viewNumber),
        sala: getFilterValue('sala', viewNumber),
        przedmiot: getFilterValue('przedmiot', viewNumber),
        grupa: getFilterValue('grupa', viewNumber),
        album: getFilterValue('album', viewNumber),
        start: getFilterValue('start', viewNumber),
        end: getFilterValue('end', viewNumber)
    }

    //TODO: dodac obsluge kiedy WSZYSTKIE filtry sa puste

    console.log(globalParsedParams)

    const urlParams = new URLSearchParams();

    for (const outerKey in globalParsedParams) {
        if (globalParsedParams.hasOwnProperty(outerKey)) {
            const innerDict = globalParsedParams[outerKey];
            const nrValue = innerDict['nr'];

            for (const innerKey in innerDict) {
                if (innerDict.hasOwnProperty(innerKey) && innerKey !== 'nr') {
                    const value = innerDict[innerKey];

                    if (value !== null && value !== undefined && value !== '') {
                        const paramName = `cal${nrValue}_${innerKey}`;
                        urlParams.append(paramName, value);
                    }
                }
            }
        }
    }

    const currentUrl = window.location.origin + window.location.pathname;

    const url = new URL(currentUrl);

    const params = new URLSearchParams(urlParams);
    params.forEach((value, key) => {
        url.searchParams.append(key, value);
    });

    window.history.pushState({}, '', url.toString());
}

function getFilterValue(filter, viewNr) {
    let filterId = filter.toString() + '-input-' + viewNr.toString()
    const filterInputElement = document.getElementById(filterId)
    return filterInputElement.value
}

function setFilterValue(filter, viewNr, value) {
    let filterId = filter.toString() + '-input-' + viewNr
    const filterInputElement = document.getElementById(filterId)
    filterInputElement.value = value
}


function handleFiltering(event) {
    const targetButton = event.target
    const [filter, button, viewNumber] = targetButton.id.split('-')
    console.log(viewNumber)

    updateUrlFilters(viewNumber)

    inputDataIntoView(viewNumber, [
            getFilterValue('wykladowca', viewNumber),
            getFilterValue('sala', viewNumber),
            getFilterValue('przedmiot', viewNumber),
            getFilterValue('grupa', viewNumber),
            getFilterValue('album', viewNumber),
            getFilterValue('start', viewNumber),
            getFilterValue('end', viewNumber)
        ]
    )
}


function parseUrlParams(urlParams) {

    const parsedParams = {};

    const attributes = ['wykladowca', 'sala', 'przedmiot', 'grupa', 'album', 'start', 'end'];

    for (const [key, value] of Object.entries(urlParams)) {
        const [prefix, attribute] = key.split('_');
        const chunks = prefix.match(/.{1,3}/g);
        console.log(chunks[1])
        console.log('split na ', prefix, ' ', attribute)
        if (attributes.includes(attribute)) {
            if (!parsedParams[prefix]) {
                parsedParams[prefix] = {
                    nr: chunks[1],
                    wykladowca: null,
                    sala: null,
                    przedmiot: null,
                    grupa: null,
                    album: null,
                    start: null,
                    end: null
                };
            }
            parsedParams[prefix][attribute] = value;
        }
    }

    globalParsedParams = parsedParams

    for (let view in parsedParams) {
        let filtersDict = parsedParams[view]

        for (let filter in filtersDict) {
            if (filter === 'nr') {
                createNewCalendarView(filtersDict['nr'])
            } else {
                if (filtersDict[filter] !== null) {
                    setFilterValue(filter.toString(), filtersDict['nr'], filtersDict[filter])
                }
            }
        }

        if (Object.values(filtersDict).filter(value => value !== null).length > 1) {
            inputDataIntoView(filtersDict['nr'], [
                filtersDict['wykladowca'],
                filtersDict['sala'],
                filtersDict['przedmiot'],
                filtersDict['grupa'],
                filtersDict['album'],
                filtersDict['start'],
                filtersDict['end']],
            )
        }
    }
    //TODO: dodac maly spinner przy ladowaniu danych
}

function inputDataIntoView(view, filters) {
    fetchFilteredData(filters, inputData, view)
}

function inputData(data, view) {
    console.log('adding data to target calendar')
    const calDiv = document.getElementById('cal-' + view)
    //getting calendar to input events
    const targetCalendar = globalCalendarsArray['cal-' + view]

    console.log('data ktora dostalem', data)
    for (let id in data) {

        targetCalendar.addEvent(data[id])
        targetCalendar.render()
    }
}

function handleFiltersClearing(event) {

    const targetButton = event.target
    const [filter, button, clear, viewNumber] = targetButton.id.split('-')
    setFilterValue('wykladowca', viewNumber, "")
    setFilterValue('sala', viewNumber, "")
    setFilterValue('przedmiot', viewNumber, "")
    setFilterValue('grupa', viewNumber, "")
    setFilterValue('album', viewNumber, "")
    setFilterValue('start', viewNumber, "")
    setFilterValue('end', viewNumber, "")

    updateUrlFilters(viewNumber)


}