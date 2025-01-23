const Endpoints = {
    Groups: '/api/groups',
    Rooms: '/api/rooms',
    Subjects: '/api/subjects',
    Teachers: '/api/teachers',
    Lessons: '/api/lessons',
}

function fetchFilteredData(filters) {
    console.log('getting data')
    let namedFilters = {
        Teacher: filters[0],
        Room: filters[1],
        Subject: filters[2],
        Group: filters[3],
        Student: filters[4]
    }
    const data = fetchRecordsFromAPI(namedFilters)
    return parseDataIntoEvents(data)
}

function parseDataIntoEvents(fetchedRecords) {
    /*
    ***ENUMS from PHP***
    enum LessonForms: string
    {
        case Lecture = 'wykład';
        case Laboratory = 'labolatorium';
        case Remote = 'zajęcia zdalne';
        case Seminar = 'seminarium';
        case Project = 'projekt';
        case Pass = 'zaliczenie';
        case RemotePass = 'zaliczenie zdalne';
        case Auditory = 'audytoryjne';
        case Field = 'terenowe';
        case DiplomaSeminar = 'seminarium dyplomowe';
        case LanguageCourse = 'lektorat';
        case Conservatory = 'konserwatorium';
    }
    enum LessonStatuses: string
    {
        case Normal = 'normalne';
        case Cancelled = 'odwołane';
        case Consultation = 'konsultacje';
        case Exception = 'wyjątek';
    }
    */
    //TODO: wywalić jak API będzie działać
    //example data
    fetchedRecords = [
        {
            id: 0,
            startDate: '2025-01-21T14:30',
            endDate: '2025-01-21T16:00',
            hours: 2.0,
            worker: 'Karczmarczyk',
            workerCover: null,
            group: '1',
            room: '245',
            subject: 'Programowanie komputerów 1',
            lessonForm: 'wykład',
            lessonStatus: 'normalne'
        }, {
            id: 1,
            startDate: '2025-01-22T14:30',
            endDate: '2025-01-22T16:00',
            hours: 2.0,
            worker: 'Karczmarczyk',
            workerCover: null,
            group: '1',
            room: '245',
            subject: 'Programowanie komputerów 1',
            lessonForm: 'wykład',
            lessonStatus: 'normalne'
        },
        {
            id: '2',
            startDate: '2025-01-23T14:30',
            endDate: '2025-01-23T16:00',
            hours: 2.0,
            worker: 'Karczmarczyk',
            workerCover: null,
            group: '1',
            room: '245',
            subject: 'Programowanie komputerów 1',
            lessonForm: 'wykład',
            lessonStatus: 'normalne'
        }
    ]

    let events = []

    for (let record in fetchedRecords) {

        console.log('dostalem record: ', record)
        events.push(recordToEvent(fetchedRecords[record]))
    }

    console.log(events)
    return events
}

function recordToEvent(record) {

    let event = {
        // id: record['id'],
        title: record['wykladowca'] + ' ' + record['przedmiot'],
        start: record['start']
    }

    console.log('stworzylem event: ', event)
    return event
}

function getWeekStartAndEndDates() {
    const now = new Date();
    const dayOfWeek = now.getDay(); // 0 (Sun) to 6 (Sat)
    const startOfWeek = new Date(now);
    const endOfWeek = new Date(now);

    // Adjust to the start of the week (Monday)
    startOfWeek.setDate(now.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1));
    startOfWeek.setHours(0, 0, 0, 0);

    // Adjust to the end of the week (Sunday)
    endOfWeek.setDate(startOfWeek.getDate() + 6);
    endOfWeek.setHours(23, 59, 59, 999);

    return {
        startOfWeek,
        endOfWeek
    };
}

function lessonSlug(filters) {
    let slug = ''
    //TODO: Pobieranie daty z filtrów
    let date = getWeekStartAndEndDates()

    slug += 'startDate' + '=' + date.startOfWeek.toISOString() + '&endDate=' + date.endOfWeek.toISOString() + '&'

    for (let filter in filters) {
        if (filters[filter] !== null) {
            slug += filter + '=' + filters[filter] + '&'
        }
    }
    slug = slug.slice(0, -1)
    return slug
}

async function fetchRecordsFromAPI(filters) {
    let slug = lessonSlug(filters)
    try {
        const response = await fetch(Endpoints.Lessons + '?' + slug)
        return await response.json()
    } catch (error) {
        console.error(error.message)
        return []
    }
}