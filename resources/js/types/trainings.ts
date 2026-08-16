import {
    ATCRating,
    AtcTraining,
    PilotRating,
    PilotTraining,
} from './backend.d';

export type AtcTrainingValue = {
    key: keyof typeof AtcTraining;
    label: string;
    minimumAtcRating: ATCRating;
    order: number;
};

export const AtcTrainings: Record<AtcTraining, AtcTrainingValue> = {
    [AtcTraining.As2As3Intro]: {
        key: 'As2As3Intro',
        label: 'AS2/AS3 - Introducción como ATC',
        minimumAtcRating: ATCRating.AS1,
        order: 1,
    },
    [AtcTraining.AdcTheory1]: {
        key: 'AdcTheory1',
        label: 'ADC - Entrenamiento Teórico parte 1',
        minimumAtcRating: ATCRating.AS3,
        order: 2,
    },
    [AtcTraining.AdcTheory2]: {
        key: 'AdcTheory2',
        label: 'ADC - Entrenamiento Teórico parte 2',
        minimumAtcRating: ATCRating.AS3,
        order: 3,
    },
    [AtcTraining.AdcTheory3]: {
        key: 'AdcTheory3',
        label: 'ADC - Entrenamiento Teórico parte 3',
        minimumAtcRating: ATCRating.AS3,
        order: 4,
    },
    [AtcTraining.AdcTheory4]: {
        key: 'AdcTheory4',
        label: 'ADC - Entrenamiento Teórico parte 4',
        minimumAtcRating: ATCRating.AS3,
        order: 5,
    },
    [AtcTraining.AdcPractical]: {
        key: 'AdcPractical',
        label: 'ADC - Entrenamiento Práctico',
        minimumAtcRating: ATCRating.AS3,
        order: 6,
    },
    [AtcTraining.ApcTheory1]: {
        key: 'ApcTheory1',
        label: 'APC - Entrenamiento Teórico parte 1',
        minimumAtcRating: ATCRating.ADC,
        order: 8,
    },
    [AtcTraining.ApcTheory2]: {
        key: 'ApcTheory2',
        label: 'APC - Entrenamiento Teórico parte 2',
        minimumAtcRating: ATCRating.ADC,
        order: 9,
    },
    [AtcTraining.ApcTheory3]: {
        key: 'ApcTheory3',
        label: 'APC - Entrenamiento Teórico parte 3',
        minimumAtcRating: ATCRating.ADC,
        order: 10,
    },
    [AtcTraining.ApcPractical]: {
        key: 'ApcPractical',
        label: 'APC - Entrenamiento Práctico',
        minimumAtcRating: ATCRating.ADC,
        order: 11,
    },
    [AtcTraining.AccTheory1]: {
        key: 'AccTheory1',
        label: 'ACC - Entrenamiento Teórico parte 1',
        minimumAtcRating: ATCRating.APC,
        order: 12,
    },
    [AtcTraining.AccTheory2]: {
        key: 'AccTheory2',
        label: 'ACC - Entrenamiento Teórico parte 2',
        minimumAtcRating: ATCRating.APC,
        order: 13,
    },
    [AtcTraining.AccPractical]: {
        key: 'AccPractical',
        label: 'ACC - Entrenamiento Práctico',
        minimumAtcRating: ATCRating.APC,
        order: 14,
    },
};

export type PilotTrainingValue = {
    key: keyof typeof PilotTraining;
    label: string;
    minimumPilotRating: PilotRating;
    order: number;
};

export const PilotTrainings: Record<PilotTraining, PilotTrainingValue> = {
    [PilotTraining.Fs2Fs3Intro]: {
        key: 'Fs2Fs3Intro',
        label: 'FS2/FS3 - Introducción como Piloto',
        minimumPilotRating: PilotRating.FS1,
        order: 1,
    },
    [PilotTraining.PpTheory1]: {
        key: 'PpTheory1',
        label: 'PP - Entrenamiento Teórico Parte 1',
        minimumPilotRating: PilotRating.FS3,
        order: 2,
    },
    [PilotTraining.PpTheory2]: {
        key: 'PpTheory2',
        label: 'PP - Entrenamiento Teórico Parte 2',
        minimumPilotRating: PilotRating.FS3,
        order: 3,
    },
    [PilotTraining.PpTheory3]: {
        key: 'PpTheory3',
        label: 'PP - Entrenamiento Teórico Parte 3',
        minimumPilotRating: PilotRating.FS3,
        order: 4,
    },
    [PilotTraining.PpTheory4]: {
        key: 'PpTheory4',
        label: 'PP - Entrenamiento Teórico Parte 4',
        minimumPilotRating: PilotRating.FS3,
        order: 5,
    },
    [PilotTraining.PpTheory5]: {
        key: 'PpTheory5',
        label: 'PP - Entrenamiento Teórico Parte 5',
        minimumPilotRating: PilotRating.FS3,
        order: 6,
    },
    [PilotTraining.PpPractical]: {
        key: 'PpPractical',
        label: 'PP - Entrenamiento Práctico',
        minimumPilotRating: PilotRating.FS3,
        order: 7,
    },
    [PilotTraining.SppTheory1]: {
        key: 'SppTheory1',
        label: 'SPP - Entrenamiento Teórico parte 1',
        minimumPilotRating: PilotRating.PP,
        order: 8,
    },
    [PilotTraining.SppTheory2]: {
        key: 'SppTheory2',
        label: 'SPP - Entrenamiento Teórico parte 2',
        minimumPilotRating: PilotRating.PP,
        order: 9,
    },
    [PilotTraining.SppTheory3]: {
        key: 'SppTheory3',
        label: 'SPP - Entrenamiento Teórico parte 3',
        minimumPilotRating: PilotRating.PP,
        order: 10,
    },
    [PilotTraining.SppTheory4]: {
        key: 'SppTheory4',
        label: 'SPP - Entrenamiento Teórico parte 4',
        minimumPilotRating: PilotRating.PP,
        order: 11,
    },
    [PilotTraining.SppPractical]: {
        key: 'SppPractical',
        label: 'SPP - Entrenamiento Práctico',
        minimumPilotRating: PilotRating.PP,
        order: 12,
    },
    [PilotTraining.CpTheory1]: {
        key: 'CpTheory1',
        label: 'CP - Entrenamiento Teórico parte 1',
        minimumPilotRating: PilotRating.SPP,
        order: 13,
    },
    [PilotTraining.CpTheory2]: {
        key: 'CpTheory2',
        label: 'CP - Entrenamiento Teórico parte 2',
        minimumPilotRating: PilotRating.SPP,
        order: 14,
    },
    [PilotTraining.CpTheory3]: {
        key: 'CpTheory3',
        label: 'CP - Entrenamiento Teórico parte 3',
        minimumPilotRating: PilotRating.SPP,
        order: 15,
    },
    [PilotTraining.CpTheory4]: {
        key: 'CpTheory4',
        label: 'CP - Entrenamiento Teórico parte 4',
        minimumPilotRating: PilotRating.SPP,
        order: 16,
    },
    [PilotTraining.CpPractical]: {
        key: 'CpPractical',
        label: 'CP - Entrenamiento Práctico',
        minimumPilotRating: PilotRating.SPP,
        order: 17,
    },
};
