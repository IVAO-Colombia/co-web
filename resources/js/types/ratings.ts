import { ATCRating, PilotRating } from './backend.d';

export type ATCRatingValue = {
    key: string;
    label: string;
    description: string;
    imageUrl: string;
};

export const ATCRatings: Record<ATCRating, ATCRatingValue> = {
    [ATCRating.AS1]: {
        key: 'AS1',
        label: 'ATC Applicant',
        description: 'Rating given when applying for membership.',
        imageUrl: 'https://ivao.aero/data/images/ratings/atc/2.gif',
    },
    [ATCRating.AS2]: {
        key: 'AS2',
        label: 'ATC Trainee',
        description:
            'Rating automatically achieved after 10 hours online as a controller.',
        imageUrl: 'https://ivao.aero/data/images/ratings/atc/3.gif',
    },
    [ATCRating.AS3]: {
        key: 'AS3',
        label: 'Advanced ATC Trainee',
        description:
            'Rating requires at least 25 hours online as a controller and a successful theoretical Aurora test.',
        imageUrl: 'https://ivao.aero/data/images/ratings/atc/4.gif',
    },
    [ATCRating.ADC]: {
        key: 'ADC',
        label: 'Aerodrome Controller',
        description:
            'Rating requires at least 50 hours online as a controller and a successful theoretical and practical test.',
        imageUrl: 'https://ivao.aero/data/images/ratings/atc/5.gif',
    },
    [ATCRating.APC]: {
        key: 'APC',
        label: 'Approach Controller',
        description:
            'Rating requires at least 100 hours online as a controller and a successful theoretical and practical test.',
        imageUrl: 'https://ivao.aero/data/images/ratings/atc/6.gif',
    },
    [ATCRating.ACC]: {
        key: 'ACC',
        label: 'Center Controller',
        description:
            'Rating requires at least 200 hours online as a controller and a successful theoretical and practical test.',
        imageUrl: 'https://ivao.aero/data/images/ratings/atc/7.gif',
    },
    [ATCRating.SEC]: {
        key: 'SEC',
        label: 'Senior Controller',
        description:
            'Rating requires at least 1000 hours online as a controller, a successful theoretical and practical test, as well as the Senior Private Pilot rating. Additional requirements apply, please check the SEC Briefing Guide.',
        imageUrl: 'https://ivao.aero/data/images/ratings/atc/8.gif',
    },
    [ATCRating.SAI]: {
        key: 'SAI',
        label: 'Senior ATC Instructor',
        description:
            'Rating is issued to selected members of the Training Staff and Senior Training Advisors. Given by the Training Director or Training Assistant Director.',
        imageUrl: 'https://ivao.aero/data/images/ratings/atc/9.gif',
    },
    [ATCRating.CAI]: {
        key: 'CAI',
        label: 'Chief ATC Instructor',
        description:
            'Rating for the IVAO Training Director & Assistant Director. Given by BoG / Executive on appointment.',
        imageUrl: 'https://ivao.aero/data/images/ratings/atc/10.gif',
    },
};

export type PilotRatingValue = {
    key: string;
    label: string;
    description: string;
    imageUrl: string;
};

export const PilotRatings: Record<PilotRating, PilotRatingValue> = {
    [PilotRating.FS1]: {
        key: 'FS1',
        label: 'Basic Flight Student',
        description: 'Rating given when applying for membership.',
        imageUrl: 'https://ivao.aero/data/images/ratings/pilot/2.gif',
    },
    [PilotRating.FS2]: {
        key: 'FS2',
        label: 'Flight Student',
        description:
            'Rating automatically achieved after 10 hours online as a pilot.',
        imageUrl: 'https://ivao.aero/data/images/ratings/pilot/3.gif',
    },
    [PilotRating.FS3]: {
        key: 'FS3',
        label: 'Advanced Flight Student',
        description:
            'Rating requires at least 25 hours online as a pilot and a successful theoretical Altitude test.',
        imageUrl: 'https://ivao.aero/data/images/ratings/pilot/4.gif',
    },
    [PilotRating.PP]: {
        key: 'PP',
        label: 'Private Pilot',
        description:
            'Rating requires at least 50 hours online as a pilot and a successful theoretical and practical test.',
        imageUrl: 'https://ivao.aero/data/images/ratings/pilot/5.gif',
    },
    [PilotRating.SPP]: {
        key: 'SPP',
        label: 'Senior Private Pilot',
        description:
            'Rating requires at least 100 hours online as a pilot and a successful theoretical and practical test.',
        imageUrl: 'https://ivao.aero/data/images/ratings/pilot/6.gif',
    },
    [PilotRating.CP]: {
        key: 'CP',
        label: 'Commercial Pilot',
        description:
            'Rating requires at least 200 hours online as a pilot and a successful theoretical and practical test.',
        imageUrl: 'https://ivao.aero/data/images/ratings/pilot/7.gif',
    },
    [PilotRating.ATP]: {
        key: 'ATP',
        label: 'Airline Transport Pilot',
        description:
            'Rating requires at least 750 hours online as a pilot and a successful theoretical and practical test.',
        imageUrl: 'https://ivao.aero/data/images/ratings/pilot/8.gif',
    },
    [PilotRating.SFI]: {
        key: 'SFI',
        label: 'Senior Flight Instructor',
        description:
            'Rating is issued to selected members of the Training Staff and Senior Training Advisors. Given by the Training Director or Training Assistant Director.',
        imageUrl: 'https://ivao.aero/data/images/ratings/pilot/9.gif',
    },
    [PilotRating.CFI]: {
        key: 'CFI',
        label: 'Chief Flight Instructor',
        description:
            'Rating for the IVAO Training Director & Assistant Director. Given by BoG / Executive on appointment.',
        imageUrl: 'https://ivao.aero/data/images/ratings/pilot/10.gif',
    },
};
