function fetchFilteredData(){

    console.log('getting data')

    //TODO: adding way to fetch with API

    const data = fetchRecordsFromAPI()
    return parseDataIntoEvents(data)

}

function parseDataIntoEvents(fetchedRecords) {

    //example data
    let records = [
        {
            id: 'cos',
            wykladowca: 'Kaczmarczyk',
            sala: '123',
            przedmiot: 'Aplikacje internetowe 1',
            budynek: 'WI1',
            start: '2025-01-23',
            hours: 2
        },{
            id: 'nie',
            wykladowca: 'Wernikowski',
            sala: '245',
            przedmiot: 'Programownia komputerow 1',
            budynek: 'WI2',
            start: '2025-01-22',
            hours: 2
        },
        {
            id: 'wiem',
            wykladowca: 'Mąka',
            sala: '12',
            przedmiot: 'Transmisja danych',
            budynek: 'WI1',
            start: '2025-01-24',
            hours: 2
        }


    ]

    let events = []

    for(let record in records){

        console.log('dostalem record: ', record)

        events.push(recordToEvent(records[record]))

    }

    console.log(events)
    return events

}

function recordToEvent(record) {

    let event = {
        id: record['id'],
        title: record['wykladowca'] + ' ' + record['przedmiot'],
        allDay: true,
        start: record['start']

    }

    console.log('stworzylem event: ', event)

    return event

}

function fetchRecordsFromAPI() {

}