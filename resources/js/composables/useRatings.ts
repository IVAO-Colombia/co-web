import type { ComputedRef } from 'vue';
import { computed } from 'vue';
import { ATCRatings, PilotRatings } from '@/types';
import type { ATCRatingValue, PilotRatingValue, User } from '@/types';

export type UseRatingsReturn = {
    pilotRating: ComputedRef<PilotRatingValue>;
    atcRating: ComputedRef<ATCRatingValue>;
};

export const useRatings = (user: User): UseRatingsReturn => {
    const pilotRating = computed<PilotRatingValue>(
        () => PilotRatings[user.pilot_rating],
    );
    const atcRating = computed<ATCRatingValue>(
        () => ATCRatings[user.atc_rating],
    );

    return {
        pilotRating,
        atcRating,
    };
};
