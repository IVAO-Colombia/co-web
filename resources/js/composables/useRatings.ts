import type { ComputedRef } from 'vue';
import { computed } from 'vue';
import { ATCRating, PilotRating } from '@/types';
import type { ATCRatingValue, PilotRatingValue, User } from '@/types';

export type UseRatingsReturn = {
    pilotRating: ComputedRef<PilotRatingValue>;
    atcRating: ComputedRef<ATCRatingValue>;
};

export const useRatings = (user: User): UseRatingsReturn => {
    const pilotRating = computed<PilotRatingValue>(
        () => PilotRating[user.pilot_rating],
    );
    const atcRating = computed<ATCRatingValue>(
        () => ATCRating[user.atc_rating],
    );

    return {
        pilotRating,
        atcRating,
    };
};
