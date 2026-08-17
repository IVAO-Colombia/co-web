<script setup lang="ts">
import { ArrowRight, X } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

const COOKIE_NAME = 'ivao_colombia_new_website_seen';
const COOKIE_DAYS = 365;

const isVisible = ref(false);

const getCookie = (name: string): string | null => {
    const cookies = document.cookie.split('; ');

    const cookie = cookies.find((row) => row.startsWith(`${name}=`));

    return cookie ? cookie.split('=')[1] : null;
};

const markAsRead = () => {
    const expires = new Date();

    expires.setTime(expires.getTime() + COOKIE_DAYS * 24 * 60 * 60 * 1000);

    document.cookie = `${COOKIE_NAME}=true; expires=${expires.toUTCString()}; path=/; SameSite=Lax`;

    isVisible.value = false;
};

onMounted(() => {
    if (!getCookie(COOKIE_NAME)) {
        isVisible.value = true;
    }
});
</script>

<template>
    <Transition
        enter-active-class="transition duration-500 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-300 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="isVisible"
            class="fixed inset-0 z-[9999] flex h-[100dvh] w-full items-center justify-center overflow-hidden bg-slate-950/70 px-3 py-3 backdrop-blur-md sm:px-6 sm:py-6"
        >
            <Transition
                appear
                enter-active-class="transition duration-500 ease-out"
                enter-from-class="translate-y-6 scale-[0.98] opacity-0"
                enter-to-class="translate-y-0 scale-100 opacity-100"
            >
                <article
                    class="relative flex max-h-[calc(100dvh-1.5rem)] w-full max-w-2xl flex-col overflow-hidden rounded-[28px] border border-white/10 bg-white shadow-2xl sm:max-h-[calc(100dvh-3rem)] dark:bg-slate-950"
                >
                    <!-- Decorative top -->
                    <div
                        class="absolute inset-x-0 top-0 z-20 h-1 bg-primary"
                    ></div>

                    <!-- Close -->
                    <button
                        type="button"
                        class="absolute top-4 right-4 z-30 flex h-9 w-9 cursor-pointer items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-900 sm:top-5 sm:right-5 dark:hover:bg-slate-800 dark:hover:text-white"
                        @click="markAsRead"
                        aria-label="Cerrar"
                    >
                        <X class="size-4" />
                    </button>

                    <!--
                        CONTENIDO DE LA CARTA

                        min-h-0 + flex-1 + overflow-y-auto
                        hace que el scroll quede encerrado
                        dentro de la carta.
                    -->
                    <div
                        class="min-h-0 flex-1 overflow-y-auto overscroll-contain"
                    >
                        <div class="px-6 py-9 sm:px-12 sm:py-12">
                            <!-- Header -->
                            <header class="text-center">
                                <div
                                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-primary text-white shadow-lg shadow-primary/20"
                                >
                                    <img src="/favicon.png" class="size-8" />
                                </div>

                                <h1
                                    class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl dark:text-white"
                                >
                                    Una nueva etapa
                                </h1>

                                <p
                                    class="mt-2 text-sm font-medium text-slate-400"
                                >
                                    comienza hoy
                                </p>
                            </header>

                            <!-- Divider -->
                            <div
                                class="my-7 flex items-center justify-center gap-3 sm:my-8"
                            >
                                <span
                                    class="h-px w-10 bg-slate-200 sm:w-12 dark:bg-slate-800"
                                ></span>

                                <span class="text-xs text-primary"> ✦ </span>

                                <span
                                    class="h-px w-10 bg-slate-200 sm:w-12 dark:bg-slate-800"
                                ></span>
                            </div>

                            <!-- Letter -->
                            <div
                                class="space-y-5 text-[15px] leading-7 text-slate-600 sm:text-base dark:text-slate-300"
                            >
                                <p
                                    class="font-semibold text-slate-900 dark:text-white"
                                >
                                    Querida comunidad de IVAO Colombia:
                                </p>

                                <p>
                                    Hoy queremos compartir con ustedes algo que
                                    nos llena de ilusión: nuestra división
                                    cuenta desde ahora con una
                                    <strong
                                        class="font-semibold text-slate-900 dark:text-white"
                                    >
                                        nueva página web </strong
                                    >.
                                </p>

                                <p>
                                    Este proyecto nace con la intención de
                                    ofrecer un espacio más moderno, claro y
                                    cercano para todos los que forman parte de
                                    nuestra comunidad. Un lugar donde pilotos,
                                    controladores y amantes de la aviación
                                    virtual puedan encontrar información,
                                    eventos, recursos y todo aquello que hace
                                    parte de nuestra división.
                                </p>

                                <p>
                                    Detrás de esta renovación hay muchas horas
                                    de trabajo, ideas, pruebas y también muchas
                                    cosas que todavía queremos mejorar. Por eso,
                                    esta página no representa un punto final,
                                    sino
                                    <strong
                                        class="font-semibold text-slate-900 dark:text-white"
                                    >
                                        el comienzo de una nueva etapa </strong
                                    >.
                                </p>

                                <p>
                                    Queremos seguir construyendo este espacio
                                    junto a ustedes, escuchando sus ideas,
                                    aprendiendo de sus sugerencias y añadiendo
                                    nuevas funcionalidades con el paso del
                                    tiempo.
                                </p>

                                <p>
                                    Esperamos que disfruten esta nueva
                                    experiencia tanto como nosotros disfrutamos
                                    construyéndola.
                                </p>

                                <p class="pt-2">
                                    Gracias por ser parte de IVAO Colombia y por
                                    seguir haciendo crecer nuestra comunidad.
                                </p>
                            </div>

                            <!-- Signature -->
                            <div class="mt-9">
                                <p
                                    class="text-sm text-slate-400 italic"
                                    style="
                                        font-family:
                                            'Homemade Apple', cursive,
                                            sans-serif;
                                    "
                                >
                                    Con aprecio,
                                </p>

                                <p
                                    class="mt-3 text-lg font-bold text-slate-900 dark:text-white"
                                >
                                    El equipo de IVAO Colombia
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    División Colombia
                                </p>
                            </div>

                            <!-- Credits -->
                            <div
                                class="mt-9 border-t border-slate-100 pt-5 dark:border-slate-800"
                            >
                                <p
                                    class="text-[10px] font-bold tracking-widest text-slate-400 uppercase"
                                >
                                    Renovación y desarrollo web
                                </p>

                                <p
                                    class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                                >
                                    <a
                                        href="https://www.ivao.aero/Member.aspx?Id=296066"
                                        class="underline"
                                        >296066
                                    </a>
                                    |
                                    <a
                                        href="https://www.ivao.aero/Member.aspx?Id=648720"
                                        class="underline"
                                        >648720</a
                                    >
                                    |
                                    <a
                                        href="https://www.ivao.aero/Member.aspx?Id=653841"
                                        class="underline"
                                        >653841</a
                                    >
                                </p>
                            </div>

                            <!-- Button -->
                            <button
                                type="button"
                                class="group mt-8 flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-primary/20 transition-all duration-200 hover:brightness-90"
                                @click="markAsRead"
                            >
                                Comenzar a explorar

                                <ArrowRight
                                    class="size-4 transition-transform duration-200 group-hover:translate-x-1"
                                />
                            </button>

                            <p
                                class="mt-4 text-center text-[11px] text-slate-400"
                            >
                                Este mensaje no volverá a mostrarse en este
                                dispositivo.
                            </p>
                        </div>
                    </div>
                </article>
            </Transition>
        </div>
    </Transition>
</template>

<style scoped>
/*
 * Scrollbar de la carta
 */
div::-webkit-scrollbar {
    width: 5px;
}

div::-webkit-scrollbar-track {
    background: transparent;
}

div::-webkit-scrollbar-thumb {
    border-radius: 9999px;
    background: rgb(203 213 225);
}

div::-webkit-scrollbar-thumb:hover {
    background: rgb(148 163 184);
}

.dark div::-webkit-scrollbar-thumb {
    background: rgb(51 65 85);
}
</style>
