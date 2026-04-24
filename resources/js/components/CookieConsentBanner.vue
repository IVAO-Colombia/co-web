<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';

const STORAGE_KEY = 'ivao_cookie_consent_v1';

const isMounted = ref(false);
const hasDecision = ref(false);


const shouldShowBanner = computed(() => isMounted.value && !hasDecision.value);

function setCookie(name: string, value: string, days = 365): void {
    if (typeof document === 'undefined') {
        return;
    }

    const maxAge = days * 24 * 60 * 60;

    document.cookie = `${name}=${value};path=/;max-age=${maxAge};SameSite=Lax`;
}

function setConsent(value: 'accepted' | 'rejected'): void {
    hasDecision.value = true;

    if (typeof window !== 'undefined') {
        window.localStorage.setItem(STORAGE_KEY, value);
    }

    setCookie('cookie_consent', value);
}

onMounted(() => {
    if (typeof window === 'undefined') {
        return;
    }

    const savedConsent = window.localStorage.getItem(STORAGE_KEY);

    if (savedConsent === 'accepted' || savedConsent === 'rejected') {
        hasDecision.value = true;
        setCookie('cookie_consent', savedConsent);
    }

    isMounted.value = true;
});
</script>

<template>
    <transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="translate-y-2 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-2 opacity-0"
    >
        <aside
            v-if="shouldShowBanner"
            class="fixed right-3 bottom-3 left-3 z-80 rounded-2xl border border-slate-200/80 bg-white/95 p-4 shadow-[0_20px_45px_rgba(15,23,42,0.22)] backdrop-blur-sm sm:right-6 sm:bottom-6 sm:left-auto sm:max-w-md dark:border-slate-700/70 dark:bg-slate-900/92"
            role="dialog"
            aria-live="polite"
            aria-label="Cookie consent"
        >
            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                {{ $t('Cookie usage') }}
            </p>
            <p
                class="mt-1.5 text-sm leading-5 text-slate-600 dark:text-slate-300"
            >
                {{ $t('This site uses functional cookies that are necessary for it to work properly. We do not use tracking or advertising cookies.') }}
            </p>

            <div class="mt-3 flex items-center justify-end gap-2">
                <button
                    type="button"
                    class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700"
                    @click="setConsent('rejected')"
                >
                    {{ $t('Reject') }}
                </button>
                <button
                    type="button"
                    class="inline-flex items-center rounded-lg bg-[#1d4ed8] px-3 py-2 text-sm font-semibold text-white transition-colors hover:bg-[#1e40af]"
                    @click="setConsent('accepted')"
                >
                    {{ $t('Accept') }}
                </button>
            </div>
        </aside>
    </transition>
</template>
