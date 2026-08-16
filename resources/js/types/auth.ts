import type { ATCRating, PilotRating } from './backend';

export type User = {
    id: number;
    name: string;
    email: string;
    vid: number;
    country_id: string;
    division_id: string;
    language_id: string;
    network_rating: number;
    atc_rating: ATCRating;
    pilot_rating: PilotRating;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};

export type Auth = {
    user: User;
    permissions: string[];
};

export type TwoFactorConfigContent = {
    title: string;
    description: string;
    buttonText: string;
};
