export const ATCRating = {
    2: {
        key: 'AS1',
        label: 'ATC Applicant',
        description: 'Rating given when applying for membership.',
        imageUrl: 'https://ivao.aero/data/images/ratings/atc/2.gif',
    },
    3: {
        key: 'AS2',
        label: 'ATC Trainee',
        description:
            'Rating automatically achieved after 10 hours online as a controller.',
        imageUrl: 'https://ivao.aero/data/images/ratings/atc/3.gif',
    },
    4: {
        key: 'AS3',
        label: 'Advanced ATC Trainee',
        description:
            'Rating requires at least 25 hours online as a controller and a successful theoretical Aurora test.',
        imageUrl: 'https://ivao.aero/data/images/ratings/atc/4.gif',
    },
    5: {
        key: 'ADC',
        label: 'Aerodrome Controller',
        description:
            'Rating requires at least 50 hours online as a controller and a successful theoretical and practical test.',
        imageUrl: 'https://ivao.aero/data/images/ratings/atc/5.gif',
    },
    6: {
        key: 'APC',
        label: 'Approach Controller',
        description:
            'Rating requires at least 100 hours online as a controller and a successful theoretical and practical test.',
        imageUrl: 'https://ivao.aero/data/images/ratings/atc/6.gif',
    },
    7: {
        key: 'ACC',
        label: 'Center Controller',
        description:
            'Rating requires at least 200 hours online as a controller and a successful theoretical and practical test.',
        imageUrl: 'https://ivao.aero/data/images/ratings/atc/7.gif',
    },
    8: {
        key: 'SEC',
        label: 'Senior Controller',
        description:
            'Rating requires at least 1000 hours online as a controller, a successful theoretical and practical test, as well as the Senior Private Pilot rating. Additional requirements apply, please check the SEC Briefing Guide.',
        imageUrl: 'https://ivao.aero/data/images/ratings/atc/8.gif',
    },
    9: {
        key: 'SAI',
        label: 'Senior ATC Instructor',
        description:
            'Rating is issued to selected members of the Training Staff and Senior Training Advisors. Given by the Training Director or Training Assistant Director.',
        imageUrl: 'https://ivao.aero/data/images/ratings/atc/9.gif',
    },
    10: {
        key: 'CAI',
        label: 'Chief ATC Instructor',
        description:
            'Rating for the IVAO Training Director & Assistant Director. Given by BoG / Executive on appointment.',
        imageUrl: 'https://ivao.aero/data/images/ratings/atc/10.gif',
    },
} as const;

export type ATCRatingId = keyof typeof ATCRating;
export type ATCRatingValue = (typeof ATCRating)[ATCRatingId];

export const PilotRating = {
    2: {
        key: 'FS1',
        label: 'Basic Flight Student',
        description: 'Rating given when applying for membership.',
        imageUrl: 'https://ivao.aero/data/images/ratings/pilot/2.gif',
    },
    3: {
        key: 'FS2',
        label: 'Flight Student',
        description:
            'Rating automatically achieved after 10 hours online as a pilot.',
        imageUrl: 'https://ivao.aero/data/images/ratings/pilot/3.gif',
    },
    4: {
        key: 'FS3',
        label: 'Advanced Flight Student',
        description:
            'Rating requires at least 25 hours online as a pilot and a successful theoretical Altitude test.',
        imageUrl: 'https://ivao.aero/data/images/ratings/pilot/4.gif',
    },
    5: {
        key: 'PP',
        label: 'Private Pilot',
        description:
            'Rating requires at least 50 hours online as a pilot and a successful theoretical and practical test.',
        imageUrl: 'https://ivao.aero/data/images/ratings/pilot/5.gif',
    },
    6: {
        key: 'SPP',
        label: 'Senior Private Pilot',
        description:
            'Rating requires at least 100 hours online as a pilot and a successful theoretical and practical test.',
        imageUrl: 'https://ivao.aero/data/images/ratings/pilot/6.gif',
    },
    7: {
        key: 'CP',
        label: 'Commercial Pilot',
        description:
            'Rating requires at least 200 hours online as a pilot and a successful theoretical and practical test.',
        imageUrl: 'https://ivao.aero/data/images/ratings/pilot/7.gif',
    },
    8: {
        key: 'ATP',
        label: 'Airline Transport Pilot',
        description:
            'Rating requires at least 750 hours online as a pilot and a successful theoretical and practical test.',
        imageUrl: 'https://ivao.aero/data/images/ratings/pilot/8.gif',
    },
    9: {
        key: 'SFI',
        label: 'Senior Flight Instructor',
        description:
            'Rating is issued to selected members of the Training Staff and Senior Training Advisors. Given by the Training Director or Training Assistant Director.',
        imageUrl: 'https://ivao.aero/data/images/ratings/pilot/9.gif',
    },
    10: {
        key: 'CFI',
        label: 'Chief Flight Instructor',
        description:
            'Rating for the IVAO Training Director & Assistant Director. Given by BoG / Executive on appointment.',
        imageUrl: 'https://ivao.aero/data/images/ratings/pilot/10.gif',
    },
} as const;

export type PilotRatingId = keyof typeof PilotRating;
export type PilotRatingValue = (typeof PilotRating)[PilotRatingId];
