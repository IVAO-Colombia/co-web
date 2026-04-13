import type { ATCRatingId, PilotRatingId } from './ratings';

export type User = {
    id: number;
    name: string;
    email: string;
    vid: number;
    country_id: string;
    division_id: string;
    language_id: string;
    network_rating: number;
    atc_rating: ATCRatingId;
    pilot_rating: PilotRatingId;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};

export type Auth = {
    user: User;
};

export type TwoFactorConfigContent = {
    title: string;
    description: string;
    buttonText: string;
};
